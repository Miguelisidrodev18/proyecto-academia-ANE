<?php

namespace App\Exports;

use App\Models\Curso;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReporteCursoResumenSheet implements FromArray, WithTitle, WithEvents
{
    private Curso $curso;
    private int   $lastRow = 0;

    public function __construct(Curso $curso)
    {
        $this->curso = $curso;
    }

    public function title(): string { return 'Resumen por Clase'; }

    public function array(): array
    {
        $clases = $this->curso->clases;

        $rows = [
            ['ACADEMIA NUEVA ERA — RESUMEN DEL CURSO', '', '', '', '', ''],
            ['', '', '', '', '', ''],
            ['CURSO',     $this->curso->nombre,          '', 'NIVEL', ucfirst($this->curso->nivel), ''],
            ['TIPO',      ucfirst($this->curso->tipo ?? '—'), '', 'TOTAL CLASES', $clases->count(), ''],
            ['GENERADO',  now()->format('d/m/Y H:i'),   '', '', '', ''],
            ['', '', '', '', '', ''],
            ['#', 'FECHA', 'CLASE', 'PRESENTES', 'AUSENTES', '% ASISTENCIA'],
        ];

        $i = 1;
        foreach ($clases as $clase) {
            $asistencias = $clase->asistencias;
            $total    = $asistencias->count();
            $presente = $asistencias->where('estado', 'presente')->count();
            $tardanza = $asistencias->where('estado', 'tardanza')->count();
            $ausente  = $asistencias->whereIn('estado', ['ausente', 'justificado'])->count();
            $pct      = $total > 0 ? round((($presente + $tardanza) / $total) * 100, 1) : 0;

            $rows[] = [$i++, $clase->fecha->format('d/m/Y'), $clase->titulo, $presente + $tardanza, $ausente, "{$pct}%"];
        }

        $this->lastRow = count($rows);
        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '082B59']],
                    'font'      => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(32);

                $sheet->getStyle('A3:F5')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EBF5FB']],
                ]);
                foreach ([3, 4, 5] as $row) {
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                    $sheet->getStyle("D{$row}")->getFont()->setBold(true);
                }

                $sheet->getStyle('A7:F7')->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '30A9D9']],
                    'font'      => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $dataStart = 8;
                for ($r = $dataStart; $r <= $this->lastRow; $r++) {
                    $pct = (float) str_replace('%', '', $sheet->getCell("F{$r}")->getValue());
                    $color = $pct >= 80 ? 'D1FAE5' : ($pct >= 60 ? 'FEF3C7' : 'FEE2E2');

                    $sheet->getStyle("F{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $bg = ($r % 2 === 0) ? 'FFFFFF' : 'F8FBFF';
                    $sheet->getStyle("A{$r}:E{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                    ]);
                }

                if ($this->lastRow >= $dataStart) {
                    $sheet->getStyle("A7:F{$this->lastRow}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                    ]);
                }

                $sheet->getColumnDimension('A')->setWidth(6);
                $sheet->getColumnDimension('B')->setWidth(14);
                $sheet->getColumnDimension('C')->setWidth(40);
                $sheet->getColumnDimension('D')->setWidth(14);
                $sheet->getColumnDimension('E')->setWidth(14);
                $sheet->getColumnDimension('F')->setWidth(16);

                $sheet->freezePane('A8');
            },
        ];
    }
}
