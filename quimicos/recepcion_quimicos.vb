Imports MySql.Data.MySqlClient
Imports System.Configuration
Imports System.Windows.Forms

Public Class recepcion_quimicos
    Public cnx As New MySqlConnection(ConfigurationManager.ConnectionStrings("cnxstring").ToString())
    Public permiso As Boolean = False

    ' ============================================================
    ' LOAD
    ' ============================================================
    Private Sub recepcion_quimicos_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        ' Configurar grilla inferior (dg_entrada)
        With dg_entrada.Columns
            .Clear()
            .Add("quimico_codigo", "Código")
            .Add("nombre", "Nombre")
            .Add("tipo", "Tipo")
            .Add("unidad_medida", "Unidad")
            .Add("registro_sag", "Reg SAG")
            .Add("cant_envases", "Cant. Envases")
            .Add("contenido_envase", "Contenido (por envase)")
            .Add("cantidad_total", "Cantidad Total")
            .Add("unidad_entrada", "Unidad")
        End With
        dg_entrada.Columns("cant_envases").ReadOnly = False
        dg_entrada.Columns("contenido_envase").ReadOnly = False
        dg_entrada.Columns("unidad_entrada").ReadOnly = False

        ' Eventos para habilitar Guardar cuando se modifica el grid
        AddHandler dg_entrada.RowsAdded, AddressOf dg_entrada_Cambio
        AddHandler dg_entrada.RowsRemoved, AddressOf dg_entrada_Cambio
        AddHandler txt_guia.TextChanged, AddressOf Cabecera_Cambio
        AddHandler txt_factura.TextChanged, AddressOf Cabecera_Cambio

        ' Cargar bodegas y proveedores
        cargarBodegas()
        cargarProveedores()
        cargarCatalogo()
        actualizarEstadoBotonGuardar()
    End Sub

    Private Sub dg_entrada_Cambio(sender As Object, e As EventArgs)
        actualizarEstadoBotonGuardar()
    End Sub

    Private Sub Cabecera_Cambio(sender As Object, e As EventArgs)
        actualizarEstadoBotonGuardar()
    End Sub

    Private Sub actualizarEstadoBotonGuardar()
        Dim tieneGuia As Boolean = Not String.IsNullOrWhiteSpace(txt_guia.Text)
        Dim tieneItems As Boolean = dg_entrada.Rows.Count > 0
        btn_guardar.Enabled = tieneGuia AndAlso tieneItems
    End Sub

    ' ============================================================
    ' CARGAR BODEGAS
    ' ============================================================
    Private Sub cargarBodegas()
        cbo_bodega.Items.Clear()
        Try
            cnx.Open()
            Dim da As New MySqlDataAdapter("SELECT codigo, nombre FROM quimicos_bodegas WHERE activo = 1 ORDER BY codigo", cnx)
            Dim dt As New DataTable
            da.Fill(dt)
            cnx.Close()

            ' Guardamos codigo y nombre en un ComboBox con clave/valor
            Dim ds As New DataSet
            dt.TableName = "bodega"
            ds.Tables.Add(dt)
            cbo_bodega.DataSource = dt
            cbo_bodega.DisplayMember = "nombre"
            cbo_bodega.ValueMember = "codigo"
            If cbo_bodega.Items.Count > 0 Then cbo_bodega.SelectedIndex = 0
        Catch ex As Exception
            MessageBox.Show("Error al cargar bodegas: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If cnx.State = ConnectionState.Open Then cnx.Close()
        End Try
    End Sub

    ' ============================================================
    ' CARGAR PROVEEDORES
    ' ============================================================
    Private Sub cargarProveedores()
        cbo_proveedor.Items.Clear()
        Try
            cnx.Open()
            Dim da As New MySqlDataAdapter("SELECT id, nombre FROM quimicos_proveedores WHERE activo = 1 ORDER BY nombre", cnx)
            Dim dt As New DataTable
            da.Fill(dt)
            cnx.Close()

            ' Insertar marca "(LIBRE)" al inicio para texto libre
            Dim dtFinal As DataTable = dt.Clone()
            Dim rowLibre = dtFinal.NewRow()
            rowLibre("id") = 0
            rowLibre("nombre") = "(LIBRE - escribir manualmente)"
            dtFinal.Rows.Add(rowLibre)
            For Each r As DataRow In dt.Rows
                dtFinal.ImportRow(r)
            Next

            cbo_proveedor.DataSource = dtFinal
            cbo_proveedor.DisplayMember = "nombre"
            cbo_proveedor.ValueMember = "id"
            cbo_proveedor.SelectedIndex = 0
        Catch ex As Exception
            MessageBox.Show("Error al cargar proveedores: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If cnx.State = ConnectionState.Open Then cnx.Close()
        End Try
    End Sub

    ' ============================================================
    ' CARGAR CATÁLOGO DE QUÍMICOS (dg_detalle)
    ' Muestra stock actual en la bodega seleccionada
    ' ============================================================
    Private Sub cargarCatalogo()
        Try
            Dim bodegaCod As String = If(cbo_bodega.SelectedIndex >= 0, cbo_bodega.SelectedValue.ToString(), "")

            Dim sql As String =
                "SELECT q.codigo, q.nombre, q.tipo, q.unidad_medida, q.registro_sag, " &
                "       IFNULL(e.cantidad, 0) AS stock_actual " &
                "FROM quimicos q " &
                "LEFT JOIN quimicos_existencia e ON e.quimico_codigo = q.codigo AND e.bodega = ?bod " &
                "WHERE q.activo = 1 " &
                "ORDER BY q.nombre"

            cnx.Open()
            Dim da As New MySqlDataAdapter(sql, cnx)
            da.SelectCommand.Parameters.AddWithValue("?bod", bodegaCod)
            Dim dt As New DataTable
            da.Fill(dt)
            cnx.Close()

            dg_detalle.AutoGenerateColumns = True
            dg_detalle.DataSource = dt
            dg_detalle.Columns("codigo").HeaderText = "Código"
            dg_detalle.Columns("nombre").HeaderText = "Nombre"
            dg_detalle.Columns("tipo").HeaderText = "Tipo"
            dg_detalle.Columns("unidad_medida").HeaderText = "Unidad"
            dg_detalle.Columns("registro_sag").HeaderText = "Reg SAG"
            dg_detalle.Columns("stock_actual").HeaderText = "Stock Actual"
        Catch ex As Exception
            MessageBox.Show("Error al cargar químicos: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If cnx.State = ConnectionState.Open Then cnx.Close()
        End Try
    End Sub

    ' Cuando cambia bodega, recargar stock
    Private Sub cbo_bodega_SelectedIndexChanged(sender As Object, e As EventArgs) Handles cbo_bodega.SelectedIndexChanged
        cargarCatalogo()
    End Sub

    ' ============================================================
    ' AGREGAR QUÍMICO A RECEPCIÓN
    ' ============================================================
    Private Sub btn_agregar_Click(sender As Object, e As EventArgs) Handles btn_agregar.Click
        If dg_detalle.SelectedRows.Count = 0 Then
            MessageBox.Show("Seleccione un químico del catálogo", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Return
        End If

        Dim row = dg_detalle.SelectedRows(0)
        Dim codigo As String = row.Cells("codigo").Value.ToString()

        ' Evitar duplicados
        For Each dr As DataGridViewRow In dg_entrada.Rows
            If dr.Cells("quimico_codigo").Value.ToString() = codigo Then
                MessageBox.Show("Ese químico ya está en la recepción", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Information)
                Return
            End If
        Next

        ' Agregar nueva fila con valores por defecto
        Dim unidad As String = row.Cells("unidad_medida").Value.ToString()
        dg_entrada.Rows.Add(
            codigo,
            row.Cells("nombre").Value.ToString(),
            row.Cells("tipo").Value.ToString(),
            unidad,
            row.Cells("registro_sag").Value.ToString(),
            1,           ' cant_envases
            1,           ' contenido_envase
            1,           ' cantidad_total (calculada)
            unidad       ' unidad_entrada
        )

        ' Disparar recálculo
        dg_entrada.CurrentCell = dg_entrada.Rows(dg_entrada.Rows.Count - 1).Cells("cant_envases")
        recalcularCantidad(dg_entrada.Rows(dg_entrada.Rows.Count - 1))
    End Sub

    ' ============================================================
    ' QUITAR QUÍMICO DE RECEPCIÓN
    ' ============================================================
    Private Sub btn_quitar_Click(sender As Object, e As EventArgs) Handles btn_quitar.Click
        If dg_entrada.SelectedRows.Count = 0 Then
            MessageBox.Show("Seleccione un químico de la lista inferior", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Return
        End If
        dg_entrada.Rows.Remove(dg_entrada.SelectedRows(0))
    End Sub

    ' ============================================================
    ' RECALCULAR CANTIDAD TOTAL cuando editan cant_envases o contenido_envase
    ' ============================================================
    Private Sub dg_entrada_CellValueChanged(sender As Object, e As DataGridViewCellEventArgs) Handles dg_entrada.CellValueChanged
        If e.RowIndex < 0 Then Return
        Dim colName As String = dg_entrada.Columns(e.ColumnIndex).Name
        If colName = "cant_envases" Or colName = "contenido_envase" Then
            recalcularCantidad(dg_entrada.Rows(e.RowIndex))
        End If
    End Sub

    Private Sub dg_entrada_CellEndEdit(sender As Object, e As DataGridViewCellEventArgs) Handles dg_entrada.CellEndEdit
        If e.RowIndex < 0 Then Return
        Dim colName As String = dg_entrada.Columns(e.ColumnIndex).Name
        If colName = "cant_envases" Or colName = "contenido_envase" Then
            recalcularCantidad(dg_entrada.Rows(e.RowIndex))
        End If
    End Sub

    Private Sub recalcularCantidad(row As DataGridViewRow)
        Dim cantEnvases As Decimal = 0
        Dim contenido As Decimal = 0
        Dim v1 = row.Cells("cant_envases").Value
        Dim v2 = row.Cells("contenido_envase").Value
        If v1 IsNot Nothing AndAlso v1.ToString() <> "" Then Decimal.TryParse(v1.ToString(), cantEnvases)
        If v2 IsNot Nothing AndAlso v2.ToString() <> "" Then Decimal.TryParse(v2.ToString(), contenido)
        row.Cells("cantidad_total").Value = cantEnvases * contenido
    End Sub

    ' ============================================================
    ' VALIDAR ANTES DE GUARDAR
    ' ============================================================
    Private Function validar() As Boolean
        If cbo_bodega.SelectedIndex < 0 Then
            MessageBox.Show("Seleccione una bodega", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return False
        End If
        If String.IsNullOrWhiteSpace(txt_guia.Text) Then
            MessageBox.Show("Ingrese el número de GUIA", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return False
        End If
        If dg_entrada.Rows.Count = 0 Then
            MessageBox.Show("Agregue al menos un químico a la recepción", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return False
        End If
        ' Verificar cantidades válidas
        For Each row As DataGridViewRow In dg_entrada.Rows
            Dim cant As Decimal = 0
            If row.Cells("cantidad_total").Value IsNot Nothing AndAlso row.Cells("cantidad_total").Value.ToString() <> "" Then
                Decimal.TryParse(row.Cells("cantidad_total").Value.ToString(), cant)
            End If
            If cant <= 0 Then
                MessageBox.Show("El químico '" & row.Cells("nombre").Value.ToString() & "' tiene cantidad 0 o inválida", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return False
            End If
            Dim uni As String = If(row.Cells("unidad_entrada").Value IsNot Nothing, row.Cells("unidad_entrada").Value.ToString(), "")
            If String.IsNullOrWhiteSpace(uni) Then
                MessageBox.Show("El químico '" & row.Cells("nombre").Value.ToString() & "' no tiene unidad asignada", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return False
            End If
        Next
        Return True
    End Function

    ' ============================================================
    ' OBTENER PROVEEDOR (catálogo o texto libre)
    ' ============================================================
    Private Function obtenerProveedor() As String
        If cbo_proveedor.SelectedIndex <= 0 Then
            ' LIBRE: usar el texto del campo libre
            Return If(String.IsNullOrWhiteSpace(txt_proveedor_libre.Text), "", txt_proveedor_libre.Text.Trim().ToUpper())
        Else
            ' Catálogo: usar el nombre del catálogo
            Return cbo_proveedor.Text
        End If
    End Function

    ' Cuando cambia el combo, mostrar/ocultar campo libre
    Private Sub cbo_proveedor_SelectedIndexChanged(sender As Object, e As EventArgs) Handles cbo_proveedor.SelectedIndexChanged
        If cbo_proveedor.SelectedIndex <= 0 Then
            txt_proveedor_libre.Visible = True
            txt_proveedor_libre.Focus()
        Else
            txt_proveedor_libre.Visible = False
            txt_proveedor_libre.Clear()
        End If
    End Sub

    ' ============================================================
    ' GUARDAR RECEPCIÓN
    ' ============================================================
    Private Sub btn_guardar_Click(sender As Object, e As EventArgs) Handles btn_guardar.Click
        If Not validar() Then Return

        Dim bodegaCod As String = cbo_bodega.SelectedValue.ToString()
        Dim proveedor As String = obtenerProveedor()
        If String.IsNullOrWhiteSpace(proveedor) Then
            MessageBox.Show("Ingrese un proveedor (catálogo o texto libre)", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        Dim respuesta = MessageBox.Show("¿Confirma registrar esta recepción?", "Confirmar", MessageBoxButtons.YesNo, MessageBoxIcon.Question)
        If respuesta <> DialogResult.Yes Then Return

        Try
            cnx.Open()
            Dim transaction As MySqlTransaction = cnx.BeginTransaction()

            ' 1) Insertar cabecera (empresa y temporada quedan vacías - químico es independiente)
            Dim cmdCab As New MySqlCommand(
                "INSERT INTO quimicos_recepcion (fecha, guia, factura, proveedor, lote_proveedor, bodega, responsable, observaciones, neto_clp, iva_clp, total_clp, neto_usd, total_usd) " &
                "VALUES (CURDATE(), ?guia, ?factura, ?prov, ?lote, ?bod, NULL, ?obs, ?neto_clp, ?iva_clp, ?total_clp, ?neto_usd, ?total_usd)", cnx, transaction)
            cmdCab.Parameters.AddWithValue("?guia", txt_guia.Text.Trim())
            cmdCab.Parameters.AddWithValue("?factura", If(String.IsNullOrWhiteSpace(txt_factura.Text), DBNull.Value, txt_factura.Text.Trim()))
            cmdCab.Parameters.AddWithValue("?prov", proveedor)
            cmdCab.Parameters.AddWithValue("?lote", If(String.IsNullOrWhiteSpace(txt_lote.Text), DBNull.Value, txt_lote.Text.Trim()))
            cmdCab.Parameters.AddWithValue("?bod", bodegaCod)
            cmdCab.Parameters.AddWithValue("?obs", If(String.IsNullOrWhiteSpace(txt_observaciones.Text), DBNull.Value, txt_observaciones.Text.Trim()))
            cmdCab.Parameters.AddWithValue("?neto_clp", parseMonto(txt_neto_clp.Text))
            cmdCab.Parameters.AddWithValue("?iva_clp", parseMonto(txt_iva_clp.Text))
            cmdCab.Parameters.AddWithValue("?total_clp", parseMonto(txt_total_clp.Text))
            cmdCab.Parameters.AddWithValue("?neto_usd", parseMonto(txt_neto_usd.Text))
            cmdCab.Parameters.AddWithValue("?total_usd", parseMonto(txt_total_usd.Text))
            cmdCab.ExecuteNonQuery()
            Dim nRecepcion As Integer = CInt(cmdCab.LastInsertedId)

            ' 2) Insertar detalles y actualizar stock
            For Each row As DataGridViewRow In dg_entrada.Rows
                Dim codigo As String = row.Cells("quimico_codigo").Value.ToString()
                Dim cantEnvases As Decimal = 0
                Dim contenido As Decimal = 0
                Dim total As Decimal = 0
                If row.Cells("cant_envases").Value IsNot Nothing Then Decimal.TryParse(row.Cells("cant_envases").Value.ToString(), cantEnvases)
                If row.Cells("contenido_envase").Value IsNot Nothing Then Decimal.TryParse(row.Cells("contenido_envase").Value.ToString(), contenido)
                If row.Cells("cantidad_total").Value IsNot Nothing Then Decimal.TryParse(row.Cells("cantidad_total").Value.ToString(), total)
                Dim uni As String = If(row.Cells("unidad_entrada").Value IsNot Nothing, row.Cells("unidad_entrada").Value.ToString(), "")

                ' Detalle
                Dim cmdDet As New MySqlCommand(
                    "INSERT INTO quimicos_recepcion_detalle (n_recepcion, quimico_codigo, cant_envases, contenido_envase, cantidad_total, unidad) " &
                    "VALUES (?n, ?cod, ?ce, ?co, ?ct, ?uni)", cnx, transaction)
                cmdDet.Parameters.AddWithValue("?n", nRecepcion)
                cmdDet.Parameters.AddWithValue("?cod", codigo)
                cmdDet.Parameters.AddWithValue("?ce", cantEnvases)
                cmdDet.Parameters.AddWithValue("?co", contenido)
                cmdDet.Parameters.AddWithValue("?ct", total)
                cmdDet.Parameters.AddWithValue("?uni", uni)
                cmdDet.ExecuteNonQuery()

                ' UPSERT existencia
                Dim cmdEx As New MySqlCommand(
                    "INSERT INTO quimicos_existencia (quimico_codigo, bodega, cantidad, unidad) VALUES (?cod, ?bod, ?cant, ?uni) " &
                    "ON DUPLICATE KEY UPDATE cantidad = cantidad + ?cant, unidad = ?uni", cnx, transaction)
                cmdEx.Parameters.AddWithValue("?cod", codigo)
                cmdEx.Parameters.AddWithValue("?bod", bodegaCod)
                cmdEx.Parameters.AddWithValue("?cant", total)
                cmdEx.Parameters.AddWithValue("?uni", uni)
                cmdEx.ExecuteNonQuery()
            Next

            transaction.Commit()
            cnx.Close()

            MessageBox.Show("Recepción #" & nRecepcion & " registrada correctamente", "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information)
            limpiarTodo()

        Catch ex As Exception
            MessageBox.Show("Error al guardar: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If cnx.State = ConnectionState.Open Then cnx.Close()
        End Try
    End Sub

    ' ============================================================
    ' LIMPIAR
    ' ============================================================
    Private Sub limpiarTodo()
        dg_entrada.Rows.Clear()
        txt_guia.Clear()
        txt_factura.Clear()
        txt_lote.Clear()
        txt_observaciones.Clear()
        txt_proveedor_libre.Clear()
        txt_proveedor_libre.Visible = False
        txt_neto_clp.Clear()
        txt_iva_clp.Clear()
        txt_total_clp.Clear()
        txt_neto_usd.Clear()
        txt_total_usd.Clear()
        cbo_proveedor.SelectedIndex = 0
        cargarCatalogo()
    End Sub

    Private Sub btn_limpiar_Click(sender As Object, e As EventArgs) Handles btn_limpiar.Click
        limpiarTodo()
    End Sub

    ' ============================================================
    ' CÁLCULO AUTOMÁTICO DE MONTOS
    ' Neto CLP + IVA = Total CLP
    ' Neto USD + (Neto USD * 0.19) = Total USD  (opcional)
    ' ============================================================
    Private Sub txt_neto_clp_Leave(sender As Object, e As EventArgs) Handles txt_neto_clp.Leave
        calcularMontosCLP()
    End Sub
    Private Sub txt_iva_clp_Leave(sender As Object, e As EventArgs) Handles txt_iva_clp.Leave
        calcularMontosCLP()
    End Sub

    Private Sub calcularMontosCLP()
        Dim neto As Decimal = parseMonto(txt_neto_clp.Text)
        Dim iva As Decimal = parseMonto(txt_iva_clp.Text)
        If neto > 0 AndAlso iva = 0 Then
            iva = Math.Round(neto * 0.19, 0)
            txt_iva_clp.Text = iva.ToString("N0")
        End If
        Dim total As Decimal = neto + iva
        txt_total_clp.Text = total.ToString("N0")
    End Sub

    Private Sub txt_neto_usd_Leave(sender As Object, e As EventArgs) Handles txt_neto_usd.Leave
        calcularMontosUSD()
    End Sub

    Private Sub calcularMontosUSD()
        Dim neto As Decimal = parseMonto(txt_neto_usd.Text)
        Dim total As Decimal = parseMonto(txt_total_usd.Text)
        If neto > 0 AndAlso total = 0 Then
            total = neto
            txt_total_usd.Text = total.ToString("N2")
        End If
    End Sub

    ' Parsear texto a decimal. Acepta formato con puntos/comas miles y coma/punto decimal
    Private Function parseMonto(texto As String) As Decimal
        If String.IsNullOrWhiteSpace(texto) Then Return 0
        Dim s As String = texto.Trim().Replace(".", "").Replace(",", ".")
        Dim v As Decimal
        If Decimal.TryParse(s, Globalization.NumberStyles.Any, Globalization.CultureInfo.InvariantCulture, v) Then
            Return v
        End If
        Return 0
    End Function

End Class
