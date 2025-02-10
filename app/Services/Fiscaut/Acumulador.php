<?php

declare(strict_types=1);

namespace App\Services\Fiscaut;

class Acumulador
{
    use FiscautConfig;

    public function create(array $data)
    {
        return $this->post('contabil/acumuladores', $data);
    }
}
