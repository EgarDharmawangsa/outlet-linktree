<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\OutletLink;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OutletApiService
{
    public function getOutlets()
    {
        return collect(
            Cache::remember('outlets', now()->addMinutes(10), function () {
                try {
                    $response = Http::withHeaders([
                        'x-api-key' => env('API_KEY')
                    ])->get('http://slipgaji.apotekkuapp.com/api/json/cabang?fields[]=id&fields[]=nama&fields[]=alamat&fields[]=no_hp');

                    if (!$response->successful()) {
                        return [];
                    }

                    return collect($response->json())->map(function ($outlet) {
                        $outlet['slug'] = Str::slug($outlet['nama']);
                        return $outlet;
                    })->toArray();

                } catch (\Throwable $e) {
                    report($e);
                    return [];
                }
            })
        );
    }

    public function syncOutlets()
    {
        Cache::forget('outlets');

        $outlets = $this->getOutlets();

        $active_uuids = $outlets->pluck('id')->toArray();

        OutletLink::whereNotIn('uuid_outlet', $active_uuids)->delete();
        User::whereNotIn('uuid_outlet', $active_uuids)->delete();

        $rows = [];
        foreach ($outlets as $outlet) {
            $rows[] = [
                'uuid' => Str::uuid(),
                'uuid_outlet' => $outlet['id'],
                'name' => $outlet['nama'],
                'email' => Str::slug($outlet['nama'], '') . '@outlet',
                'password' => Hash::make('Apotekku'),
                'is_super_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        User::upsert(
            $rows,
            ['uuid_outlet'],
            ['name', 'email', 'is_super_admin', 'updated_at']
        );

        return $outlets;
    }

    public function getOutletById(string $uuid_outlet)
    {
        $outlets = $this->getOutlets();

        if ($outlets->isEmpty()) {
            return collect();
        }

        $outlet = $outlets->firstWhere('id', $uuid_outlet);

        return $outlet;
    }
}
