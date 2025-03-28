<?php

namespace Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class DataExtractionService {
    const DEFAULT_QUERY_PARAMS = [
        'limit' => 500,
        'dateFrom' => '0001-01-01'
    ];

    private function sendRequest(string $url): ?array
    {
        $response = Http::get($url);
        if ($response->successful()) {
            return $response->json()['data'];
        }
        echo ('Error status code: ' . $response->status() . PHP_EOL);
        return null;
    }

    private function prepareUrl(string $path, array $queryParams): string
    {
        $protocol = env('DATA_API_PROTOCOL', '');
        $host = env('DATA_API_HOST', '');
        $port = env('DATA_API_PORT', '');
        $key = env('DATA_API_KEY', '');

        $url = "$protocol://$host:$port/api/$path?key=$key";
        foreach ($queryParams as $key => $value) {
            $url .= '&' . $key . '=' . $value;
        }
        return $url;
    }

    private function prepareQueryParams(array $params, int $page = 1): array
    {
        $queryParams = ['page' => $page];
        $queryParams = array_merge($queryParams, self::DEFAULT_QUERY_PARAMS);
        return array_merge($queryParams, $params);
    }

    public function getData(string $path, array $params, int $page = 1): ?array
    {
        $url = $this->prepareUrl($path, $this->prepareQueryParams($params, $page));

        if (RateLimiter::tooManyAttempts('getData', 59))
        {
            sleep(RateLimiter::availableIn('getData'));
        }

        $data = $this->sendRequest($url);
        RateLimiter::increment('getData');
        return $data;
    }
}
