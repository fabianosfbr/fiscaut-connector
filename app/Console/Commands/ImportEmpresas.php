<?php

namespace App\Console\Commands;

use PDO;
use Exception;
use PDOException;
use App\Models\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


class ImportEmpresas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:empresas';
    protected $description = 'Import empresas from Dominio ODBC to Fiscaut Connector';
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $tableName = 'bethadba.geempre';

        $config = Config::get('database.connections.odbc');

        try {
            $pdo = new PDO("odbc:{$config['dsn']}", $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            // Consulta para obter os dados da tabela específica
            $query = $pdo->query("SELECT * FROM $tableName");
            if ($query) {


                foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {


                  //  $row['razao_emp'] = $this->make_utf8($row['razao_emp']);

                    $this->info(print_r($row));

                    $this->info('Empresa: ' . $row['razao_emp'] . ' Código: ' . $row['codi_emp']);

                    Empresa::updateOrCreate(
                        ['codi_emp' => $row['codi_emp']],
                        $row
                    );
                }
            } else {
                echo "Erro ao executar consulta na tabela $tableName: " . $pdo->errorInfo()[2] . "\n";
            }

            // Fecha a conexão
            $pdo = null;
        } catch (PDOException $e) {
            echo "Erro ao conectar via ODBC: " . $e->getMessage();
        }
    }

    private function make_utf8(string $string)
    {
        // Test it and see if it is UTF-8 or not
        $utf8 = \mb_detect_encoding($string, ["UTF-8"], true);

        if ($utf8 !== false) {
            return $string;
        }

        // From now on, it is a safe assumption that $string is NOT UTF-8-encoded

        // The detection strictness (i.e. third parameter) is up to you
        // You may set it to false to return the closest matching encoding
        $encoding = \mb_detect_encoding($string, mb_detect_order(), true);

        if ($encoding === false) {
            throw new \RuntimeException("String encoding cannot be detected");
        }

        return \mb_convert_encoding($string, "UTF-8", $encoding);
    }

}
