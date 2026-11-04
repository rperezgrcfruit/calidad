<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class quimicos_maestro
    Inherits System.Windows.Forms.Form

    <System.Diagnostics.DebuggerNonUserCode()>
    Protected Overrides Sub Dispose(disposing As Boolean)
        If disposing AndAlso components IsNot Nothing Then
            components.Dispose()
        End If
        MyBase.Dispose(disposing)
    End Sub

    Private components As System.ComponentModel.IContainer

    <System.Diagnostics.DebuggerStepThrough()>
    Private Sub InitializeComponent()
        Me.lbl_titulo = New System.Windows.Forms.Label()
        Me.rb_quimicos = New System.Windows.Forms.RadioButton()
        Me.rb_bodegas = New System.Windows.Forms.RadioButton()
        Me.txt_codigo = New System.Windows.Forms.TextBox()
        Me.txt_nombre = New System.Windows.Forms.TextBox()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.cbo_tipo = New System.Windows.Forms.ComboBox()
        Me.cbo_unidad = New System.Windows.Forms.ComboBox()
        Me.txt_sag = New System.Windows.Forms.TextBox()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.Label5 = New System.Windows.Forms.Label()
        Me.btn_guardar = New System.Windows.Forms.Button()
        Me.btn_modificar = New System.Windows.Forms.Button()
        Me.btn_eliminar = New System.Windows.Forms.Button()
        Me.btn_limpiar = New System.Windows.Forms.Button()
        Me.dg_datos = New System.Windows.Forms.DataGridView()
        Me.Label6 = New System.Windows.Forms.Label()
        CType(Me.dg_datos, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'lbl_titulo
        '
        Me.lbl_titulo.BackColor = System.Drawing.Color.FromArgb(CType(CType(40, Byte), Integer), CType(CType(80, Byte), Integer), CType(CType(140, Byte), Integer))
        Me.lbl_titulo.Font = New System.Drawing.Font("Microsoft Sans Serif", 12.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lbl_titulo.ForeColor = System.Drawing.Color.White
        Me.lbl_titulo.Location = New System.Drawing.Point(0, 0)
        Me.lbl_titulo.Name = "lbl_titulo"
        Me.lbl_titulo.RightToLeft = System.Windows.Forms.RightToLeft.No
        Me.lbl_titulo.Size = New System.Drawing.Size(958, 40)
        Me.lbl_titulo.TabIndex = 0
        Me.lbl_titulo.Text = "  MANTENEDOR DE QUIMICOS"
        Me.lbl_titulo.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'rb_quimicos
        '
        Me.rb_quimicos.AutoSize = True
        Me.rb_quimicos.Checked = True
        Me.rb_quimicos.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.rb_quimicos.Location = New System.Drawing.Point(10, 55)
        Me.rb_quimicos.Name = "rb_quimicos"
        Me.rb_quimicos.Size = New System.Drawing.Size(84, 21)
        Me.rb_quimicos.TabIndex = 1
        Me.rb_quimicos.TabStop = True
        Me.rb_quimicos.Text = "&Quimicos"
        Me.rb_quimicos.UseVisualStyleBackColor = True
        '
        'rb_bodegas
        '
        Me.rb_bodegas.AutoSize = True
        Me.rb_bodegas.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.rb_bodegas.Location = New System.Drawing.Point(105, 55)
        Me.rb_bodegas.Name = "rb_bodegas"
        Me.rb_bodegas.Size = New System.Drawing.Size(82, 21)
        Me.rb_bodegas.TabIndex = 2
        Me.rb_bodegas.Text = "&Bodegas"
        Me.rb_bodegas.UseVisualStyleBackColor = True
        '
        'txt_codigo
        '
        Me.txt_codigo.CharacterCasing = System.Windows.Forms.CharacterCasing.Upper
        Me.txt_codigo.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_codigo.Location = New System.Drawing.Point(95, 90)
        Me.txt_codigo.MaxLength = 10
        Me.txt_codigo.Name = "txt_codigo"
        Me.txt_codigo.Size = New System.Drawing.Size(120, 23)
        Me.txt_codigo.TabIndex = 4
        '
        'txt_nombre
        '
        Me.txt_nombre.CharacterCasing = System.Windows.Forms.CharacterCasing.Upper
        Me.txt_nombre.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_nombre.Location = New System.Drawing.Point(95, 120)
        Me.txt_nombre.Name = "txt_nombre"
        Me.txt_nombre.Size = New System.Drawing.Size(320, 23)
        Me.txt_nombre.TabIndex = 6
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.Label1.Location = New System.Drawing.Point(10, 95)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(56, 17)
        Me.Label1.TabIndex = 3
        Me.Label1.Text = "Codigo:"
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.Label2.Location = New System.Drawing.Point(10, 125)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(62, 17)
        Me.Label2.TabIndex = 5
        Me.Label2.Text = "Nombre:"
        '
        'cbo_tipo
        '
        Me.cbo_tipo.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbo_tipo.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.cbo_tipo.FormattingEnabled = True
        Me.cbo_tipo.Location = New System.Drawing.Point(95, 150)
        Me.cbo_tipo.Name = "cbo_tipo"
        Me.cbo_tipo.Size = New System.Drawing.Size(220, 24)
        Me.cbo_tipo.TabIndex = 8
        '
        'cbo_unidad
        '
        Me.cbo_unidad.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbo_unidad.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.cbo_unidad.FormattingEnabled = True
        Me.cbo_unidad.Location = New System.Drawing.Point(145, 180)
        Me.cbo_unidad.Name = "cbo_unidad"
        Me.cbo_unidad.Size = New System.Drawing.Size(120, 24)
        Me.cbo_unidad.TabIndex = 10
        '
        'txt_sag
        '
        Me.txt_sag.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_sag.Location = New System.Drawing.Point(93, 216)
        Me.txt_sag.Name = "txt_sag"
        Me.txt_sag.Size = New System.Drawing.Size(160, 23)
        Me.txt_sag.TabIndex = 12
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.Label3.Location = New System.Drawing.Point(12, 187)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(127, 17)
        Me.Label3.TabIndex = 9
        Me.Label3.Text = "Unidad de Medida:"
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.Label4.Location = New System.Drawing.Point(12, 222)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(75, 17)
        Me.Label4.TabIndex = 11
        Me.Label4.Text = "Reg. SAG:"
        '
        'Label5
        '
        Me.Label5.AutoSize = True
        Me.Label5.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.Label5.Location = New System.Drawing.Point(50, 200)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(40, 17)
        Me.Label5.TabIndex = 7
        Me.Label5.Text = "Tipo:"
        '
        'btn_guardar
        '
        Me.btn_guardar.Enabled = False
        Me.btn_guardar.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_guardar.Location = New System.Drawing.Point(578, 95)
        Me.btn_guardar.Name = "btn_guardar"
        Me.btn_guardar.Size = New System.Drawing.Size(80, 67)
        Me.btn_guardar.TabIndex = 13
        Me.btn_guardar.Text = "Guardar"
        Me.btn_guardar.UseVisualStyleBackColor = True
        '
        'btn_modificar
        '
        Me.btn_modificar.Enabled = False
        Me.btn_modificar.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_modificar.Location = New System.Drawing.Point(680, 95)
        Me.btn_modificar.Name = "btn_modificar"
        Me.btn_modificar.Size = New System.Drawing.Size(90, 67)
        Me.btn_modificar.TabIndex = 14
        Me.btn_modificar.Text = "Modificar"
        Me.btn_modificar.UseVisualStyleBackColor = True
        '
        'btn_eliminar
        '
        Me.btn_eliminar.Enabled = False
        Me.btn_eliminar.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_eliminar.Location = New System.Drawing.Point(780, 95)
        Me.btn_eliminar.Name = "btn_eliminar"
        Me.btn_eliminar.Size = New System.Drawing.Size(80, 67)
        Me.btn_eliminar.TabIndex = 15
        Me.btn_eliminar.Text = "Eliminar"
        Me.btn_eliminar.UseVisualStyleBackColor = True
        '
        'btn_limpiar
        '
        Me.btn_limpiar.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_limpiar.Location = New System.Drawing.Point(870, 95)
        Me.btn_limpiar.Name = "btn_limpiar"
        Me.btn_limpiar.Size = New System.Drawing.Size(80, 67)
        Me.btn_limpiar.TabIndex = 16
        Me.btn_limpiar.Text = "Nuevo"
        Me.btn_limpiar.UseVisualStyleBackColor = True
        '
        'dg_datos
        '
        Me.dg_datos.AllowUserToAddRows = False
        Me.dg_datos.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.dg_datos.AutoSizeColumnsMode = System.Windows.Forms.DataGridViewAutoSizeColumnsMode.Fill
        Me.dg_datos.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dg_datos.Location = New System.Drawing.Point(15, 275)
        Me.dg_datos.MultiSelect = False
        Me.dg_datos.Name = "dg_datos"
        Me.dg_datos.ReadOnly = True
        Me.dg_datos.SelectionMode = System.Windows.Forms.DataGridViewSelectionMode.FullRowSelect
        Me.dg_datos.Size = New System.Drawing.Size(932, 350)
        Me.dg_datos.TabIndex = 17
        '
        'Label6
        '
        Me.Label6.AutoSize = True
        Me.Label6.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.Label6.Location = New System.Drawing.Point(12, 156)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(36, 17)
        Me.Label6.TabIndex = 11
        Me.Label6.Text = "Tipo"
        '
        'quimicos_maestro
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(959, 660)
        Me.Controls.Add(Me.dg_datos)
        Me.Controls.Add(Me.btn_limpiar)
        Me.Controls.Add(Me.btn_eliminar)
        Me.Controls.Add(Me.btn_modificar)
        Me.Controls.Add(Me.btn_guardar)
        Me.Controls.Add(Me.txt_sag)
        Me.Controls.Add(Me.Label6)
        Me.Controls.Add(Me.Label4)
        Me.Controls.Add(Me.cbo_unidad)
        Me.Controls.Add(Me.Label3)
        Me.Controls.Add(Me.cbo_tipo)
        Me.Controls.Add(Me.txt_nombre)
        Me.Controls.Add(Me.Label2)
        Me.Controls.Add(Me.txt_codigo)
        Me.Controls.Add(Me.Label1)
        Me.Controls.Add(Me.rb_bodegas)
        Me.Controls.Add(Me.rb_quimicos)
        Me.Controls.Add(Me.lbl_titulo)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "quimicos_maestro"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PRD - Mantenedor Quimicos y Bodegas"
        CType(Me.dg_datos, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub

    Friend WithEvents lbl_titulo As Label
    Friend WithEvents rb_quimicos As RadioButton
    Friend WithEvents rb_bodegas As RadioButton
    Friend WithEvents txt_codigo As System.Windows.Forms.TextBox
    Friend WithEvents txt_nombre As System.Windows.Forms.TextBox
    Friend WithEvents Label1 As Label
    Friend WithEvents Label2 As Label
    Friend WithEvents cbo_tipo As System.Windows.Forms.ComboBox
    Friend WithEvents cbo_unidad As System.Windows.Forms.ComboBox
    Friend WithEvents txt_sag As System.Windows.Forms.TextBox
    Friend WithEvents Label3 As Label
    Friend WithEvents Label4 As Label
    Friend WithEvents Label5 As Label
    Friend WithEvents btn_guardar As Button
    Friend WithEvents btn_modificar As Button
    Friend WithEvents btn_eliminar As Button
    Friend WithEvents btn_limpiar As Button
    Friend WithEvents dg_datos As DataGridView
    Friend WithEvents Label6 As Label
End Class
