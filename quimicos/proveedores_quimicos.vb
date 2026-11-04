Imports MySql.Data.MySqlClient
Imports System.Configuration

Public Class proveedores_quimicos
    Public cnx As New MySqlConnection(ConfigurationManager.ConnectionStrings("cnxstring").ToString())
    Public permiso As Boolean = False   ' para indicar si tiene permiso a escribir

    Private Sub proveedores_quimicos_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Me.KeyPreview = True
        cargarDataGrid()
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
                Dim sql As String = "SELECT id, codigo, nombre, contacto, telefono, email, direccion FROM quimicos_proveedores WHERE codigo = ?cod AND activo = 1"
                Dim cmd As New MySqlCommand(sql, cnx)
                cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())

                cnx.Open()
                Dim adapter As New MySqlDataAdapter(cmd)
                adapter.Fill(table)
                cnx.Close()

                If table.Rows.Count > 0 Then
                    ' CODIGO EXISTE - CARGAR DATOS
                    Dim row = table.Rows(0)

                    txt_nombre.Text = row("nombre").ToString()
                    txt_contacto.Text = row("contacto").ToString()
                    txt_telefono.Text = row("telefono").ToString()
                    txt_email.Text = row("email").ToString()
                    txt_direccion.Text = row("direccion").ToString()

                    txt_codigo.Enabled = False
                    txt_nombre.Enabled = True
                    txt_contacto.Enabled = True
                    txt_telefono.Enabled = True
                    txt_email.Enabled = True
                    txt_direccion.Enabled = True

                    btn_guardar.Enabled = False
                    btn_modificar.Enabled = permiso
                    btn_eliminar.Enabled = permiso
                Else
                    ' CODIGO NO EXISTE - SE PUEDE INGRESAR NUEVO
                    MessageBox.Show("Código no existe - Puede ingresar nuevo registro", "Información", MessageBoxButtons.OK, MessageBoxIcon.Information)

                    txt_nombre.Clear()
                    txt_contacto.Clear()
                    txt_telefono.Clear()
                    txt_email.Clear()
                    txt_direccion.Clear()

                    txt_codigo.Enabled = False
                    txt_nombre.Enabled = True
                    txt_contacto.Enabled = True
                    txt_telefono.Enabled = True
                    txt_email.Enabled = True
                    txt_direccion.Enabled = True

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
            cmd.CommandText = "INSERT INTO quimicos_proveedores (codigo, nombre, contacto, telefono, email, direccion, activo) VALUES (?cod, ?nom, ?con, ?tel, ?email, ?dir, 1)"
            cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())
            cmd.Parameters.AddWithValue("?nom", txt_nombre.Text.Trim())
            cmd.Parameters.AddWithValue("?con", txt_contacto.Text.Trim())
            cmd.Parameters.AddWithValue("?tel", txt_telefono.Text.Trim())
            cmd.Parameters.AddWithValue("?email", txt_email.Text.Trim())
            cmd.Parameters.AddWithValue("?dir", txt_direccion.Text.Trim())

            cnx.Open()
            cmd.ExecuteNonQuery()
            cnx.Close()

            MessageBox.Show("Proveedor guardado correctamente", "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information)

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
            cmd.CommandText = "UPDATE quimicos_proveedores SET nombre = ?nom, contacto = ?con, telefono = ?tel, email = ?email, direccion = ?dir WHERE codigo = ?cod AND activo = 1"
            cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())
            cmd.Parameters.AddWithValue("?nom", txt_nombre.Text.Trim())
            cmd.Parameters.AddWithValue("?con", txt_contacto.Text.Trim())
            cmd.Parameters.AddWithValue("?tel", txt_telefono.Text.Trim())
            cmd.Parameters.AddWithValue("?email", txt_email.Text.Trim())
            cmd.Parameters.AddWithValue("?dir", txt_direccion.Text.Trim())

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
        Dim respuesta = MessageBox.Show("¿Confirma la eliminación del proveedor?", "Confirmar", MessageBoxButtons.YesNo, MessageBoxIcon.Warning)
        If respuesta <> DialogResult.Yes Then Return

        Try
            Dim cmd As New MySqlCommand()
            cmd.Connection = cnx
            cmd.CommandText = "DELETE FROM quimicos_proveedores WHERE codigo = ?cod"
            cmd.Parameters.AddWithValue("?cod", txt_codigo.Text.Trim().ToUpper())

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

    ' ================================================================
    ' LIMPIAR
    ' ================================================================
    Private Sub limpiar()
        txt_codigo.Clear()
        txt_nombre.Clear()
        txt_contacto.Clear()
        txt_telefono.Clear()
        txt_email.Clear()
        txt_direccion.Clear()

        txt_codigo.Enabled = True
        txt_nombre.Enabled = False
        txt_contacto.Enabled = False
        txt_telefono.Enabled = False
        txt_email.Enabled = False
        txt_direccion.Enabled = False

        btn_guardar.Enabled = False
        btn_modificar.Enabled = False
        btn_eliminar.Enabled = False

        txt_codigo.Select()
        cargarDataGrid()
    End Sub

    ' ================================================================
    ' CARGAR DATAGRID
    ' ================================================================
    Private Sub cargarDataGrid()
        Try
            Dim sql As String = "SELECT codigo, nombre, contacto, telefono, email, direccion FROM quimicos_proveedores WHERE activo = 1 ORDER BY nombre"
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
    ' BOTÓN LIMPIAR (NUEVO)
    ' ================================================================
    Private Sub btn_limpiar_Click(sender As Object, e As EventArgs) Handles btn_limpiar.Click
        limpiar()
    End Sub

    ' ================================================================
    ' HABILITAR GUARDAR CUANDO HAY NOMBRE
    ' ================================================================
    Private Sub txt_nombre_TextChanged(sender As Object, e As EventArgs) Handles txt_nombre.TextChanged
        If txt_nombre.Text.Trim() <> "" AndAlso Not txt_codigo.Enabled Then
            btn_guardar.Enabled = True
        End If
    End Sub
End Class
