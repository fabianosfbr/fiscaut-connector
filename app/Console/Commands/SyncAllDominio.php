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
use App\Jobs\Dominio\SyncClienteDominioJob;
use App\Jobs\Dominio\SyncAcumuladorDominioJob;
use App\Jobs\Dominio\SyncFornecedorDominioJob;
use App\Jobs\Dominio\SyncPlanoDeContaDominioJob;

class SyncAllDominio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-all-dominio';

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
                new SyncPlanoDeContaDominioJob(),
                new SyncFornecedorDominioJob(),
                new SyncClienteDominioJob(),
                new SyncAcumuladorDominioJob(),
            ])->dispatch();
        }
    }
}
