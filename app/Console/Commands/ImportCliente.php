<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\PlanoDeConta;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class ImportCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cliente';
    protected $description = 'Import clientes from Dominio ODBC to Fiscaut Connector';
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $tableName = 'bethadba.efclientes';

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();


        foreach ($empresas as $empresa) {

            $rows = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp)
                ->orderBy('codi_cli', 'desc')
                ->get();


            foreach ($rows as $key => $row) {


                $plan = PlanoDeConta::where('codi_emp', $row->codi_emp)
                    ->where('nome_cta', $row->nome_cli)
                    ->first();

                $row->nome_cli = removeCaracteresEspeciais($row->nome_cli);

                $this->info('Cliente: ' . $row->nome_cli . ' CNPJ/CPF: ' . $row->cgce_cli);


                Cliente::updateOrCreate(
                    [
                        'codi_emp' => $row->codi_emp,
                        'codi_cli' => $row->codi_cli,
                    ],
                    [
                        'codi_emp' => $row->codi_emp,
                        'codi_cli' => $row->codi_cli,
                        'nome_cli' => $row->nome_cli,
                        'cgce_cli' => $row->cgce_cli,
                        'codi_cta' => $plan?->codi_cta,
                    ]
                );
            }
        }
    }
}
