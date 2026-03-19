<?php

namespace App\Console\Commands;

use App\Services\CuotaService;
use App\Services\MatriculaService;
use Illuminate\Console\Command;

class ProcesarCuotas extends Command
{
    protected $signature   = 'cuotas:procesar {--dry-run : Muestra lo que haría sin aplicar cambios}';
    protected $description = 'Vence cuotas expiradas, suspende matrículas con cuotas vencidas y vence matrículas expiradas';

    public function __construct(
        private CuotaService $cuotaService,
        private MatriculaService $matriculaService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $this->info($dryRun ? '[DRY RUN] Simulando procesamiento de cuotas...' : 'Procesando cuotas...');

        if ($dryRun) {
            $this->warn('Modo simulación: no se aplicarán cambios.');
            return self::SUCCESS;
        }

        $resultado = $this->cuotaService->procesarCicloCompleto();
        $vencidas  = $this->matriculaService->vencerMatriculasExpiradas();

        $this->table(
            ['Operación', 'Afectados'],
            [
                ['Cuotas vencidas',          $resultado['cuotas_vencidas']],
                ['Matrículas suspendidas',    $resultado['matriculas_suspendidas']],
                ['Matrículas expiradas',      $vencidas],
            ]
        );

        $this->info('Procesamiento completado.');

        return self::SUCCESS;
    }
}
