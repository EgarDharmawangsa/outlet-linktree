<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\OutletApiService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'uuid' => Str::uuid(),
            'name' => 'Super Admin',
            'email' => 'superadmin@center',
            'password' => bcrypt('spa12345'),
            'is_super_admin' => true,
            'uuid_outlet' => null
        ]);

        $outletApiService = new OutletApiService();
        $outlet_users = $outletApiService->getOutlets();

        foreach ($outlet_users as $outlet_user) {
            User::create([
                'uuid' => Str::uuid(),
                'name' => $outlet_user['nama'],
                'email' => Str::slug($outlet_user['nama'], '') . '@outlet',
                'uuid_outlet' => $outlet_user['id'],
                'password' => Hash::make('Apotekku'),
                'is_super_admin' => false,
            ]);
        }
    }
}
