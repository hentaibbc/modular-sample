<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [ 'id' => 1, 'name'  => '商品一', 'price' => 100, ],
            [ 'id' => 2, 'name'  => '商品二', 'price' => 150, ],
            [ 'id' => 3, 'name'  => '商品三', 'price' => 120, ],
            [ 'id' => 4, 'name'  => '商品四', 'price' => 200, ],
            [ 'id' => 5, 'name'  => '商品五', 'price' => 500, ],
        ];

        foreach ($items as $item) {
            DB::table('items')->insertOrIgnore(array_merge($item, [
                'created_at'    => now(),
                'updated_at'    => now(),
            ]));
        }
    }
}
