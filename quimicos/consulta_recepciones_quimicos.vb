Imports MySql.Data.MySqlClient
Imports System.Configuration
Imports System.Windows.Forms

Public Class consulta_recepciones_quimicos
    Public cnx As New MySqlConnection(ConfigurationManager.ConnectionStrings("cnxstring").ToString())

    Private Sub consulta_recepciones_quimicos_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        ' Cargar combos
        cargarBodegas()
        cargarProveedores()

        ' Fechas por defecto: primer día del mes actual hasta hoy
        txt_desde.Text = New DateTime(DateTime.Now.Year, DateTime.Now.Month, 1).ToString("dd/MM/yyyy")
        txt_hasta.Text = DateTime.Now.ToString("dd/MM/yyyy")

        ' Cargar recepciones
        cargarRecepciones()
    End Sub

    ' ============================================================
    ' CARGAR BODEGAS (incluye "TODAS")
    ' ============================================================
    Private Sub cargarBodegas()
        Try
            cnx.Open()
            Dim da As New MySqlDataAdapter("SELECT codigo, nombre FROM quimicos_bodegas WHERE activo = 1 ORDER BY codigo", cnx)
            Dim dt As New DataTable
            da.Fill(dt)
            cnx.Close()

            ' Insertar "TODAS" al inicio
            Dim rowTodas = dt.NewRow()
            rowTodas("codigo") = ""
            rowTodas("nombre") = "TODAS"
            dt.Rows.InsertAt(rowTodas, 0)

            cbo_bodega.DataSource = dt
            cbo_bodega.DisplayMember = "nombre"
            cbo_bodega.ValueMember = "codigo"
            cbo_bodega.SelectedIndex = 0
        Catch ex As Exception
            MessageBox.Show("Error al cargar bodegas: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If cnx.State = ConnectionState.Open Then cnx.Close()
        End Try
    End Sub

    ' ============================================================
    ' CARGAR PROVEEDORES (incluye "TODOS")
    ' ============================================================
    Private Sub cargarProveedores()
        Try
            cnx.Open()
            Dim da As New MySqlDataAdapter("SELECT id, nombre FROM quimicos_proveedores WHERE activo = 1 ORDER BY nombre", cnx)
            Dim dt As New DataTable
            da.Fill(dt)
            cnx.Close()

            Dim rowTodos = dt.NewRow()
            rowTodos("id") = 0
            rowTodos("nombre") = "TODOS"
            dt.Rows.InsertAt(rowTodos, 0)

            rowTodos = dt.NewRow()
            rowTodos("id") = -1
            rowTodos("nombre") = "(LIBRE - texto libre)"
            dt.Rows.InsertAt(rowTodos, 1)

            cbo_proveedor.DataSource = dt
            cbo_proveedor.DisplayMember = "nombre"
            cbo_proveedor.ValueMember = "id"
            cbo_proveedor.SelectedIndex = 0
        Catch ex As Exception
            MessageBox.Show("Error al cargar proveedores: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If cnx.State = ConnectionState.Open Then cnx.Close()
        End Try
    End Sub

    ' ============================================================
    ' FILTRAR Y CARGAR RECEPCIONES
    ' ============================================================
    Private Sub cargarRecepciones()
        Try
            ' Parsear fechas
            Dim fechaDesde As Date
            Dim fechaHasta As Date
            If Not Date.TryParseExact(txt_desde.Text, "dd/MM/yyyy", Nothing, Globalization.DateTimeStyles.None, fechaDesde) Then
                MessageBox.Show("Fecha 'desde' inválida. Use formato dd/MM/yyyy", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return
            End If
            If Not Date.TryParseExact(txt_hasta.Text, "dd/MM/yyyy", Nothing, Globalization.DateTimeStyles.None, fechaHasta) Then
                MessageBox.Show("Fecha 'hasta' inválida. Use formato dd/MM/yyyy", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return
            End If
            ' Asegurar que hasta cubra todo el día
            fechaHasta = fechaHasta.AddDays(1).AddSeconds(-1)

            Dim bodegaFiltro As String = If(cbo_bodega.SelectedIndex >= 0, cbo_bodega.SelectedValue.ToString(), "")
            Dim idProveedor As Integer = CInt(cbo_proveedor.SelectedValue)
            Dim nombreProveedorFiltro As String = ""
            If idProveedor = -1 Then
                nombreProveedorFiltro = "(LIBRE - texto libre)"
            ElseIf idProveedor > 0 Then
                nombreProveedorFiltro = cbo_proveedor.Text
            End If

            Dim sql As String =
                "SELECT " &
                "  r.n_recepcion AS ""#Recepción"", " &
                "  r.fecha AS ""Fecha"", " &
                "  COALESCE(b.nombre, r.bodega) AS ""Bodega"", " &
                "  r.proveedor AS ""Proveedor"", " &
                "  r.guia AS ""GUIA"", " &
                "  IFNULL(r.factura,'') AS ""Factura"", " &
                "  IFNULL(r.lote_proveedor,'') AS ""Lote Prov."", " &
                "  (SELECT COUNT(*) FROM quimicos_recepcion_detalle d WHERE d.n_recepcion = r.n_recepcion) AS ""#Items"", " &
                "  IFNULL((SELECT SUM(cantidad_total) FROM quimicos_recepcion_detalle d WHERE d.n_recepcion = r.n_recepcion), 0) AS ""Cantidad Total"", " &
                "  IFNULL(r.observaciones,'') AS ""Observaciones"" " &
                "FROM quimicos_recepcion r " &
                "LEFT JOIN quimicos_bodegas b ON b.codigo = r.bodega " &
                "WHERE r.fecha BETWEEN ?desde AND ?hasta "

            Dim sqlFiltros As String = ""
            If Not String.IsNullOrEmpty(bodegaFiltro) Then
                sqlFiltros &= " AND r.bodega = ?bodega "
            End If
            If idProveedor = -1 Then
                sqlFiltros &= " AND r.proveedor NOT IN (SELECT nombre FROM quimicos_proveedores WHERE activo = 1) "
            ElseIf idProveedor > 0 Then
                sqlFiltros &= " AND r.proveedor = ?prov "
            End If

            Dim sqlFinal = sql & sqlFiltros & " ORDER BY r.fecha DESC, r.n_recepcion DESC"

            cnx.Open()
            Dim da As New MySqlDataAdapter(sqlFinal, cnx)
            da.SelectCommand.Parameters.AddWithValue("?desde", fechaDesde.ToString("yyyy-MM-dd"))
            da.SelectCommand.Parameters.AddWithValue("?hasta", fechaHasta.ToString("yyyy-MM-dd"))
            If Not String.IsNullOrEmpty(bodegaFiltro) Then
                da.SelectCommand.Parameters.AddWithValue("?bodega", bodegaFiltro)
            End If
            If idProveedor > 0 Then
                da.SelectCommand.Parameters.AddWithValue("?prov", nombreProveedorFiltro)
            End If

            Dim dt As New DataTable
            da.Fill(dt)
            cnx.Close()

            dg_recepciones.AutoGenerateColumns = True
            dg_recepciones.DataSource = dt

            ' Personalizar columnas
            If dg_recepciones.Columns.Contains("#Recepción") Then
                dg_recepciones.Columns("#Recepción").HeaderText = "# Recepción"
                dg_recepciones.Columns("#Recepción").Width = 80
            End If
            If dg_recepciones.Columns.Contains("Fecha") Then
                dg_recepciones.Columns("Fecha").DefaultCellStyle.Format = "dd/MM/yyyy"
                dg_recepciones.Columns("Fecha").Width = 100
            End If

            ' Resumen
            Dim totalItems As Integer = 0
            Dim totalCantidad As Decimal = 0
            For Each row As DataRow In dt.Rows
                totalItems += CInt(row("#Items"))
                totalCantidad += CDec(row("Cantidad Total"))
            Next
            lbl_resumen.Text = "Total recepciones: " & dt.Rows.Count & "   |   Total ítems: " & totalItems & "   |   Cantidad total recibida: " & totalCantidad.ToString("N2")

        Catch ex As Exception
            MessageBox.Show("Error al cargar recepciones: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If cnx.State = ConnectionState.Open Then cnx.Close()
        End Try
    End Sub

    ' ============================================================
    ' EVENTOS DE BOTONES
    ' ============================================================
    Private Sub btn_filtrar_Click(sender As Object, e As EventArgs) Handles btn_filtrar.Click
        cargarRecepciones()
    End Sub

    Private Sub btn_limpiar_filtros_Click(sender As Object, e As EventArgs) Handles btn_limpiar_filtros.Click
        cbo_bodega.SelectedIndex = 0
        cbo_proveedor.SelectedIndex = 0
        txt_desde.Text = New DateTime(DateTime.Now.Year, DateTime.Now.Month, 1).ToString("dd/MM/yyyy")
        txt_hasta.Text = DateTime.Now.ToString("dd/MM/yyyy")
        cargarRecepciones()
    End Sub

    Private Sub btn_actualizar_Click(sender As Object, e As EventArgs) Handles btn_actualizar.Click
        cargarRecepciones()
    End Sub

    ' Doble click en una fila abre el detalle
    Private Sub dg_recepciones_DoubleClick(sender As Object, e As EventArgs) Handles dg_recepciones.DoubleClick
        verDetalle()
    End Sub

    Private Sub btn_ver_detalle_Click(sender As Object, e As EventArgs) Handles btn_ver_detalle.Click
        verDetalle()
    End Sub

    Private Sub verDetalle()
        If dg_recepciones.SelectedRows.Count = 0 Then
            MessageBox.Show("Seleccione una recepción de la lista", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Return
        End If
        Dim nRecep As Integer = CInt(dg_recepciones.SelectedRows(0).Cells("#Recepción").Value)
        Dim f As New consulta_recepciones_quimicos_detalle(nRecep)
        f.ShowDialog()
    End Sub

    Private Sub btn_cerrar_Click(sender As Object, e As EventArgs) Handles btn_cerrar.Click
        Me.Close()
    End Sub

    ' ============================================================
    ' EXPORTAR A EXCEL (2 hojas: Recepciones + Detalle)
    ' ============================================================
    Private Sub btn_exportar_Click(sender As Object, e As EventArgs) Handles btn_exportar.Click
        If dg_recepciones.Rows.Count = 0 Then
            MessageBox.Show("No hay datos para exportar", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Return
        End If

        Dim sfd As New SaveFileDialog()
        sfd.Filter = "Excel files (*.xlsx)|*.xlsx"
        sfd.FileName = "Recepciones_Quimicos_" & DateTime.Now.ToString("yyyyMMdd_HHmmss") & ".xlsx"
        If sfd.ShowDialog() <> DialogResult.OK Then Return

        Try
            Using ep As New OfficeOpenXml.ExcelPackage()
                ' HOJA 1: Recepciones (cabeceras)
                Dim ws1 As OfficeOpenXml.ExcelWorksheet = ep.Workbook.Worksheets.Add("Recepciones")
                For i As Integer = 0 To dg_recepciones.Columns.Count - 1
                    ws1.Cells(1, i + 1).Value = dg_recepciones.Columns(i).HeaderText
                    ws1.Cells(1, i + 1).Style.Font.Bold = True
                    ws1.Cells(1, i + 1).Style.Fill.PatternType = OfficeOpenXml.Style.ExcelFillStyle.Solid
                    ws1.Cells(1, i + 1).Style.Fill.BackgroundColor.SetColor(System.Drawing.Color.FromArgb(40, 80, 140))
                    ws1.Cells(1, i + 1).Style.Font.Color.SetColor(System.Drawing.Color.White)
                Next

                For r As Integer = 0 To dg_recepciones.Rows.Count - 1
                    For c As Integer = 0 To dg_recepciones.Columns.Count - 1
                        Dim val = dg_recepciones.Rows(r).Cells(c).Value
                        ws1.Cells(r + 2, c + 1).Value = If(val IsNot Nothing, val.ToString(), "")
                    Next
                Next
                ws1.Cells.AutoFitColumns()

                ' HOJA 2: Detalle (todas las líneas de las recepciones filtradas)
                Dim ws2 As OfficeOpenXml.ExcelWorksheet = ep.Workbook.Worksheets.Add("Detalle")
                Dim headersDet As String() = {"# Recepción", "Fecha", "Bodega", "Proveedor", "GUIA", "Código", "Nombre", "Tipo", "Unidad Cat.", "Reg SAG", "Cant. Envases", "Contenido/Envase", "Cantidad Total", "Unidad"}
                For i As Integer = 0 To headersDet.Length - 1
                    ws2.Cells(1, i + 1).Value = headersDet(i)
                    ws2.Cells(1, i + 1).Style.Font.Bold = True
                    ws2.Cells(1, i + 1).Style.Fill.PatternType = OfficeOpenXml.Style.ExcelFillStyle.Solid
                    ws2.Cells(1, i + 1).Style.Fill.BackgroundColor.SetColor(System.Drawing.Color.FromArgb(40, 80, 140))
                    ws2.Cells(1, i + 1).Style.Font.Color.SetColor(System.Drawing.Color.White)
                Next

                ' Generar WHERE para el detalle: usar mismos filtros que el listado
                Dim filtrosDet As String = generarFiltrosSQL()
                Dim sqlDet As String =
                    "SELECT r.n_recepcion, r.fecha, COALESCE(b.nombre, r.bodega) AS bodega, r.proveedor, r.guia, " &
                    "       d.quimico_codigo, q.nombre, q.tipo, q.unidad_medida, q.registro_sag, " &
                    "       d.cant_envases, d.contenido_envase, d.cantidad_total, d.unidad " &
                    "FROM quimicos_recepcion r " &
                    "INNER JOIN quimicos_recepcion_detalle d ON d.n_recepcion = r.n_recepcion " &
                    "LEFT JOIN quimicos q ON q.codigo = d.quimico_codigo " &
                    "LEFT JOIN quimicos_bodegas b ON b.codigo = r.bodega " &
                    filtrosDet &
                    " ORDER BY r.fecha DESC, r.n_recepcion DESC, d.quimico_codigo"

                Dim rowIdx As Integer = 2
                cnx.Open()
                Dim cmd As New MySqlCommand(sqlDet, cnx)
                Dim da As New MySqlDataAdapter(cmd)
                Dim paramsList As New List(Of MySqlParameter)
                ' Reutilizar mismos parámetros del listado
                Dim fechaDesde As Date
                Dim fechaHasta As Date
                If Date.TryParseExact(txt_desde.Text, "dd/MM/yyyy", Nothing, Globalization.DateTimeStyles.None, fechaDesde) AndAlso
                   Date.TryParseExact(txt_hasta.Text, "dd/MM/yyyy", Nothing, Globalization.DateTimeStyles.None, fechaHasta) Then
                    fechaHasta = fechaHasta.AddDays(1).AddSeconds(-1)
                    cmd.Parameters.AddWithValue("?desde", fechaDesde.ToString("yyyy-MM-dd"))
                    cmd.Parameters.AddWithValue("?hasta", fechaHasta.ToString("yyyy-MM-dd"))
                    Dim bodegaFiltro As String = If(cbo_bodega.SelectedIndex >= 0, cbo_bodega.SelectedValue.ToString(), "")
                    Dim idProveedor As Integer = CInt(cbo_proveedor.SelectedValue)
                    If Not String.IsNullOrEmpty(bodegaFiltro) Then
                        cmd.Parameters.AddWithValue("?bodega", bodegaFiltro)
                    End If
                    If idProveedor = -1 Then
                        ' LIBRE: el filtro ya está en el WHERE
                    ElseIf idProveedor > 0 Then
                        cmd.Parameters.AddWithValue("?prov", cbo_proveedor.Text)
                    End If
                End If

                Dim rd = cmd.ExecuteReader()
                While rd.Read()
                    ws2.Cells(rowIdx, 1).Value = rd("n_recepcion").ToString()
                    ws2.Cells(rowIdx, 2).Value = CDate(rd("fecha")).ToString("dd/MM/yyyy")
                    ws2.Cells(rowIdx, 3).Value = rd("bodega").ToString()
                    ws2.Cells(rowIdx, 4).Value = rd("proveedor").ToString()
                    ws2.Cells(rowIdx, 5).Value = rd("guia").ToString()
                    ws2.Cells(rowIdx, 6).Value = rd("quimico_codigo").ToString()
                    ws2.Cells(rowIdx, 7).Value = rd("nombre").ToString()
                    ws2.Cells(rowIdx, 8).Value = If(rd("tipo") IsNot DBNull.Value, rd("tipo").ToString(), "")
                    ws2.Cells(rowIdx, 9).Value = If(rd("unidad_medida") IsNot DBNull.Value, rd("unidad_medida").ToString(), "")
                    ws2.Cells(rowIdx, 10).Value = If(rd("registro_sag") IsNot DBNull.Value, rd("registro_sag").ToString(), "")
                    ws2.Cells(rowIdx, 11).Value = Convert.ToDecimal(rd("cant_envases"))
                    ws2.Cells(rowIdx, 12).Value = Convert.ToDecimal(rd("contenido_envase"))
                    ws2.Cells(rowIdx, 13).Value = Convert.ToDecimal(rd("cantidad_total"))
                    ws2.Cells(rowIdx, 14).Value = If(rd("unidad") IsNot DBNull.Value, rd("unidad").ToString(), "")
                    rowIdx += 1
                End While
                cnx.Close()
                ws2.Cells.AutoFitColumns()

                Dim fi As New IO.FileInfo(sfd.FileName)
                ep.SaveAs(fi)
                MessageBox.Show("Exportado a " & sfd.FileName & vbCrLf & vbCrLf &
                                "Hoja 1: Recepciones (cabeceras)" & vbCrLf &
                                "Hoja 2: Detalle (" & (rowIdx - 2) & " líneas)", "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information)
            End Using
        Catch ex As Exception
            MessageBox.Show("Error al exportar: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If cnx.State = ConnectionState.Open Then cnx.Close()
        End Try
    End Sub

    ' Genera el mismo fragmento WHERE que cargarRecepciones() para reutilizarlo en el export
    Private Function generarFiltrosSQL() As String
        Dim fechaDesde As Date
        Dim fechaHasta As Date
        If Not Date.TryParseExact(txt_desde.Text, "dd/MM/yyyy", Nothing, Globalization.DateTimeStyles.None, fechaDesde) Then Return ""
        If Not Date.TryParseExact(txt_hasta.Text, "dd/MM/yyyy", Nothing, Globalization.DateTimeStyles.None, fechaHasta) Then Return ""
        fechaHasta = fechaHasta.AddDays(1).AddSeconds(-1)

        Dim bodegaFiltro As String = If(cbo_bodega.SelectedIndex >= 0, cbo_bodega.SelectedValue.ToString(), "")
        Dim idProveedor As Integer = CInt(cbo_proveedor.SelectedValue)

        Dim sql As String = " WHERE r.fecha BETWEEN ?desde AND ?hasta "
        If Not String.IsNullOrEmpty(bodegaFiltro) Then sql &= " AND r.bodega = ?bodega "
        If idProveedor = -1 Then
            sql &= " AND r.proveedor NOT IN (SELECT nombre FROM quimicos_proveedores WHERE activo = 1) "
        ElseIf idProveedor > 0 Then
            sql &= " AND r.proveedor = ?prov "
        End If
        Return sql
    End Function
End Class
