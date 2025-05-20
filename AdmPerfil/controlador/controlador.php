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
    
    // Datos de ejemplo para la gráfica (ajusta con tus datos reales)
    $datosGrafica = [
        'labels' => ['Progreso A', 'Progreso B', 'Progreso C'],
        'valores' => [35, 25, 40],
        'colores' => ['#FF6384', '#36A2EB', '#FFCE56']
    ];
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Configuración del documento
    $pdf->SetCreator('AdmPerfil');
    $pdf->SetAuthor('Administrador');
    $pdf->SetTitle('Reporte Personalizado');
    $pdf->SetMargins(15, 25, 15);
    $pdf->SetHeaderMargin(10);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 25);
    
    // Encabezado
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->Cell(0, 10, 'REPORTE DE PERFIL', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Información del usuario
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 10, 'Nombre:', 0, 0);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, $datosUsuario['nombre'], 0, 1);
    
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 10, 'Fecha:', 0, 0);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, date('d/m/Y'), 0, 1);
    $pdf->Ln(15);
    
    // Gráfico de pastel
    $this->agregarGraficoPastel($pdf, $datosGrafica);
    $pdf->Ln(20);
    
    // Datos adicionales en tabla
    $this->agregarTablaDatos($pdf, $datosUsuario);
    
    // Pie de página
    $pdf->SetY(-15);
    $pdf->SetFont('helvetica', 'I', 8);
    $pdf->Cell(0, 10, 'Página '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, 0, 'C');
    
    // Salida
    $pdf->Output('reporte_perfil.pdf', $action === 'preview' ? 'I' : 'D');
    exit;
  }

