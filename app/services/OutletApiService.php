<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OutletApiService
{
    public function getOutlets()
    {
        $cache = collect(
            Cache::remember('outlets', now()->addMinutes(10), function () {
                $response = Http::withHeaders([
                    'x-api-key' => env('API_KEY')
                ])->get('http://slipgaji.apotekkuapp.com/api/json/cabang?fields[]=id&fields[]=nama&fields[]=alamat&fields[]=no_hp');

                if (!$response->successful()) {
                    return [];
                }

                $outlets = collect($response->json())->map(function ($outlet) {
                    $outlet['slug'] = Str::slug($outlet['nama']);

                    return $outlet;
                });

                return $outlets->toArray();
            })
        );

        return $cache;
    }

    public function syncOutlets()
    {
        Cache::forget('outlets');
        return $this->getOutlets();
    }

    public function getOutletById(String $uuid_outlet)
    {
        $outlets = $this->getOutlets();

        if ($outlets->isEmpty()) {
            return collect();
        }

        $outlet = $outlets->firstWhere('id', $uuid_outlet);

        return $outlet;
    }
}
