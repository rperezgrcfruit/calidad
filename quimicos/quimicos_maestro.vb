Imports MySql.Data.MySqlClient
Imports System.Configuration

Public Class quimicos_maestro
    Public cnx As New MySqlConnection(ConfigurationManager.ConnectionStrings("cnxstring").ToString())
    Public permiso As Boolean = False   ' para indicar si tiene permiso a escribir
    Private modoQuimico As Boolean = True   ' True = modo químicos, False = modo bodegas
    Private modoBodega As Boolean = False  ' True = modo bodegas, False = modo químicos

    Private Sub quimicos_maestro_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Me.KeyPreview = True
        cargarDataGrid()
        cbo_tipo.Items.Add("FUMIGANTE")
        cbo_tipo.Items.Add("FUNGICIDA")
        cbo_tipo.Items.Add("INSECTICIDA")
        cbo_tipo.Items.Add("HERBICIDA")
        cbo_tipo.Items.Add("FERTILIZANTE FOLIAR")
        cbo_tipo.Items.Add("DESVERTEDIZANTE")
        cbo_tipo.Items.Add("OTRO")

        cbo_unidad.Items.Add("LT")
        cbo_unidad.Items.Add("KG")
        cbo_unidad.Items.Add("GR")
        cbo_unidad.Items.Add("ML")
        cbo_unidad.Items.Add("UND")

        cbo_tipo.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        cbo_unidad.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList

        If permiso Then
            txt_codigo.Enabled = True
            txt_nombre.Enabled = True
            cbo_tipo.Enabled = True
            cbo_unidad.Enabled = True
            txt_sag.Enabled = True
            btn_guardar.Enabled = True
        End If
    End Sub

    ' ================================================================
    ' BÚSQUEDA POR CÓDIGO (Enter)
    ' ================================================================
    Private Sub txt_codigo_KeyDown(sender As Object, e As KeyEventArgs) Handles txt_codigo.KeyDown
        If e.KeyCode = Keys.Enter Then
            e.SuppressKeyPress = True

            If txt_codigo.Text.Trim() = "" Then
                MessageBox.Show("Ingrese un código para buscar", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Information)
                txt_codigo.SelectAll()
                Return
            End If

            Dim table As New DataTable()
            Try
                Dim sql As String
                Dim cmd As New MySqlCommand()

                If modoBodega Then
                    sql = "SELECT codigo, nombre FROM quimicos_bodegas WHERE codigo = ?cod"
                End If

                If modoQuimico Then
                    sql = "SELECT codigo, nombre, tipo, unidad_medida, registro_sag FROM quimicos WHERE codigo = ?cod"
                End If

                cnx.Open()
                cmd.Connection = cnx
                cmd.CommandText = sql
                cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())

                Dim adapter As New MySqlDataAdapter(cmd)
                adapter.Fill(table)
                cnx.Close()

                If table.Rows.Count > 0 Then
                    ' CODIGO EXISTE
                    MessageBox.Show("Registro encontrado", "Información", MessageBoxButtons.OK, MessageBoxIcon.Information)

                    txt_nombre.Text = table.Rows(0)("nombre").ToString()

                    If modoQuimico Then
                        cbo_tipo.Text = table.Rows(0)("tipo").ToString()
                        cbo_unidad.Text = table.Rows(0)("unidad_medida").ToString()
                        txt_sag.Text = table.Rows(0)("registro_sag").ToString()
                    End If

                    txt_codigo.Enabled = False
                    txt_nombre.Enabled = True
                    cbo_tipo.Enabled = True
                    cbo_unidad.Enabled = True
                    txt_sag.Enabled = True

                    btn_guardar.Enabled = False
                    btn_modificar.Enabled = permiso
                    btn_eliminar.Enabled = permiso
                Else
                    ' CODIGO NO EXISTE - SE PUEDE INGRESAR NUEVO
                    MessageBox.Show("Código no existe - Puede ingresar nuevo registro", "Información", MessageBoxButtons.OK, MessageBoxIcon.Information)

                    txt_nombre.Clear()
                    cbo_tipo.Text = ""
                    cbo_unidad.Text = ""
                    txt_sag.Clear()

                    txt_codigo.Enabled = False
                    txt_nombre.Enabled = True
                    cbo_tipo.Enabled = True
                    cbo_unidad.Enabled = True
                    txt_sag.Enabled = True

                    btn_guardar.Enabled = False
                    btn_modificar.Enabled = False
                    btn_eliminar.Enabled = False
                End If

                txt_nombre.Select()
            Catch ex As Exception
                MessageBox.Show("Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                cnx.Close()
            End Try
        End If
    End Sub

    ' ================================================================
    ' GUARDAR
    ' ================================================================
    Private Sub btn_guardar_Click(sender As Object, e As EventArgs) Handles btn_guardar.Click
        If txt_codigo.Text.Trim() = "" Then
            MessageBox.Show("Debe ingresar el código", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Information)
            txt_codigo.Focus()
            Return
        End If

        If txt_nombre.Text.Trim() = "" Then
            MessageBox.Show("Debe ingresar el nombre", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Information)
            txt_nombre.Focus()
            Return
        End If

        Dim respuesta = MessageBox.Show("¿Confirma el ingreso?", "Confirmar", MessageBoxButtons.YesNo, MessageBoxIcon.Question)
        If respuesta <> DialogResult.Yes Then Return

        Try
            Dim cmd As New MySqlCommand()
            cmd.Connection = cnx

            If modoQuimico Then
                cmd.CommandText = "INSERT INTO quimicos (codigo, nombre, tipo, unidad_medida, registro_sag, activo) VALUES (?cod, ?nom, ?tip, ?uni, ?sag, 1)"
                cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())
                cmd.Parameters.AddWithValue("?nom", txt_nombre.Text.Trim())
                cmd.Parameters.AddWithValue("?tip", cbo_tipo.Text)
                cmd.Parameters.AddWithValue("?uni", cbo_unidad.Text)
                cmd.Parameters.AddWithValue("?sag", txt_sag.Text.Trim())
            ElseIf modoBodega Then
                cmd.CommandText = "INSERT INTO quimicos_bodegas (codigo, nombre, activo) VALUES (?cod, ?nom, 1)"
                cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())
                cmd.Parameters.AddWithValue("?nom", txt_nombre.Text.Trim())
            End If

            cnx.Open()
            cmd.ExecuteNonQuery()
            cnx.Close()

            MessageBox.Show("Registro guardado correctamente", "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information)

            limpiar()
            cargarDataGrid()
        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            cnx.Close()
        End Try
    End Sub

    ' ================================================================
    ' MODIFICAR
    ' ================================================================
    Private Sub btn_modificar_Click(sender As Object, e As EventArgs) Handles btn_modificar.Click
        If txt_codigo.Text.Trim() = "" OrElse txt_nombre.Text.Trim() = "" Then
            MessageBox.Show("Debe completar todos los campos", "Atención", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Return
        End If

        Dim respuesta = MessageBox.Show("¿Confirma la modificación?", "Confirmar", MessageBoxButtons.YesNo, MessageBoxIcon.Question)
        If respuesta <> DialogResult.Yes Then Return

        Try
            Dim cmd As New MySqlCommand()
            cmd.Connection = cnx

            If modoQuimico Then
                cmd.CommandText = "UPDATE quimicos SET nombre = ?nom, tipo = ?tip, unidad_medida = ?uni, registro_sag = ?sag WHERE codigo = ?cod"
                cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())
                cmd.Parameters.AddWithValue("?nom", txt_nombre.Text.Trim())
                cmd.Parameters.AddWithValue("?tip", cbo_tipo.Text)
                cmd.Parameters.AddWithValue("?uni", cbo_unidad.Text)
                cmd.Parameters.AddWithValue("?sag", txt_sag.Text.Trim())
            ElseIf modoBodega Then
                cmd.CommandText = "UPDATE quimicos_bodegas SET nombre = ?nom WHERE codigo = ?cod"
                cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())
                cmd.Parameters.AddWithValue("?nom", txt_nombre.Text.Trim())
            End If

            cnx.Open()
            Dim rows = cmd.ExecuteNonQuery()
            cnx.Close()

            If rows > 0 Then
                MessageBox.Show("Modificación realizada", "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information)
                limpiar()
                cargarDataGrid()
            Else
                MessageBox.Show("No se encontró el registro", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End If
        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            cnx.Close()
        End Try
    End Sub

    ' ================================================================
    ' ELIMINAR
    ' ================================================================
    Private Sub btn_eliminar_Click(sender As Object, e As EventArgs) Handles btn_eliminar.Click
        Dim respuesta = MessageBox.Show("¿Confirma la eliminación del registro?", "Confirmar", MessageBoxButtons.YesNo, MessageBoxIcon.Warning)
        If respuesta <> DialogResult.Yes Then Return

        Try
            Dim cmd As New MySqlCommand()
            cmd.Connection = cnx

            If modoQuimico Then
                cmd.CommandText = "UPDATE quimicos SET activo = 0 WHERE codigo = ?cod"
                cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())
            ElseIf modoBodega Then
                cmd.CommandText = "DELETE FROM quimicos_bodegas WHERE codigo = ?cod"
                cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())
            End If

            cnx.Open()
            Dim rows = cmd.ExecuteNonQuery()
            cnx.Close()

            If rows > 0 Then
                MessageBox.Show("Eliminación realizada", "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information)
                limpiar()
                cargarDataGrid()
            Else
                MessageBox.Show("No se encontró el registro", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End If
        Catch ex As Exception
            MessageBox.Show("Error: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            cnx.Close()
        End Try
    End Sub

    ' ===============================================================
    ' LIMPIAR
    ' ===============================================================
    Private Sub limpiar()
        txt_codigo.Clear()
        txt_nombre.Clear()
        cbo_tipo.Text = ""
        cbo_unidad.Text = ""
        txt_sag.Clear()

        txt_codigo.Enabled = True
        txt_nombre.Enabled = False
        cbo_tipo.Enabled = False
        cbo_unidad.Enabled = False
        txt_sag.Enabled = False

        btn_guardar.Enabled = False
        btn_modificar.Enabled = False
        btn_eliminar.Enabled = False

        txt_codigo.Select()

        cargarDataGrid()
    End Sub

    ' ===============================================================
    ' CARGAR DATAGRID
    ' ===============================================================
    Private Sub cargarDataGrid()
        Try
            Dim sql As String
            If modoQuimico Then
                sql = "SELECT codigo, nombre, tipo, unidad_medida, registro_sag, activo FROM quimicos WHERE activo = 1 ORDER BY codigo"
            Else
                sql = "SELECT codigo, nombre, activo FROM quimicos_bodegas WHERE activo = 1 ORDER BY codigo"
            End If

            Dim dt As New DataTable()
            Dim cmd As New MySqlCommand(sql, cnx)
            cnx.Open()
            Dim adapter As New MySqlDataAdapter(cmd)
            adapter.Fill(dt)
            cnx.Close()
            dg_datos.DataSource = dt
        Catch ex As Exception
            MessageBox.Show("Error al cargar datos: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            cnx.Close()
        End Try
    End Sub

    ' ================================================================
    ' LIMPIAR (BOTÓN LIMPIAR)
    ' ================================================================
    Private Sub btn_limpiar_Click(sender As Object, e As EventArgs) Handles btn_limpiar.Click
        limpiar()
    End Sub

    ' ================================================================
    ' QUIMICOS (RadioButton)
    ' ================================================================
    Private Sub rb_quimicos_CheckedChanged(sender As Object, e As EventArgs) Handles rb_quimicos.CheckedChanged
        If rb_quimicos.Checked Then
            modoQuimico = True
            modoBodega = False

            lbl_titulo.Text = "MANTENEDOR DE QUIMICOS"

            ' --- Asegurar visibilidad de controles de químico ---
            txt_sag.Visible = True
            Label4.Visible = True
            cbo_tipo.Visible = True
            cbo_unidad.Visible = True
            Label3.Visible = True
            Label5.Visible = True

            txt_codigo.Clear()
            txt_nombre.Clear()
            cbo_tipo.Text = ""
            cbo_unidad.Text = ""
            txt_sag.Clear()

            txt_codigo.Enabled = True
            txt_nombre.Enabled = False
            cbo_tipo.Enabled = False
            cbo_unidad.Enabled = False
            txt_sag.Enabled = False

            btn_guardar.Enabled = False
            btn_modificar.Enabled = False
            btn_eliminar.Enabled = False

            cargarDataGrid()
            txt_codigo.Select()
        End If
    End Sub

    ' ================================================================
    ' BODEGAS (RadioButton)
    ' ================================================================
    Private Sub rb_bodegas_CheckedChanged(sender As Object, e As EventArgs) Handles rb_bodegas.CheckedChanged
        If rb_bodegas.Checked Then
            modoBodega = True
            modoQuimico = False

            lbl_titulo.Text = "MANTENEDOR DE BODEGAS DE QUIMICOS"

            ' --- Ocultar controles exclusivos de químico ---
            txt_sag.Visible = False
            Label4.Visible = False
            cbo_tipo.Visible = False
            cbo_unidad.Visible = False
            Label3.Visible = False
            Label5.Visible = False

            ' --- Mostrar controles base ---
            txt_codigo.Visible = True
            txt_nombre.Visible = True
            Label1.Visible = True
            Label2.Visible = True

            txt_codigo.Clear()
            txt_nombre.Clear()
            cbo_tipo.Text = ""
            cbo_unidad.Text = ""
            txt_sag.Clear()

            txt_codigo.Enabled = True
            txt_nombre.Enabled = False

            btn_guardar.Enabled = False
            btn_modificar.Enabled = False
            btn_eliminar.Enabled = False

            cargarDataGrid()
            txt_codigo.Select()
        End If
    End Sub

    Private Sub txt_codigo_TextChanged(sender As Object, e As EventArgs) Handles txt_codigo.TextChanged

    End Sub

    Private Sub txt_nombre_TextChanged(sender As Object, e As EventArgs) Handles txt_nombre.TextChanged
        If txt_nombre.Text.Trim() <> "" AndAlso Not txt_codigo.Enabled Then
            btn_guardar.Enabled = True
        End If
    End Sub

End Class