private function dibujarGraficoSimple($pdf, $chart) {
    $x = 15;
    $y = $pdf->GetY();
    
    // Dibujar gráfico de pastel básico
    $total = array_sum($chart['data']);
    $radius = min($chart['width'], $chart['height']) / 2;
    $centerX = $x + $radius;
    $centerY = $y + $radius;
    
    $startAngle = 0;
    foreach ($chart['data'] as $i => $value) {
        $angle = ($value / $total) * 360;
        $pdf->SetFillColor($this->hex2rgb($chart['colors'][$i]));
        $pdf->PieSector(
            $centerX, $centerY, $radius,
            $startAngle, $startAngle + $angle,
            'F', false, 0, 2
        );
        $startAngle += $angle;
    }
    
    // Leyenda
    $pdf->SetY($y + $chart['height'] + 10);
    foreach ($datos['labels'] as $i => $label) {
      $pdf->SetFillColor(...$this->hex2rgb($datos['colores'][$i % count($datos['colores'])]));
      $pdf->Rect($x, $pdf->GetY(), 5, 5, 'F');
      $pdf->SetX($x + 7);
      $pdf->Cell(0, 5, sprintf('%s: %.1f%%', $label, $datos['valores'][$i]));
      $pdf->Ln(6);
    }
}

  private function agregarTablaDatos($pdf, $datos) {
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Detalles Adicionales:', 0, 1);
    $pdf->Ln(5);
    
    // Configurar colores
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetTextColor(0);
    $pdf->SetFont('helvetica', 'B', 10);
    
    // Cabecera de tabla
    $pdf->Cell(60, 10, 'Indicador', 1, 0, 'C', 1);
    $pdf->Cell(60, 10, 'Valor', 1, 0, 'C', 1);
    $pdf->Cell(60, 10, 'Comentarios', 1, 1, 'C', 1);
    
    // Datos de la tabla
    $pdf->SetFont('helvetica', '', 10);
    $fill = false;
    
    // Ejemplo de datos - reemplaza con tus datos reales
    $detalles = [
        ['Progreso General', '75%', 'Sobre el objetivo'],
        ['Tareas Completadas', '28/35', '80% efectividad'],
        ['Puntaje', '8.5/10', 'Excelente desempeño']
    ];
    
    foreach ($detalles as $fila) {
        $pdf->Cell(60, 10, $fila[0], 'LR', 0, 'L', $fill);
        $pdf->Cell(60, 10, $fila[1], 'LR', 0, 'C', $fill);
        $pdf->Cell(60, 10, $fila[2], 'LR', 1, 'L', $fill);
        $fill = !$fill;
    }
    
    // Cierre de tabla
    $pdf->Cell(180, 0, '', 'T');
  }


  public function generarReportePerfiles($action = 'download') {
    // Obtener datos
    $estadisticas = $this->modelo->obtenerEstadisticasPerfiles();

    $datosGrafica = [
        'labels' => [],
        'valores' => [],
        'colores' => []
    ];

    foreach ($estadisticas as $est) {
        $datosGrafica['labels'][] = $est['perfil'];
        $datosGrafica['valores'][] = $est['porcentaje'];
        $datosGrafica['colores'][] = $est['color']; // Usa el color asignado
    }
    
    // Crear PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Configuración
    $pdf->SetCreator('AdmPerfil');
    $pdf->SetAuthor('Sistema Académico');
    $pdf->SetTitle('Reporte de Perfiles');
    $pdf->SetMargins(15, 25, 15);
    
    // Encabezado
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'REPORTE DE PERFILES DE USUARIO', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Gráfico de pastel
    $this->agregarGraficoPastel($pdf, $datosGrafica);
    $pdf->Ln(15);
    
    // Tabla de estadísticas
    $this->agregarTablaEstadisticas($pdf, $estadisticas);
    
    // Pie de página
    $pdf->SetY(-15);
    $pdf->SetFont('helvetica', 'I', 8);
    $pdf->Cell(0, 10, 'Generado el ' . date('d/m/Y H:i'), 0, 0, 'C');
    
    // Salida
    $pdf->Output('reporte_perfiles.pdf', $action === 'preview' ? 'I' : 'D');
}

  private function agregarGraficoPastel($pdf, $datos) {
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->Cell(0, 10, 'Distribución de Perfiles (%):', 0, 1);
      $pdf->Ln(5);
      
      // Coordenadas para el gráfico
      $x = $pdf->GetX();
      $y = $pdf->GetY();
      $radius = 40;
      $centerX = $x + $radius + 10;
      $centerY = $y + $radius + 10;
      
      // Dibujar gráfico de pastel
      $total = array_sum($datos['valores']);
      $startAngle = 0;
      
      foreach ($datos['valores'] as $i => $value) {
        $angle = ($value / $total) * 360;
        $pdf->SetFillColor(...$this->hex2rgb($datos['colores'][$i % count($datos['colores'])]));
        $pdf->PieSector(
            $centerX, $centerY, $radius,
            $startAngle, $startAngle + $angle,
            'F', false, 0, 2
        );
        $startAngle += $angle;
      }
      
      // Leyenda
      $pdf->SetY($y + $radius * 2 + 20);
      foreach ($datos['labels'] as $i => $label) {
        $pdf->SetFillColor(...$this->hex2rgb($datos['colores'][$i % count($datos['colores'])]));
        $pdf->Rect($x, $pdf->GetY(), 5, 5, 'F');
        $pdf->SetX($x + 7);
        $pdf->Cell(0, 5, sprintf('%s: %.1f%%', $label, $datos['valores'][$i]));
        $pdf->Ln(6);
      }
  }

  private function agregarTablaEstadisticas($pdf, $datos) {
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->Cell(0, 10, 'Detalle Estadístico:', 0, 1);
      $pdf->Ln(5);
      
      // Cabecera de tabla
      $pdf->SetFillColor(220, 220, 220);
      $pdf->SetFont('helvetica', 'B', 10);
      
      $pdf->Cell(80, 10, 'Perfil', 1, 0, 'C', 1);
      $pdf->Cell(50, 10, 'Cantidad', 1, 0, 'C', 1);
      $pdf->Cell(50, 10, 'Porcentaje', 1, 1, 'C', 1);
      
      // Datos
      $pdf->SetFont('helvetica', '', 10);
      $fill = false;
      
      foreach ($datos as $fila) {
          $pdf->Cell(80, 10, $fila['perfil'], 'LR', 0, 'L', $fill);
          $pdf->Cell(50, 10, $fila['cantidad'], 'LR', 0, 'C', $fill);
          $pdf->Cell(50, 10, $fila['porcentaje'] . '%', 'LR', 1, 'C', $fill);
          $fill = !$fill;
      }
      
      // Cierre de tabla
      $pdf->Cell(180, 0, '', 'T');
  }

  private function hex2rgb($hex) {
      $hex = str_replace('#', '', $hex);
      return array(
          hexdec(substr($hex, 0, 2)),
          hexdec(substr($hex, 2, 2)),
          hexdec(substr($hex, 4, 2))
      );
  }
}