<?php

declare(strict_types=1);

namespace App\Services\Fiscaut;

class PlanoDeConta
{
    use FiscautConfig;

    public function create(array $data)
    {
        return $this->post('contabil/plano-de-contas', $data);
    }
}
