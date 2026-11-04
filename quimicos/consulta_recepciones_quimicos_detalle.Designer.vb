<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class consulta_recepciones_quimicos_detalle
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

    Private components As System.ComponentModel.IContainer

    <System.Diagnostics.DebuggerStepThrough()>
    Private Sub InitializeComponent()
        Me.lbl_titulo = New System.Windows.Forms.Label()
        Me.lbl_cabecera = New System.Windows.Forms.Label()
        Me.dg_detalle = New System.Windows.Forms.DataGridView()
        Me.btn_subir_guia = New System.Windows.Forms.Button()
        Me.btn_ver_guia = New System.Windows.Forms.Button()
        Me.btn_cerrar = New System.Windows.Forms.Button()
        CType(Me.dg_detalle, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'lbl_titulo
        '
        Me.lbl_titulo.BackColor = System.Drawing.Color.FromArgb(CType(CType(40, Byte), Integer), CType(CType(80, Byte), Integer), CType(CType(140, Byte), Integer))
        Me.lbl_titulo.Font = New System.Drawing.Font("Microsoft Sans Serif", 12.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lbl_titulo.ForeColor = System.Drawing.Color.White
        Me.lbl_titulo.Location = New System.Drawing.Point(0, 0)
        Me.lbl_titulo.Name = "lbl_titulo"
        Me.lbl_titulo.Size = New System.Drawing.Size(820, 40)
        Me.lbl_titulo.TabIndex = 0
        Me.lbl_titulo.Text = "  DETALLE DE RECEPCIÓN DE QUÍMICOS"
        Me.lbl_titulo.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'lbl_cabecera
        '
        Me.lbl_cabecera.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_cabecera.ForeColor = System.Drawing.Color.FromArgb(40, 80, 140)
        Me.lbl_cabecera.Location = New System.Drawing.Point(12, 50)
        Me.lbl_cabecera.Name = "lbl_cabecera"
        Me.lbl_cabecera.Size = New System.Drawing.Size(796, 80)
        Me.lbl_cabecera.TabIndex = 1
        Me.lbl_cabecera.Text = "Recepción #0"
        '
        'dg_detalle
        '
        Me.dg_detalle.AllowUserToAddRows = False
        Me.dg_detalle.AllowUserToDeleteRows = False
        Me.dg_detalle.AllowUserToResizeRows = False
        Me.dg_detalle.AutoSizeColumnsMode = System.Windows.Forms.DataGridViewAutoSizeColumnsMode.Fill
        Me.dg_detalle.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dg_detalle.Location = New System.Drawing.Point(12, 140)
        Me.dg_detalle.MultiSelect = False
        Me.dg_detalle.Name = "dg_detalle"
        Me.dg_detalle.ReadOnly = True
        Me.dg_detalle.SelectionMode = System.Windows.Forms.DataGridViewSelectionMode.FullRowSelect
        Me.dg_detalle.Size = New System.Drawing.Size(796, 250)
        Me.dg_detalle.TabIndex = 2
        '
        'btn_subir_guia
        '
        Me.btn_subir_guia.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_subir_guia.Location = New System.Drawing.Point(12, 480)
        Me.btn_subir_guia.Name = "btn_subir_guia"
        Me.btn_subir_guia.Size = New System.Drawing.Size(280, 36)
        Me.btn_subir_guia.TabIndex = 4
        Me.btn_subir_guia.Text = "📎 Subir Imagen de GUIA"
        Me.btn_subir_guia.UseVisualStyleBackColor = True
        '
        'btn_ver_guia
        '
        Me.btn_ver_guia.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_ver_guia.Location = New System.Drawing.Point(300, 480)
        Me.btn_ver_guia.Name = "btn_ver_guia"
        Me.btn_ver_guia.Size = New System.Drawing.Size(190, 36)
        Me.btn_ver_guia.TabIndex = 5
        Me.btn_ver_guia.Text = "👁 Ver GUIA"
        Me.btn_ver_guia.UseVisualStyleBackColor = True
        '
        'btn_cerrar
        '
        Me.btn_cerrar.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_cerrar.Location = New System.Drawing.Point(638, 480)
        Me.btn_cerrar.Name = "btn_cerrar"
        Me.btn_cerrar.Size = New System.Drawing.Size(170, 36)
        Me.btn_cerrar.TabIndex = 3
        Me.btn_cerrar.Text = "Cerrar"
        Me.btn_cerrar.UseVisualStyleBackColor = True
        '
        'consulta_recepciones_quimicos_detalle
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.BackColor = System.Drawing.SystemColors.Control
        Me.ClientSize = New System.Drawing.Size(820, 530)
        Me.Controls.Add(Me.btn_ver_guia)
        Me.Controls.Add(Me.btn_subir_guia)
        Me.Controls.Add(Me.btn_cerrar)
        Me.Controls.Add(Me.dg_detalle)
        Me.Controls.Add(Me.lbl_cabecera)
        Me.Controls.Add(Me.lbl_titulo)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedDialog
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "consulta_recepciones_quimicos_detalle"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterParent
        Me.Text = "Detalle de Recepción"
        CType(Me.dg_detalle, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)
        Me.PerformLayout()
    End Sub

    Friend WithEvents lbl_titulo As Label
    Friend WithEvents lbl_cabecera As Label
    Friend WithEvents dg_detalle As DataGridView
    Friend WithEvents btn_cerrar As Button
    Friend WithEvents btn_subir_guia As Button
    Friend WithEvents btn_ver_guia As Button
End Class
