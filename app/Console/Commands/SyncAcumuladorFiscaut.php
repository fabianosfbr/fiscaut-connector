<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Acumulador;
use Illuminate\Console\Command;
use App\Services\FiscautService;
use App\Jobs\SyncAcumuladorFiscautJob;

class SyncAcumuladorFiscaut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-acumulador-fiscaut';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = app(FiscautService::class);

        $empresas = Empresa::where('sync', true)->get();

        foreach ($empresas as $empresa) {
            $this->info('Sincronizando acumuladores da empresa: '.$empresa->nome_emp);

            Acumulador::where('codi_emp', $empresa->codi_emp)
            ->where('fiscaut_sync', false)
            ->chunk(500, function ($acumuladores) use ($service, $empresa) {
                foreach ($acumuladores as $acumulador) {
                    $params = [
                        'cnpj_empresa' => $empresa->cgce_emp,
                        'codi_acu' => $acumulador->codi_acu,
                        'nome_acu' => $acumulador->nome_acu,
                    ];

                    $response = $service->acumulador()->create($params);

                    if (isset($response['status']) && $response['status'] == true) {
                        $acumulador->fiscaut_sync = true;
                        $acumulador->saveQuietly();
                    }

                    $params['cnpj_nome'] = $empresa->razao_emp;

                    dump($params);
                }
            });
        }
    }
}
