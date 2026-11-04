<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class recepcion_quimicos
    Inherits System.Windows.Forms.Form

    'Form reemplaza a Dispose para limpiar la lista de componentes.
    <System.Diagnostics.DebuggerNonUserCode()>
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Requerido por el Diseñador de Windows Forms
    Private components As System.ComponentModel.IContainer

    <System.Diagnostics.DebuggerStepThrough()>
    Private Sub InitializeComponent()
        Me.lbl_titulo = New System.Windows.Forms.Label()
        Me.lbl_bodega = New System.Windows.Forms.Label()
        Me.cbo_bodega = New System.Windows.Forms.ComboBox()
        Me.lbl_proveedor = New System.Windows.Forms.Label()
        Me.cbo_proveedor = New System.Windows.Forms.ComboBox()
        Me.txt_proveedor_libre = New System.Windows.Forms.TextBox()
        Me.lbl_guia = New System.Windows.Forms.Label()
        Me.txt_guia = New System.Windows.Forms.TextBox()
        Me.lbl_factura = New System.Windows.Forms.Label()
        Me.txt_factura = New System.Windows.Forms.TextBox()
        Me.lbl_lote = New System.Windows.Forms.Label()
        Me.txt_lote = New System.Windows.Forms.TextBox()
        Me.lbl_observaciones = New System.Windows.Forms.Label()
        Me.txt_observaciones = New System.Windows.Forms.TextBox()
        Me.lbl_montos = New System.Windows.Forms.Label()
        Me.lbl_neto_clp = New System.Windows.Forms.Label()
        Me.txt_neto_clp = New System.Windows.Forms.TextBox()
        Me.lbl_iva_clp = New System.Windows.Forms.Label()
        Me.txt_iva_clp = New System.Windows.Forms.TextBox()
        Me.lbl_total_clp = New System.Windows.Forms.Label()
        Me.txt_total_clp = New System.Windows.Forms.TextBox()
        Me.lbl_neto_usd = New System.Windows.Forms.Label()
        Me.txt_neto_usd = New System.Windows.Forms.TextBox()
        Me.lbl_total_usd = New System.Windows.Forms.Label()
        Me.txt_total_usd = New System.Windows.Forms.TextBox()
        Me.btn_agregar = New System.Windows.Forms.Button()
        Me.btn_quitar = New System.Windows.Forms.Button()
        Me.btn_guardar = New System.Windows.Forms.Button()
        Me.btn_limpiar = New System.Windows.Forms.Button()
        Me.dg_detalle = New System.Windows.Forms.DataGridView()
        Me.dg_entrada = New System.Windows.Forms.DataGridView()
        Me.lbl_catalogo = New System.Windows.Forms.Label()
        Me.lbl_recepcion = New System.Windows.Forms.Label()
        CType(Me.dg_detalle, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.dg_entrada, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'lbl_titulo
        '
        Me.lbl_titulo.BackColor = System.Drawing.Color.FromArgb(CType(CType(40, Byte), Integer), CType(CType(80, Byte), Integer), CType(CType(140, Byte), Integer))
        Me.lbl_titulo.Font = New System.Drawing.Font("Microsoft Sans Serif", 12.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lbl_titulo.ForeColor = System.Drawing.Color.White
        Me.lbl_titulo.Location = New System.Drawing.Point(0, 0)
        Me.lbl_titulo.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_titulo.Name = "lbl_titulo"
        Me.lbl_titulo.Size = New System.Drawing.Size(1173, 49)
        Me.lbl_titulo.TabIndex = 0
        Me.lbl_titulo.Text = "  RECEPCIÓN DE QUÍMICOS"
        Me.lbl_titulo.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'lbl_bodega
        '
        Me.lbl_bodega.AutoSize = True
        Me.lbl_bodega.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_bodega.Location = New System.Drawing.Point(16, 68)
        Me.lbl_bodega.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_bodega.Name = "lbl_bodega"
        Me.lbl_bodega.Size = New System.Drawing.Size(78, 20)
        Me.lbl_bodega.TabIndex = 4
        Me.lbl_bodega.Text = "Bodega:"
        '
        'cbo_bodega
        '
        Me.cbo_bodega.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbo_bodega.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.cbo_bodega.FormattingEnabled = True
        Me.cbo_bodega.Location = New System.Drawing.Point(104, 64)
        Me.cbo_bodega.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.cbo_bodega.Name = "cbo_bodega"
        Me.cbo_bodega.Size = New System.Drawing.Size(292, 28)
        Me.cbo_bodega.TabIndex = 5
        '
        'lbl_proveedor
        '
        Me.lbl_proveedor.AutoSize = True
        Me.lbl_proveedor.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_proveedor.Location = New System.Drawing.Point(16, 108)
        Me.lbl_proveedor.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_proveedor.Name = "lbl_proveedor"
        Me.lbl_proveedor.Size = New System.Drawing.Size(100, 20)
        Me.lbl_proveedor.TabIndex = 6
        Me.lbl_proveedor.Text = "Proveedor:"
        '
        'cbo_proveedor
        '
        Me.cbo_proveedor.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbo_proveedor.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.cbo_proveedor.FormattingEnabled = True
        Me.cbo_proveedor.Location = New System.Drawing.Point(127, 105)
        Me.cbo_proveedor.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.cbo_proveedor.Name = "cbo_proveedor"
        Me.cbo_proveedor.Size = New System.Drawing.Size(372, 28)
        Me.cbo_proveedor.TabIndex = 7
        '
        'txt_proveedor_libre
        '
        Me.txt_proveedor_libre.CharacterCasing = System.Windows.Forms.CharacterCasing.Upper
        Me.txt_proveedor_libre.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_proveedor_libre.Location = New System.Drawing.Point(507, 106)
        Me.txt_proveedor_libre.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.txt_proveedor_libre.Name = "txt_proveedor_libre"
        Me.txt_proveedor_libre.Size = New System.Drawing.Size(372, 26)
        Me.txt_proveedor_libre.TabIndex = 8
        Me.txt_proveedor_libre.Visible = False
        '
        'lbl_guia
        '
        Me.lbl_guia.AutoSize = True
        Me.lbl_guia.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_guia.Location = New System.Drawing.Point(16, 149)
        Me.lbl_guia.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_guia.Name = "lbl_guia"
        Me.lbl_guia.Size = New System.Drawing.Size(59, 20)
        Me.lbl_guia.TabIndex = 9
        Me.lbl_guia.Text = "GUIA:"
        '
        'txt_guia
        '
        Me.txt_guia.CharacterCasing = System.Windows.Forms.CharacterCasing.Upper
        Me.txt_guia.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_guia.Location = New System.Drawing.Point(127, 145)
        Me.txt_guia.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.txt_guia.MaxLength = 20
        Me.txt_guia.Name = "txt_guia"
        Me.txt_guia.Size = New System.Drawing.Size(239, 26)
        Me.txt_guia.TabIndex = 10
        '
        'lbl_factura
        '
        Me.lbl_factura.AutoSize = True
        Me.lbl_factura.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_factura.Location = New System.Drawing.Point(387, 149)
        Me.lbl_factura.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_factura.Name = "lbl_factura"
        Me.lbl_factura.Size = New System.Drawing.Size(79, 20)
        Me.lbl_factura.TabIndex = 11
        Me.lbl_factura.Text = "Factura:"
        '
        'txt_factura
        '
        Me.txt_factura.CharacterCasing = System.Windows.Forms.CharacterCasing.Upper
        Me.txt_factura.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_factura.Location = New System.Drawing.Point(480, 145)
        Me.txt_factura.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.txt_factura.MaxLength = 20
        Me.txt_factura.Name = "txt_factura"
        Me.txt_factura.Size = New System.Drawing.Size(239, 26)
        Me.txt_factura.TabIndex = 12
        '
        'lbl_lote
        '
        Me.lbl_lote.AutoSize = True
        Me.lbl_lote.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_lote.Location = New System.Drawing.Point(747, 149)
        Me.lbl_lote.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_lote.Name = "lbl_lote"
        Me.lbl_lote.Size = New System.Drawing.Size(101, 20)
        Me.lbl_lote.TabIndex = 13
        Me.lbl_lote.Text = "Lote Prov.:"
        '
        'txt_lote
        '
        Me.txt_lote.CharacterCasing = System.Windows.Forms.CharacterCasing.Upper
        Me.txt_lote.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_lote.Location = New System.Drawing.Point(864, 145)
        Me.txt_lote.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.txt_lote.MaxLength = 40
        Me.txt_lote.Name = "txt_lote"
        Me.txt_lote.Size = New System.Drawing.Size(239, 26)
        Me.txt_lote.TabIndex = 14
        '
        'lbl_observaciones
        '
        Me.lbl_observaciones.AutoSize = True
        Me.lbl_observaciones.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_observaciones.Location = New System.Drawing.Point(16, 190)
        Me.lbl_observaciones.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_observaciones.Name = "lbl_observaciones"
        Me.lbl_observaciones.Size = New System.Drawing.Size(140, 20)
        Me.lbl_observaciones.TabIndex = 15
        Me.lbl_observaciones.Text = "Observaciones:"
        '
        'txt_observaciones
        '
        Me.txt_observaciones.CharacterCasing = System.Windows.Forms.CharacterCasing.Upper
        Me.txt_observaciones.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_observaciones.Location = New System.Drawing.Point(167, 186)
        Me.txt_observaciones.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.txt_observaciones.MaxLength = 255
        Me.txt_observaciones.Name = "txt_observaciones"
        Me.txt_observaciones.Size = New System.Drawing.Size(936, 26)
        Me.txt_observaciones.TabIndex = 16
        '
        'lbl_montos
        '
        Me.lbl_montos.AutoSize = True
        Me.lbl_montos.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_montos.ForeColor = System.Drawing.Color.FromArgb(40, 80, 140)
        Me.lbl_montos.Location = New System.Drawing.Point(16, 225)
        Me.lbl_montos.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_montos.Name = "lbl_montos"
        Me.lbl_montos.Size = New System.Drawing.Size(180, 20)
        Me.lbl_montos.TabIndex = 30
        Me.lbl_montos.Text = "MONTOS DE LA GUÍA"
        '
        'lbl_neto_clp
        '
        Me.lbl_neto_clp.AutoSize = True
        Me.lbl_neto_clp.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_neto_clp.Location = New System.Drawing.Point(16, 257)
        Me.lbl_neto_clp.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_neto_clp.Name = "lbl_neto_clp"
        Me.lbl_neto_clp.Size = New System.Drawing.Size(110, 20)
        Me.lbl_neto_clp.TabIndex = 31
        Me.lbl_neto_clp.Text = "Neto CLP ($):"
        '
        'txt_neto_clp
        '
        Me.txt_neto_clp.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_neto_clp.Location = New System.Drawing.Point(140, 253)
        Me.txt_neto_clp.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.txt_neto_clp.Name = "txt_neto_clp"
        Me.txt_neto_clp.Size = New System.Drawing.Size(150, 26)
        Me.txt_neto_clp.TabIndex = 32
        Me.txt_neto_clp.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'lbl_iva_clp
        '
        Me.lbl_iva_clp.AutoSize = True
        Me.lbl_iva_clp.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_iva_clp.Location = New System.Drawing.Point(303, 257)
        Me.lbl_iva_clp.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_iva_clp.Name = "lbl_iva_clp"
        Me.lbl_iva_clp.Size = New System.Drawing.Size(110, 20)
        Me.lbl_iva_clp.TabIndex = 33
        Me.lbl_iva_clp.Text = "IVA CLP ($):"
        '
        'txt_iva_clp
        '
        Me.txt_iva_clp.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_iva_clp.Location = New System.Drawing.Point(427, 253)
        Me.txt_iva_clp.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.txt_iva_clp.Name = "txt_iva_clp"
        Me.txt_iva_clp.Size = New System.Drawing.Size(150, 26)
        Me.txt_iva_clp.TabIndex = 34
        Me.txt_iva_clp.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'lbl_total_clp
        '
        Me.lbl_total_clp.AutoSize = True
        Me.lbl_total_clp.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_total_clp.Location = New System.Drawing.Point(590, 257)
        Me.lbl_total_clp.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_total_clp.Name = "lbl_total_clp"
        Me.lbl_total_clp.Size = New System.Drawing.Size(110, 20)
        Me.lbl_total_clp.TabIndex = 35
        Me.lbl_total_clp.Text = "Total CLP ($):"
        '
        'txt_total_clp
        '
        Me.txt_total_clp.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.txt_total_clp.Location = New System.Drawing.Point(714, 253)
        Me.txt_total_clp.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.txt_total_clp.Name = "txt_total_clp"
        Me.txt_total_clp.Size = New System.Drawing.Size(170, 26)
        Me.txt_total_clp.TabIndex = 36
        Me.txt_total_clp.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        Me.txt_total_clp.BackColor = System.Drawing.Color.LightYellow
        '
        'lbl_neto_usd
        '
        Me.lbl_neto_usd.AutoSize = True
        Me.lbl_neto_usd.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_neto_usd.Location = New System.Drawing.Point(16, 297)
        Me.lbl_neto_usd.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_neto_usd.Name = "lbl_neto_usd"
        Me.lbl_neto_usd.Size = New System.Drawing.Size(110, 20)
        Me.lbl_neto_usd.TabIndex = 37
        Me.lbl_neto_usd.Text = "Neto USD (US$):"
        '
        'txt_neto_usd
        '
        Me.txt_neto_usd.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_neto_usd.Location = New System.Drawing.Point(140, 293)
        Me.txt_neto_usd.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.txt_neto_usd.Name = "txt_neto_usd"
        Me.txt_neto_usd.Size = New System.Drawing.Size(150, 26)
        Me.txt_neto_usd.TabIndex = 38
        Me.txt_neto_usd.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'lbl_total_usd
        '
        Me.lbl_total_usd.AutoSize = True
        Me.lbl_total_usd.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_total_usd.Location = New System.Drawing.Point(303, 297)
        Me.lbl_total_usd.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_total_usd.Name = "lbl_total_usd"
        Me.lbl_total_usd.Size = New System.Drawing.Size(110, 20)
        Me.lbl_total_usd.TabIndex = 39
        Me.lbl_total_usd.Text = "Total USD (US$):"
        '
        'txt_total_usd
        '
        Me.txt_total_usd.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.txt_total_usd.Location = New System.Drawing.Point(427, 293)
        Me.txt_total_usd.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.txt_total_usd.Name = "txt_total_usd"
        Me.txt_total_usd.Size = New System.Drawing.Size(170, 26)
        Me.txt_total_usd.TabIndex = 40
        Me.txt_total_usd.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        Me.txt_total_usd.BackColor = System.Drawing.Color.LightYellow
        '
        'btn_agregar
        '
        Me.btn_agregar.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.0!, System.Drawing.FontStyle.Bold)
        Me.btn_agregar.Location = New System.Drawing.Point(910, 380)
        Me.btn_agregar.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.btn_agregar.Name = "btn_agregar"
        Me.btn_agregar.Size = New System.Drawing.Size(240, 39)
        Me.btn_agregar.TabIndex = 17
        Me.btn_agregar.Text = "Agregar a Recepción ▶"
        Me.btn_agregar.UseVisualStyleBackColor = True
        '
        'btn_quitar
        '
        Me.btn_quitar.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.0!, System.Drawing.FontStyle.Bold)
        Me.btn_quitar.Location = New System.Drawing.Point(910, 620)
        Me.btn_quitar.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.btn_quitar.Name = "btn_quitar"
        Me.btn_quitar.Size = New System.Drawing.Size(240, 39)
        Me.btn_quitar.TabIndex = 18
        Me.btn_quitar.Text = "◀ Quitar de Recepción"
        Me.btn_quitar.UseVisualStyleBackColor = True
        '
        'btn_guardar
        '
        Me.btn_guardar.Enabled = False
        Me.btn_guardar.Font = New System.Drawing.Font("Microsoft Sans Serif", 11.0!, System.Drawing.FontStyle.Bold)
        Me.btn_guardar.Location = New System.Drawing.Point(20, 895)
        Me.btn_guardar.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.btn_guardar.Name = "btn_guardar"
        Me.btn_guardar.Size = New System.Drawing.Size(733, 39)
        Me.btn_guardar.TabIndex = 19
        Me.btn_guardar.Text = "✓ Guardar Recepción"
        Me.btn_guardar.UseVisualStyleBackColor = True
        '
        'btn_limpiar
        '
        Me.btn_limpiar.Font = New System.Drawing.Font("Microsoft Sans Serif", 11.0!, System.Drawing.FontStyle.Bold)
        Me.btn_limpiar.Location = New System.Drawing.Point(772, 895)
        Me.btn_limpiar.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.btn_limpiar.Name = "btn_limpiar"
        Me.btn_limpiar.Size = New System.Drawing.Size(200, 39)
        Me.btn_limpiar.TabIndex = 20
        Me.btn_limpiar.Text = "Limpiar"
        Me.btn_limpiar.UseVisualStyleBackColor = True
        '
        'dg_detalle
        '
        Me.dg_detalle.AllowUserToAddRows = False
        Me.dg_detalle.AllowUserToDeleteRows = False
        Me.dg_detalle.AllowUserToResizeColumns = False
        Me.dg_detalle.AllowUserToResizeRows = False
        Me.dg_detalle.AutoSizeColumnsMode = System.Windows.Forms.DataGridViewAutoSizeColumnsMode.Fill
        Me.dg_detalle.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dg_detalle.Location = New System.Drawing.Point(16, 366)
        Me.dg_detalle.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.dg_detalle.MultiSelect = False
        Me.dg_detalle.Name = "dg_detalle"
        Me.dg_detalle.ReadOnly = True
        Me.dg_detalle.RowHeadersWidth = 51
        Me.dg_detalle.SelectionMode = System.Windows.Forms.DataGridViewSelectionMode.FullRowSelect
        Me.dg_detalle.Size = New System.Drawing.Size(880, 231)
        Me.dg_detalle.TabIndex = 23
        '
        'dg_entrada
        '
        Me.dg_entrada.AllowUserToAddRows = False
        Me.dg_entrada.AllowUserToDeleteRows = False
        Me.dg_entrada.AllowUserToResizeColumns = False
        Me.dg_entrada.AllowUserToResizeRows = False
        Me.dg_entrada.AutoSizeColumnsMode = System.Windows.Forms.DataGridViewAutoSizeColumnsMode.Fill
        Me.dg_entrada.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dg_entrada.Location = New System.Drawing.Point(16, 615)
        Me.dg_entrada.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.dg_entrada.MultiSelect = False
        Me.dg_entrada.Name = "dg_entrada"
        Me.dg_entrada.RowHeadersWidth = 51
        Me.dg_entrada.SelectionMode = System.Windows.Forms.DataGridViewSelectionMode.FullRowSelect
        Me.dg_entrada.Size = New System.Drawing.Size(880, 262)
        Me.dg_entrada.TabIndex = 24
        '
        'lbl_catalogo
        '
        Me.lbl_catalogo.AutoSize = True
        Me.lbl_catalogo.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_catalogo.ForeColor = System.Drawing.Color.FromArgb(40, 80, 140)
        Me.lbl_catalogo.Location = New System.Drawing.Point(16, 340)
        Me.lbl_catalogo.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_catalogo.Name = "lbl_catalogo"
        Me.lbl_catalogo.Size = New System.Drawing.Size(239, 20)
        Me.lbl_catalogo.TabIndex = 21
        Me.lbl_catalogo.Text = "CATÁLOGO DE QUÍMICOS"
        '
        'lbl_recepcion
        '
        Me.lbl_recepcion.AutoSize = True
        Me.lbl_recepcion.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_recepcion.ForeColor = System.Drawing.Color.FromArgb(CType(CType(40, Byte), Integer), CType(CType(80, Byte), Integer), CType(CType(140, Byte), Integer))
        Me.lbl_recepcion.Location = New System.Drawing.Point(25, 595)
        Me.lbl_recepcion.Margin = New System.Windows.Forms.Padding(4, 0, 4, 0)
        Me.lbl_recepcion.Name = "lbl_recepcion"
        Me.lbl_recepcion.Size = New System.Drawing.Size(298, 20)
        Me.lbl_recepcion.TabIndex = 22
        Me.lbl_recepcion.Text = "QUÍMICOS DE ESTA RECEPCIÓN"
        '
        'recepcion_quimicos
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(8.0!, 16.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.BackColor = System.Drawing.SystemColors.Control
        Me.ClientSize = New System.Drawing.Size(1173, 950)
        Me.Controls.Add(Me.dg_entrada)
        Me.Controls.Add(Me.dg_detalle)
        Me.Controls.Add(Me.lbl_recepcion)
        Me.Controls.Add(Me.lbl_catalogo)
        Me.Controls.Add(Me.btn_limpiar)
        Me.Controls.Add(Me.btn_guardar)
        Me.Controls.Add(Me.btn_quitar)
        Me.Controls.Add(Me.btn_agregar)
        Me.Controls.Add(Me.txt_total_usd)
        Me.Controls.Add(Me.lbl_total_usd)
        Me.Controls.Add(Me.txt_neto_usd)
        Me.Controls.Add(Me.lbl_neto_usd)
        Me.Controls.Add(Me.txt_total_clp)
        Me.Controls.Add(Me.lbl_total_clp)
        Me.Controls.Add(Me.txt_iva_clp)
        Me.Controls.Add(Me.lbl_iva_clp)
        Me.Controls.Add(Me.txt_neto_clp)
        Me.Controls.Add(Me.lbl_neto_clp)
        Me.Controls.Add(Me.lbl_montos)
        Me.Controls.Add(Me.txt_observaciones)
        Me.Controls.Add(Me.lbl_observaciones)
        Me.Controls.Add(Me.txt_lote)
        Me.Controls.Add(Me.lbl_lote)
        Me.Controls.Add(Me.txt_factura)
        Me.Controls.Add(Me.lbl_factura)
        Me.Controls.Add(Me.txt_guia)
        Me.Controls.Add(Me.lbl_guia)
        Me.Controls.Add(Me.txt_proveedor_libre)
        Me.Controls.Add(Me.cbo_proveedor)
        Me.Controls.Add(Me.lbl_proveedor)
        Me.Controls.Add(Me.cbo_bodega)
        Me.Controls.Add(Me.lbl_bodega)
        Me.Controls.Add(Me.lbl_titulo)
        Me.Margin = New System.Windows.Forms.Padding(4, 4, 4, 4)
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.MinimumSize = New System.Drawing.Size(1167, 728)
        Me.Name = "recepcion_quimicos"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PRD - Recepción de Químicos"
        CType(Me.dg_detalle, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.dg_entrada, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub

    Friend WithEvents lbl_titulo As Label
    Friend WithEvents lbl_bodega As Label
    Friend WithEvents cbo_bodega As ComboBox
    Friend WithEvents lbl_proveedor As Label
    Friend WithEvents cbo_proveedor As ComboBox
    Friend WithEvents txt_proveedor_libre As TextBox
    Friend WithEvents lbl_guia As Label
    Friend WithEvents txt_guia As TextBox
    Friend WithEvents lbl_factura As Label
    Friend WithEvents txt_factura As TextBox
    Friend WithEvents lbl_lote As Label
    Friend WithEvents txt_lote As TextBox
    Friend WithEvents lbl_observaciones As Label
    Friend WithEvents txt_observaciones As TextBox
    Friend WithEvents lbl_montos As Label
    Friend WithEvents lbl_neto_clp As Label
    Friend WithEvents txt_neto_clp As TextBox
    Friend WithEvents lbl_iva_clp As Label
    Friend WithEvents txt_iva_clp As TextBox
    Friend WithEvents lbl_total_clp As Label
    Friend WithEvents txt_total_clp As TextBox
    Friend WithEvents lbl_neto_usd As Label
    Friend WithEvents txt_neto_usd As TextBox
    Friend WithEvents lbl_total_usd As Label
    Friend WithEvents txt_total_usd As TextBox
    Friend WithEvents btn_agregar As Button
    Friend WithEvents btn_quitar As Button
    Friend WithEvents btn_guardar As Button
    Friend WithEvents btn_limpiar As Button
    Friend WithEvents dg_detalle As DataGridView
    Friend WithEvents dg_entrada As DataGridView
    Friend WithEvents lbl_catalogo As Label
    Friend WithEvents lbl_recepcion As Label
End Class
