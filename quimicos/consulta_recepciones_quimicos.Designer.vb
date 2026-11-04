<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class consulta_recepciones_quimicos
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
        Me.lbl_desde = New System.Windows.Forms.Label()
        Me.txt_desde = New System.Windows.Forms.TextBox()
        Me.lbl_hasta = New System.Windows.Forms.Label()
        Me.txt_hasta = New System.Windows.Forms.TextBox()
        Me.btn_filtrar = New System.Windows.Forms.Button()
        Me.btn_limpiar_filtros = New System.Windows.Forms.Button()
        Me.dg_recepciones = New System.Windows.Forms.DataGridView()
        Me.lbl_resumen = New System.Windows.Forms.Label()
        Me.btn_ver_detalle = New System.Windows.Forms.Button()
        Me.btn_actualizar = New System.Windows.Forms.Button()
        Me.btn_exportar = New System.Windows.Forms.Button()
        Me.btn_cerrar = New System.Windows.Forms.Button()
        CType(Me.dg_recepciones, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'lbl_titulo
        '
        Me.lbl_titulo.BackColor = System.Drawing.Color.FromArgb(CType(CType(40, Byte), Integer), CType(CType(80, Byte), Integer), CType(CType(140, Byte), Integer))
        Me.lbl_titulo.Font = New System.Drawing.Font("Microsoft Sans Serif", 12.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lbl_titulo.ForeColor = System.Drawing.Color.White
        Me.lbl_titulo.Location = New System.Drawing.Point(0, 0)
        Me.lbl_titulo.Name = "lbl_titulo"
        Me.lbl_titulo.Size = New System.Drawing.Size(800, 40)
        Me.lbl_titulo.TabIndex = 0
        Me.lbl_titulo.Text = "  CONSULTA DE RECEPCIONES DE QUÍMICOS"
        Me.lbl_titulo.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'lbl_bodega
        '
        Me.lbl_bodega.AutoSize = True
        Me.lbl_bodega.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_bodega.Location = New System.Drawing.Point(12, 55)
        Me.lbl_bodega.Name = "lbl_bodega"
        Me.lbl_bodega.Size = New System.Drawing.Size(60, 17)
        Me.lbl_bodega.TabIndex = 1
        Me.lbl_bodega.Text = "Bodega:"
        '
        'cbo_bodega
        '
        Me.cbo_bodega.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbo_bodega.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.cbo_bodega.FormattingEnabled = True
        Me.cbo_bodega.Location = New System.Drawing.Point(78, 52)
        Me.cbo_bodega.Name = "cbo_bodega"
        Me.cbo_bodega.Size = New System.Drawing.Size(220, 24)
        Me.cbo_bodega.TabIndex = 2
        '
        'lbl_proveedor
        '
        Me.lbl_proveedor.AutoSize = True
        Me.lbl_proveedor.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_proveedor.Location = New System.Drawing.Point(310, 55)
        Me.lbl_proveedor.Name = "lbl_proveedor"
        Me.lbl_proveedor.Size = New System.Drawing.Size(75, 17)
        Me.lbl_proveedor.TabIndex = 3
        Me.lbl_proveedor.Text = "Proveedor:"
        '
        'cbo_proveedor
        '
        Me.cbo_proveedor.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbo_proveedor.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.cbo_proveedor.FormattingEnabled = True
        Me.cbo_proveedor.Location = New System.Drawing.Point(390, 52)
        Me.cbo_proveedor.Name = "cbo_proveedor"
        Me.cbo_proveedor.Size = New System.Drawing.Size(280, 24)
        Me.cbo_proveedor.TabIndex = 4
        '
        'lbl_desde
        '
        Me.lbl_desde.AutoSize = True
        Me.lbl_desde.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_desde.Location = New System.Drawing.Point(12, 92)
        Me.lbl_desde.Name = "lbl_desde"
        Me.lbl_desde.Size = New System.Drawing.Size(53, 17)
        Me.lbl_desde.TabIndex = 5
        Me.lbl_desde.Text = "Desde:"
        '
        'txt_desde
        '
        Me.txt_desde.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_desde.Location = New System.Drawing.Point(72, 89)
        Me.txt_desde.Name = "txt_desde"
        Me.txt_desde.Size = New System.Drawing.Size(100, 22)
        Me.txt_desde.TabIndex = 6
        '
        'lbl_hasta
        '
        Me.lbl_hasta.AutoSize = True
        Me.lbl_hasta.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_hasta.Location = New System.Drawing.Point(185, 92)
        Me.lbl_hasta.Name = "lbl_hasta"
        Me.lbl_hasta.Size = New System.Drawing.Size(50, 17)
        Me.lbl_hasta.TabIndex = 7
        Me.lbl_hasta.Text = "Hasta:"
        '
        'txt_hasta
        '
        Me.txt_hasta.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!)
        Me.txt_hasta.Location = New System.Drawing.Point(240, 89)
        Me.txt_hasta.Name = "txt_hasta"
        Me.txt_hasta.Size = New System.Drawing.Size(100, 22)
        Me.txt_hasta.TabIndex = 8
        '
        'btn_filtrar
        '
        Me.btn_filtrar.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_filtrar.Location = New System.Drawing.Point(360, 80)
        Me.btn_filtrar.Name = "btn_filtrar"
        Me.btn_filtrar.Size = New System.Drawing.Size(110, 36)
        Me.btn_filtrar.TabIndex = 9
        Me.btn_filtrar.Text = "🔍 Filtrar"
        Me.btn_filtrar.UseVisualStyleBackColor = True
        '
        'btn_limpiar_filtros
        '
        Me.btn_limpiar_filtros.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_limpiar_filtros.Location = New System.Drawing.Point(478, 80)
        Me.btn_limpiar_filtros.Name = "btn_limpiar_filtros"
        Me.btn_limpiar_filtros.Size = New System.Drawing.Size(130, 36)
        Me.btn_limpiar_filtros.TabIndex = 10
        Me.btn_limpiar_filtros.Text = "🧹 Limpiar Filtros"
        Me.btn_limpiar_filtros.UseVisualStyleBackColor = True
        '
        'dg_recepciones
        '
        Me.dg_recepciones.AllowUserToAddRows = False
        Me.dg_recepciones.AllowUserToDeleteRows = False
        Me.dg_recepciones.AllowUserToResizeRows = False
        Me.dg_recepciones.AutoSizeColumnsMode = System.Windows.Forms.DataGridViewAutoSizeColumnsMode.Fill
        Me.dg_recepciones.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dg_recepciones.Location = New System.Drawing.Point(12, 130)
        Me.dg_recepciones.MultiSelect = False
        Me.dg_recepciones.Name = "dg_recepciones"
        Me.dg_recepciones.ReadOnly = True
        Me.dg_recepciones.SelectionMode = System.Windows.Forms.DataGridViewSelectionMode.FullRowSelect
        Me.dg_recepciones.Size = New System.Drawing.Size(776, 360)
        Me.dg_recepciones.TabIndex = 11
        '
        'lbl_resumen
        '
        Me.lbl_resumen.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.lbl_resumen.ForeColor = System.Drawing.Color.FromArgb(40, 80, 140)
        Me.lbl_resumen.Location = New System.Drawing.Point(12, 500)
        Me.lbl_resumen.Name = "lbl_resumen"
        Me.lbl_resumen.Size = New System.Drawing.Size(776, 22)
        Me.lbl_resumen.TabIndex = 12
        Me.lbl_resumen.Text = "Total recepciones: 0"
        Me.lbl_resumen.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'btn_ver_detalle
        '
        Me.btn_ver_detalle.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_ver_detalle.Location = New System.Drawing.Point(12, 535)
        Me.btn_ver_detalle.Name = "btn_ver_detalle"
        Me.btn_ver_detalle.Size = New System.Drawing.Size(170, 36)
        Me.btn_ver_detalle.TabIndex = 13
        Me.btn_ver_detalle.Text = "📋 Ver Detalle"
        Me.btn_ver_detalle.UseVisualStyleBackColor = True
        '
        'btn_actualizar
        '
        Me.btn_actualizar.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_actualizar.Location = New System.Drawing.Point(190, 535)
        Me.btn_actualizar.Name = "btn_actualizar"
        Me.btn_actualizar.Size = New System.Drawing.Size(150, 36)
        Me.btn_actualizar.TabIndex = 14
        Me.btn_actualizar.Text = "🔄 Actualizar"
        Me.btn_actualizar.UseVisualStyleBackColor = True
        '
        'btn_exportar
        '
        Me.btn_exportar.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_exportar.Location = New System.Drawing.Point(348, 535)
        Me.btn_exportar.Name = "btn_exportar"
        Me.btn_exportar.Size = New System.Drawing.Size(170, 36)
        Me.btn_exportar.TabIndex = 15
        Me.btn_exportar.Text = "📊 Exportar Excel"
        Me.btn_exportar.UseVisualStyleBackColor = True
        '
        'btn_cerrar
        '
        Me.btn_cerrar.Font = New System.Drawing.Font("Microsoft Sans Serif", 10.0!, System.Drawing.FontStyle.Bold)
        Me.btn_cerrar.Location = New System.Drawing.Point(618, 535)
        Me.btn_cerrar.Name = "btn_cerrar"
        Me.btn_cerrar.Size = New System.Drawing.Size(170, 36)
        Me.btn_cerrar.TabIndex = 16
        Me.btn_cerrar.Text = "Cerrar"
        Me.btn_cerrar.UseVisualStyleBackColor = True
        '
        'consulta_recepciones_quimicos
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.BackColor = System.Drawing.SystemColors.Control
        Me.ClientSize = New System.Drawing.Size(800, 595)
        Me.MinimumSize = New System.Drawing.Size(800, 595)
        Me.Controls.Add(Me.btn_cerrar)
        Me.Controls.Add(Me.btn_exportar)
        Me.Controls.Add(Me.btn_actualizar)
        Me.Controls.Add(Me.btn_ver_detalle)
        Me.Controls.Add(Me.lbl_resumen)
        Me.Controls.Add(Me.dg_recepciones)
        Me.Controls.Add(Me.btn_limpiar_filtros)
        Me.Controls.Add(Me.btn_filtrar)
        Me.Controls.Add(Me.txt_hasta)
        Me.Controls.Add(Me.lbl_hasta)
        Me.Controls.Add(Me.txt_desde)
        Me.Controls.Add(Me.lbl_desde)
        Me.Controls.Add(Me.cbo_proveedor)
        Me.Controls.Add(Me.lbl_proveedor)
        Me.Controls.Add(Me.cbo_bodega)
        Me.Controls.Add(Me.lbl_bodega)
        Me.Controls.Add(Me.lbl_titulo)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.Sizable
        Me.MinimizeBox = True
        Me.Name = "consulta_recepciones_quimicos"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PRD - Consulta Recepciones Químicos"
        CType(Me.dg_recepciones, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)
        Me.PerformLayout()
    End Sub

    Friend WithEvents lbl_titulo As Label
    Friend WithEvents lbl_bodega As Label
    Friend WithEvents cbo_bodega As ComboBox
    Friend WithEvents lbl_proveedor As Label
    Friend WithEvents cbo_proveedor As ComboBox
    Friend WithEvents lbl_desde As Label
    Friend WithEvents txt_desde As TextBox
    Friend WithEvents lbl_hasta As Label
    Friend WithEvents txt_hasta As TextBox
    Friend WithEvents btn_filtrar As Button
    Friend WithEvents btn_limpiar_filtros As Button
    Friend WithEvents dg_recepciones As DataGridView
    Friend WithEvents lbl_resumen As Label
    Friend WithEvents btn_ver_detalle As Button
    Friend WithEvents btn_actualizar As Button
    Friend WithEvents btn_exportar As Button
    Friend WithEvents btn_cerrar As Button
End Class
