<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Tabela;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use PDO;
use PDOException;

class ImportTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:tables';

    protected $description = 'Import tables from Dominio ODBC to Fiscaut Connector';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Connecting to ODBC database...');

        $config = Config::get('database.connections.odbc');
        $tablePrefix = $config['database'];

        try {
            $pdo = new PDO("odbc:{$config['dsn']}", $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta para obter os dados da tabela específica
            $query = $pdo->query("SELECT * FROM SYS.SYSTABLE WHERE table_type = 'BASE'");
            if ($query) {
                foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $this->info('Tabela: '.$row['table_name'].' Nº de registros: '.$row['count']);

                    Tabela::updateOrCreate(
                        ['table_name' => $row['table_name']],
                        ['table_name' => $tablePrefix.'.'.$row['table_name'], 'count_rows' => $row['count'], 'sync' => false]
                    );
                }
            } else {
                echo 'Erro ao executar consulta SYS.SYSTABLE: '.$pdo->errorInfo()[2]."\n";
            }

            // Fecha a conexão
            $pdo = null;
        } catch (PDOException $e) {
            echo 'Erro ao conectar via ODBC: '.$e->getMessage();
        }
    }
}
