<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            [
                'code' => '3171',
                'province_name' => 'DKI Jakarta',
                'name' => 'Kota Jakarta Pusat',
                'latitude' => -6.1805113,
                'longitude' => 106.8283831,
            ],
            [
                'code' => '3174',
                'province_name' => 'DKI Jakarta',
                'name' => 'Kota Jakarta Selatan',
                'latitude' => -6.2614927,
                'longitude' => 106.8105998,
            ],
            [
                'code' => '3273',
                'province_name' => 'Jawa Barat',
                'name' => 'Kota Bandung',
                'latitude' => -6.9174639,
                'longitude' => 107.6191228,
            ],
            [
                'code' => '3204',
                'province_name' => 'Jawa Barat',
                'name' => 'Kabupaten Bandung',
                'latitude' => -7.0252531,
                'longitude' => 107.5197600,
            ],
            [
                'code' => '3374',
                'province_name' => 'Jawa Tengah',
                'name' => 'Kota Semarang',
                'latitude' => -6.9666670,
                'longitude' => 110.4166640,
            ],
            [
                'code' => '3471',
                'province_name' => 'DI Yogyakarta',
                'name' => 'Kota Yogyakarta',
                'latitude' => -7.7955798,
                'longitude' => 110.3694896,
            ],
            [
                'code' => '3578',
                'province_name' => 'Jawa Timur',
                'name' => 'Kota Surabaya',
                'latitude' => -7.2574719,
                'longitude' => 112.7520883,
            ],
        ];

        foreach ($regions as $region) {
            Region::updateOrCreate(
                ['code' => $region['code']],
                $region
            );
        }
    }
}
