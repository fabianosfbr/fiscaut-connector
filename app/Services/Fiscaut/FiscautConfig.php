<?php

namespace App\Services\Fiscaut;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;


trait FiscautConfig
{
    public function __construct(
        protected ?PendingRequest $http = null,
    ) {

        $token = config("fiscaut.token");
        $url = config("fiscaut.url");

        $this->http = Http::withToken($token)
            ->withHeader('Accept', 'application/json')
            ->baseUrl($url);
    }


    public function get(string $url)
    {

        try {
            return $this->http
                ->get($url)
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    public function post(string $url, array $params = null)
    {
        try {
            return $this->http
                ->post($url, $params)
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    public function delete(string $url)
    {
        try {
            return $this->http
                ->delete($url)
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    public function put(string $url, array $params)
    {
        try {
            return $this->http
                ->put($url, $params)
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    public function patch(string $url)
    {
        try {
            return $this->http
                ->patch($url)
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }
}
