<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Region;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regionMap = Region::pluck('id', 'code');

        $facilities = [
            // DKI Jakarta
            [
                'region_code' => '3171',
                'code' => 'UDD-DKI-01',
                'name' => 'UDD PMI Provinsi DKI Jakarta',
                'type' => Facility::TYPE_UDD,
                'address' => 'Jl. Kramat Raya No. 47, Senen, Kota Jakarta Pusat',
                'phone' => '(021) 3906666',
                'operating_hours' => '24 Jam',
                'latitude' => -6.1887250,
                'longitude' => 106.8445740,
            ],
            [
                'region_code' => '3171',
                'code' => 'RS-JKT-01',
                'name' => 'RSUP Nasional Dr. Cipto Mangunkusumo (RSCM)',
                'type' => Facility::TYPE_HOSPITAL,
                'address' => 'Jl. Pangeran Diponegoro No. 71, Senen, Kota Jakarta Pusat',
                'phone' => '(021) 1500135',
                'operating_hours' => '24 Jam',
                'latitude' => -6.1973850,
                'longitude' => 106.8470310,
            ],
            [
                'region_code' => '3174',
                'code' => 'RS-JKT-02',
                'name' => 'RSUP Fatmawati Jakarta',
                'type' => Facility::TYPE_HOSPITAL,
                'address' => 'Jl. RS. Fatmawati Raya No. 4, Cilandak, Kota Jakarta Selatan',
                'phone' => '(021) 7501524',
                'operating_hours' => '24 Jam',
                'latitude' => -6.2949570,
                'longitude' => 106.7955790,
            ],

            // Bandung Raya
            [
                'region_code' => '3273',
                'code' => 'UDD-BDG-01',
                'name' => 'UDD PMI Kota Bandung',
                'type' => Facility::TYPE_UDD,
                'address' => 'Jl. Aceh No. 79, Cihapit, Kec. Bandung Wetan, Kota Bandung',
                'phone' => '(022) 4207052',
                'operating_hours' => '24 Jam',
                'latitude' => -6.9089260,
                'longitude' => 107.6213590,
            ],
            [
                'region_code' => '3273',
                'code' => 'RS-BDG-01',
                'name' => 'RSUP Dr. Hasan Sadikin (RSHS) Bandung',
                'type' => Facility::TYPE_HOSPITAL,
                'address' => 'Jl. Pasteur No. 38, Pasteur, Kec. Sukajadi, Kota Bandung',
                'phone' => '(022) 2034953',
                'operating_hours' => '24 Jam',
                'latitude' => -6.8977970,
                'longitude' => 107.5988290,
            ],
            [
                'region_code' => '3273',
                'code' => 'RS-BDG-02',
                'name' => 'RS Santo Borromeus Bandung',
                'type' => Facility::TYPE_HOSPITAL,
                'address' => 'Jl. Ir. H. Juanda No. 100, Lebakgede, Kec. Coblong, Kota Bandung',
                'phone' => '(022) 2552000',
                'operating_hours' => '24 Jam',
                'latitude' => -6.8940220,
                'longitude' => 107.6135410,
            ],
            [
                'region_code' => '3204',
                'code' => 'RS-BDG-03',
                'name' => 'RSUD Otista Soreang Kabupaten Bandung',
                'type' => Facility::TYPE_HOSPITAL,
                'address' => 'Jl. Gading Tutuka, Cingcin, Kec. Soreang, Kabupaten Bandung',
                'phone' => '(022) 5891355',
                'operating_hours' => '24 Jam',
                'latitude' => -7.0221640,
                'longitude' => 107.5327110,
            ],

            // Semarang
            [
                'region_code' => '3374',
                'code' => 'UDD-SMG-01',
                'name' => 'UDD PMI Kota Semarang',
                'type' => Facility::TYPE_UDD,
                'address' => 'Jl. Mgr Sugiyopranoto No. 31, Bulustalan, Kota Semarang',
                'phone' => '(024) 3515050',
                'operating_hours' => '24 Jam',
                'latitude' => -6.9847640,
                'longitude' => 110.4084880,
            ],
            [
                'region_code' => '3374',
                'code' => 'RS-SMG-01',
                'name' => 'RSUP Dr. Kariadi Semarang',
                'type' => Facility::TYPE_HOSPITAL,
                'address' => 'Jl. DR. Sutomo No. 16, Randusari, Kota Semarang',
                'phone' => '(024) 8413476',
                'operating_hours' => '24 Jam',
                'latitude' => -6.9942580,
                'longitude' => 110.4073680,
            ],

            // Yogyakarta
            [
                'region_code' => '3471',
                'code' => 'UDD-YGY-01',
                'name' => 'UDD PMI Kota Yogyakarta',
                'type' => Facility::TYPE_UDD,
                'address' => 'Jl. Tegalgendu No. 25, Prenggan, Kotagede, Kota Yogyakarta',
                'phone' => '(0274) 372176',
                'operating_hours' => '24 Jam',
                'latitude' => -7.8226180,
                'longitude' => 110.3932150,
            ],
            [
                'region_code' => '3471',
                'code' => 'RS-YGY-01',
                'name' => 'RSUP Dr. Sardjito Yogyakarta',
                'type' => Facility::TYPE_HOSPITAL,
                'address' => 'Jl. Kesehatan No. 1, Sekip, Sinduadi, Yogyakarta',
                'phone' => '(0274) 587333',
                'operating_hours' => '24 Jam',
                'latitude' => -7.7686110,
                'longitude' => 110.3736110,
            ],

            // Surabaya
            [
                'region_code' => '3578',
                'code' => 'UDD-SBY-01',
                'name' => 'UDD PMI Kota Surabaya',
                'type' => Facility::TYPE_UDD,
                'address' => 'Jl. Embong Ploso No. 7-15, Embong Kaliasin, Genteng, Kota Surabaya',
                'phone' => '(031) 5313289',
                'operating_hours' => '24 Jam',
                'latitude' => -7.2665890,
                'longitude' => 112.7476440,
            ],
            [
                'region_code' => '3578',
                'code' => 'RS-SBY-01',
                'name' => 'RSUD Dr. Soetomo Surabaya',
                'type' => Facility::TYPE_HOSPITAL,
                'address' => 'Jl. Mayjen Prof. Dr. Moestopo No. 6-8, Airlangga, Gubeng, Kota Surabaya',
                'phone' => '(031) 5501078',
                'operating_hours' => '24 Jam',
                'latitude' => -7.2682060,
                'longitude' => 112.7582460,
            ],
        ];

        foreach ($facilities as $item) {
            $regionId = $regionMap[$item['region_code']] ?? null;
            if (! $regionId) {
                continue;
            }

            unset($item['region_code']);
            $item['region_id'] = $regionId;
            $item['is_active'] = true;

            Facility::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
