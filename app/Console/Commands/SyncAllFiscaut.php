<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Console\Command;
use App\Services\FiscautService;
use App\Jobs\SyncClienteFiscautJob;
use Illuminate\Support\Facades\Bus;
use App\Jobs\SyncFornecedorFiscautJob;
use App\Jobs\SyncPlanoDeContaFiscautJob;

class SyncAllFiscaut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-all-fiscaut';

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
            $this->info('Sincronizando clientes da empresa: '.$empresa->nome_emp);

            Bus::chain([
                new SyncPlanoDeContaFiscautJob($empresa),
                new SyncClienteFiscautJob($empresa),
                new SyncFornecedorFiscautJob($empresa),
            ])->dispatch();
        }
    }
}
