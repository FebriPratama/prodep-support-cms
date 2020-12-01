<?php

use App\Models\API\SalesOrderApi;

class SalesOrderSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake() {

        SalesOrderApi::firstOrCreate([
            'so_type' => 'plaza',
            'so_product_name' => 'Mesin Cuci Portable',
            'so_total' => 300000.00,
            'user_id' => '2583164c-edcc-45d9-8391-51c76b767258',
            'so_status' => 'succeeded'
        ]);

        SalesOrderApi::firstOrCreate([
            'so_type' => 'store',
            'so_product_name' => 'Stella Pengharum Pikiran',
            'so_total' => 30000.00,
            'user_id' => '2583164c-edcc-45d9-8391-51c76b767258',
            'so_status' => 'succeeded'
        ]);

        SalesOrderApi::firstOrCreate([
            'so_type' => 'virtual',
            'so_product_name' => 'Spotify 3 Bulan',
            'so_total' => 50000.00,
            'user_id' => '2583164c-edcc-45d9-8391-51c76b767258',
            'so_status' => 'Diterima & selesai'
        ]);

    }

    /**
     * Run seeds to be ran only on production environments
     *
     * @return mixed
     */
    public function runProduction() {

    }

    /**
     * Run seeds to be ran on every environment (including production)
     *
     * @return mixed
     */
    public function runAlways() {

    }
}
