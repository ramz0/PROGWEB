<?php
require_once __DIR__ . '/../tcpdf/tcpdf.php';
require_once __DIR__ . '/../modelo/modelo.php';

class Controlador {
  private $modelo;
    
  public function __construct() {
    $this->modelo = new Modelo();
  }
    
  public function generarPDF($idUsuario, $action = 'download') {
    $datosUsuario = $this->modelo->obtenerDatosUsuario($idUsuario);
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Configuración del documento
    $pdf->SetCreator('AdmPerfil');
    $pdf->SetAuthor('Administrador');
    $pdf->SetTitle('Mensaje Personalizado');
    
    // Agregar página
    $pdf->AddPage();
    
    // Contenido del PDF
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Mensaje Personalizado', 0, 1, 'C');
    $pdf->Ln(10);
    
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 10, 'Estimado/a ' . $datosUsuario['nombre'] . ",\n\n" . 
        $datosUsuario['mensaje_personalizado'], 0, 'L');
    
    // Determinar el tipo de salida según la acción
    switch($action) {
        case 'preview':
            // Mostrar en navegador (In-line)
            $pdf->Output('mensaje_personalizado.pdf', 'I');
            break;
        case 'download':
        default:
            // Forzar descarga (Download)
            $pdf->Output('mensaje_personalizado.pdf', 'D');
            break;
    }
    exit;
  }
}