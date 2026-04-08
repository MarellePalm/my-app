<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class ExternalApiController extends Controller
{
    public function __invoke(string $name)
    {
        $apiData = match ($name) {
        'ralf' => (function () {
    $response = Http::get('https://ralfiharjutus.ta24siim.itmajakas.ee/api/movies')->json();

    return [
        'data' => $response['data'] ?? [],
    ];
})(),

            'plants' => [
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Roos',
                        'description' => 'Punane lill',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Tulip',
                        'description' => 'Kevadine lill',
                    ],
                ],
            ],

            default => [
                'data' => [],
            ],
        };

        return Inertia::render('ExternalApi', [
            'apiData' => $apiData,
        ]);
    }
}