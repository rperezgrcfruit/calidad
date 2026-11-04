Imports MySql.Data.MySqlClient
Imports System.Configuration
Imports System.Net
Imports System.IO

Public Class consulta_recepciones_quimicos_detalle
    Public cnx As New MySqlConnection(ConfigurationManager.ConnectionStrings("cnxstring").ToString())
    Private _nRecepcion As Integer

    Public Sub New(nRecepcion As Integer)
        InitializeComponent()
        _nRecepcion = nRecepcion
    End Sub

    Private Sub consulta_recepciones_quimicos_detalle_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        cargarCabecera()
        cargarDetalle()
    End Sub

    ' ============================================================
    ' CARGAR CABECERA (datos generales)
    ' ============================================================
    Private Sub cargarCabecera()
        Try
            Dim sql As String =
                "SELECT r.fecha, COALESCE(b.nombre, r.bodega) AS bodega_nombre, r.proveedor, " &
                "       r.guia, IFNULL(r.factura,'(sin factura)') AS factura, IFNULL(r.lote_proveedor,'(sin lote)') AS lote, " &
                "       IFNULL(r.observaciones,'(sin observaciones)') AS obs " &
                "FROM quimicos_recepcion r " &
                "LEFT JOIN quimicos_bodegas b ON b.codigo = r.bodega " &
                "WHERE r.n_recepcion = ?n"
            cnx.Open()
            Dim cmd As New MySqlCommand(sql, cnx)
            cmd.Parameters.AddWithValue("?n", _nRecepcion)
            Dim rd = cmd.ExecuteReader()
            If rd.Read() Then
                lbl_cabecera.Text =
                    "Recepción #" & _nRecepcion & Environment.NewLine &
                    "Fecha: " & CDate(rd("fecha")).ToString("dd/MM/yyyy") & "    |    Bodega: " & rd("bodega_nombre").ToString() & "    |    Proveedor: " & rd("proveedor").ToString() & Environment.NewLine &
                    "GUIA: " & rd("guia").ToString() & "    |    Factura: " & rd("factura").ToString() & "    |    Lote Prov.: " & rd("lote").ToString() & Environment.NewLine &
                    "Obs: " & rd("obs").ToString()
            End If
            cnx.Close()
        Catch ex As Exception
            MessageBox.Show("Error al cargar cabecera: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If cnx.State = ConnectionState.Open Then cnx.Close()
        End Try
    End Sub

    ' ============================================================
    ' CARGAR DETALLE (químicos de la recepción)
    ' ============================================================
    Private Sub cargarDetalle()
        Try
            Dim sql As String =
                "SELECT d.quimico_codigo AS ""Código"", " &
                "       q.nombre AS ""Nombre"", " &
                "       q.tipo AS ""Tipo"", " &
                "       q.unidad_medida AS ""Unidad Cat."", " &
                "       q.registro_sag AS ""Reg SAG"", " &
                "       d.cant_envases AS ""Cant. Envases"", " &
                "       d.contenido_envase AS ""Cont./Env."", " &
                "       d.cantidad_total AS ""Cantidad Total"", " &
                "       d.unidad AS ""Unidad""" &
                "FROM quimicos_recepcion_detalle d " &
                "LEFT JOIN quimicos q ON q.codigo = d.quimico_codigo " &
                "WHERE d.n_recepcion = ?n " &
                "ORDER BY d.quimico_codigo"

            cnx.Open()
            Dim da As New MySqlDataAdapter(sql, cnx)
            da.SelectCommand.Parameters.AddWithValue("?n", _nRecepcion)
            Dim dt As New DataTable
            da.Fill(dt)
            cnx.Close()

            dg_detalle.AutoGenerateColumns = True
            dg_detalle.DataSource = dt

            ' Formato de columnas numéricas
            If dg_detalle.Columns.Contains("Cant. Envases") Then
                dg_detalle.Columns("Cant. Envases").DefaultCellStyle.Format = "N2"
                dg_detalle.Columns("Cant. Envases").DefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleRight
            End If
            If dg_detalle.Columns.Contains("Cont./Env.") Then
                dg_detalle.Columns("Cont./Env.").DefaultCellStyle.Format = "N2"
                dg_detalle.Columns("Cont./Env.").DefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleRight
            End If
            If dg_detalle.Columns.Contains("Cantidad Total") Then
                dg_detalle.Columns("Cantidad Total").DefaultCellStyle.Format = "N2"
                dg_detalle.Columns("Cantidad Total").DefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleRight
            End If

            Me.Text = "Detalle Recepción #" & _nRecepcion
        Catch ex As Exception
            MessageBox.Show("Error al cargar detalle: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
            If cnx.State = ConnectionState.Open Then cnx.Close()
        End Try
    End Sub

    Private Sub btn_cerrar_Click(sender As Object, e As EventArgs) Handles btn_cerrar.Click
        Me.Close()
    End Sub

    ' ============================================================
    ' SUBIR IMAGEN DE GUIA (PDF) AL SERVIDOR
    ' Patrón idéntico a recepcion_granel.vb - btnSubirDoc_Click
    ' Sube vía HTTP POST al servidor app.grcfruit.cl
    ' ============================================================
    Private Sub btn_subir_guia_Click(sender As Object, e As EventArgs) Handles btn_subir_guia.Click
        Try
            Using openFileDialog As New OpenFileDialog()
                openFileDialog.Title = "Seleccionar PDF de la GUIA"
                openFileDialog.Filter = "Archivos PDF (*.pdf)|*.pdf|Imágenes (*.jpg;*.jpeg;*.png)|*.jpg;*.jpeg;*.png"
                If openFileDialog.ShowDialog() <> DialogResult.OK Then Return

                Dim filePath As String = openFileDialog.FileName
                Dim fileExtension As String = Path.GetExtension(filePath).ToLower()

                ' Construir nombre: {n_recepcion}_{YYYYMMDD_HHMMSS}{ext}
                ' Esto asegura que cada subida es única y no choca con archivos previos
                Dim newFileName As String = String.Format("quimicos_{0}_{1:yyyyMMdd_HHmmss}{2}",
                    _nRecepcion.ToString().PadLeft(6, "0"),
                    DateTime.Now,
                    fileExtension)

                Dim uploadUrl As String = "http://app.grcfruit.cl/apis/backup_files/uploadRecepcionQuimicos.php"

                ' Crear solicitud HTTP POST
                Dim request As HttpWebRequest = DirectCast(WebRequest.Create(uploadUrl), HttpWebRequest)
                request.Method = "POST"
                request.Timeout = 300000 ' 5 minutos

                ' Configurar para multipart/form-data
                Dim boundary As String = "---------------------------" & DateTime.Now.Ticks.ToString("x")
                request.ContentType = "multipart/form-data; boundary=" & boundary
                request.KeepAlive = True

                ' Enviar el archivo
                Using requestStream As Stream = request.GetRequestStream()
                    Using writer As New StreamWriter(requestStream)
                        writer.WriteLine("--" & boundary)
                        writer.WriteLine($"Content-Disposition: form-data; name=""fileToUpload""; filename=""{newFileName}""")
                        writer.WriteLine("Content-Type: application/octet-stream")
                        writer.WriteLine()
                        writer.Flush()

                        ' Leer el archivo y escribir en el stream
                        Using fileStream As New FileStream(filePath, FileMode.Open, FileAccess.Read)
                            Dim buffer(4096) As Byte
                            Dim bytesRead As Integer = 0
                            Do
                                bytesRead = fileStream.Read(buffer, 0, buffer.Length)
                                requestStream.Write(buffer, 0, bytesRead)
                            Loop While bytesRead > 0
                        End Using

                        writer.WriteLine()
                        writer.WriteLine("--" & boundary & "--")
                        writer.Flush()
                    End Using
                End Using

                ' Obtener respuesta del servidor
                Using response As WebResponse = request.GetResponse()
                    Using responseStream As Stream = response.GetResponseStream()
                        Using reader As New StreamReader(responseStream)
                            Dim responseString As String = reader.ReadToEnd()
                            MessageBox.Show("Archivo subido exitosamente." & vbCrLf & vbCrLf &
                                            "Respuesta del servidor: " & responseString & vbCrLf & vbCrLf &
                                            "Archivo guardado como: " & newFileName,
                                            "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Error al subir el archivo: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    ' ============================================================
    ' VER GUIA
    ' Patrón idéntico a recepcion_granel.vb - btnVerDoc_Click
    ' Descarga el PDF desde el servidor y lo muestra en AxAcroPDF (formPDF)
    ' ============================================================
    Private Sub btn_ver_guia_Click(sender As Object, e As EventArgs) Handles btn_ver_guia.Click
        Try
            ' Construye el nombre del archivo (busca el más reciente para esta recepción)
            ' El nombre es: quimicos_{n_recepcion}_{YYYYMMDD_HHMMSS}.pdf
            ' Como puede haber varios, listamos todos y tomamos el más reciente
            Dim listUrl As String = "http://app.grcfruit.cl/apis/backup_files/listRecepcionQuimicos.php?prefix=quimicos_" & _nRecepcion.ToString().PadLeft(6, "0")
            Dim latestFile As String = ""

            Try
                Dim listReq As HttpWebRequest = DirectCast(WebRequest.Create(listUrl), HttpWebRequest)
                listReq.Method = "GET"
                listReq.Timeout = 30000
                Using listResp As WebResponse = listReq.GetResponse()
                    Using sr As New StreamReader(listResp.GetResponseStream())
                        Dim responseString As String = sr.ReadToEnd().Trim()
                        ' El PHP devuelve el archivo más reciente (o lista separada por |)
                        If responseString.Contains("|") Then
                            Dim archivos As String() = responseString.Split("|"c)
                            If archivos.Length > 0 Then latestFile = archivos(archivos.Length - 1)
                        ElseIf Not String.IsNullOrEmpty(responseString) AndAlso responseString <> "NO_FILES" Then
                            latestFile = responseString
                        End If
                    End Using
                End Using
            Catch
                ' Si falla el listado, intentar con el nombre fijo
                latestFile = String.Format("quimicos_{0}.pdf", _nRecepcion.ToString().PadLeft(6, "0"))
            End Try

            If String.IsNullOrEmpty(latestFile) Then
                MessageBox.Show("No hay GUIA subida para esta recepción. Use el botón 'Subir Imagen de GUIA'.",
                                "Atención", MessageBoxButtons.OK, MessageBoxIcon.Information)
                Return
            End If

            ' URL del script PHP para descargar el archivo
            Dim downloadUrl As String = "http://app.grcfruit.cl/apis/backup_files/downloadRecepcionQuimicos.php?fileName=" & Uri.EscapeDataString(latestFile)

            ' Mostrar en el visor PDF (formPDF con AxAcroPDF)
            Dim formPdf As New formPDF()
            formPdf.AxAcroPDF1.src = downloadUrl
            formPdf.Text = "GUIA Recepción #" & _nRecepcion & " - " & latestFile
            formPdf.ShowDialog()

        Catch ex As Exception
            MessageBox.Show("Error al mostrar el archivo: " & ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub
End Class
