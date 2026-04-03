<?php

namespace App\Exports;

use App\Models\Clase;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReporteClaseExport implements FromArray, WithTitle, WithEvents
{
    private Clase $clase;
    private int   $dataStart = 11;
    private int   $lastRow   = 11;

    public function __construct(Clase $clase)
    {
        $this->clase = $clase->load(['curso', 'asistencias.alumno.user']);
    }

    public function title(): string { return 'Reporte Clase'; }

    public function array(): array
    {
        $asistencias = $this->clase->asistencias->sortBy('alumno.user.name');
        $total    = $asistencias->count();
        $presente = $asistencias->where('estado', 'presente')->count();
        $tardanza = $asistencias->where('estado', 'tardanza')->count();
        $ausente  = $asistencias->where('estado', 'ausente')->count();
        $justif   = $asistencias->where('estado', 'justificado')->count();
        $pct      = $total > 0 ? round((($presente + $tardanza) / $total) * 100, 1) : 0;

        $rows = [
            ['ACADEMIA NUEVA ERA — REPORTE DE ASISTENCIA POR CLASE', '', '', '', '', ''],
            ['', '', '', '', '', ''],
            ['CLASE',    $this->clase->titulo,                     '', 'CURSO',  $this->clase->curso->nombre, ''],
            ['FECHA',    $this->clase->fecha->format('d/m/Y'),     '', 'HORA',   $this->clase->fecha->format('H:i'), ''],
            ['ZOOM',     $this->clase->zoom_link ?? '—',           '', 'GRABADA', $this->clase->grabada ? 'Sí' : 'No', ''],
            ['GENERADO', now()->format('d/m/Y H:i'),              '', 'TOTAL ALUMNOS', $total, ''],
            ['', '', '', '', '', ''],
            // Stats
            ['Presentes', $presente, 'Tardanzas', $tardanza, 'Justificados', $justif],
            ['Ausentes',  $ausente,  '% Asistencia', "{$pct}%", '', ''],
            ['', '', '', '', '', ''],
            // Header
            ['#', 'ALUMNO', 'DNI', 'ESTADO', 'HORA INGRESO', 'OBSERVACIÓN'],
        ];

        $i = 1;
        foreach ($asistencias as $asis) {
            $alumno = $asis->alumno;
            $rows[] = [
                $i++,
                $alumno?->nombreCompleto() ?? '—',
                $alumno?->dni ?? '—',
                strtoupper($asis->estado),
                $asis->hora_ingreso ? $asis->hora_ingreso->format('H:i') : '—',
                $asis->observacion ?? '—',
            ];
        }

        $this->lastRow = count($rows);
        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Título
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '082B59']],
                    'font'      => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(32);

                // Info clase (filas 3-6)
                $sheet->getStyle('A3:F6')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EBF5FB']],
                ]);
                foreach ([3, 4, 5, 6] as $row) {
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                    $sheet->getStyle("D{$row}")->getFont()->setBold(true);
                }

                // Stats (filas 8-9)
                $sheet->getStyle('A8:F9')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D6EAF8']],
                ]);
                foreach ([8, 9] as $row) {
                    foreach (['A', 'C', 'E'] as $col) {
                        $sheet->getStyle("{$col}{$row}")->getFont()->setBold(true);
                    }
                }

                // Header tabla (fila 11)
                $sheet->getStyle('A11:F11')->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '082B59']],
                    'font'      => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Datos
                $dataStart = 12;
                for ($r = $dataStart; $r <= $this->lastRow; $r++) {
                    $estado = strtolower($sheet->getCell("D{$r}")->getValue());
                    $color  = match ($estado) {
                        'presente'    => 'D1FAE5',
                        'tardanza'    => 'FEF3C7',
                        'justificado' => 'DBEAFE',
                        'ausente'     => 'FEE2E2',
                        default       => 'F9FAFB',
                    };
                    $sheet->getStyle("D{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $bgCebra = ($r % 2 === 0) ? 'FFFFFF' : 'F8FBFF';
                    foreach (['A', 'B', 'C', 'E', 'F'] as $col) {
                        $sheet->getStyle("{$col}{$r}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgCebra]],
                        ]);
                    }
                }

                // Bordes
                if ($this->lastRow >= $dataStart) {
                    $sheet->getStyle("A11:F{$this->lastRow}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                    ]);
                }

                // Anchos
                $sheet->getColumnDimension('A')->setWidth(6);
                $sheet->getColumnDimension('B')->setWidth(36);
                $sheet->getColumnDimension('C')->setWidth(14);
                $sheet->getColumnDimension('D')->setWidth(16);
                $sheet->getColumnDimension('E')->setWidth(14);
                $sheet->getColumnDimension('F')->setWidth(30);

                foreach (['A', 'C', 'D', 'E'] as $col) {
                    $sheet->getStyle("{$col}1:{$col}{$this->lastRow}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                $sheet->freezePane('A12');
            },
        ];
    }
}
