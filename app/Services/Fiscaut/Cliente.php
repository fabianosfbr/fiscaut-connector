<?php

declare(strict_types=1);

namespace App\Services\Fiscaut;

class Cliente
{
    use FiscautConfig;

    public function create(array $data)
    {
        return $this->post('contabil/clientes', $data);
    }
}
