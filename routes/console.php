<?php

declare(strict_types=1);

use App\Models\Configuracao;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('play', function () {
    $api_key = Configuracao::first()?->api_key;

    dd($api_key);
});
