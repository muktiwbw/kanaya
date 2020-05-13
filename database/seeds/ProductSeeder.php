<?php

use Illuminate\Database\Seeder;
use App\Product;
use App\Image;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $sizes = ['S','M','L','XL'];

        products = [
            [
                'name' => 'Dress Party',
                'category' => 'dress-party',
                'stock' => 50,
                'sale' => 700000,
                'rent' => 350000,
                'images' => [
                    [
                        'counts' => 3,
                        'end' => 9
                    ]
                ]
            ],
            [
                'name' => 'Kaftan Ramadhan',
                'category' => 'kaftan-ramadhan',
                'stock' => 100,
                'sale' => 800000,
                'rent' => 300000,
                'images' => [
                    [
                        'counts' => 3,
                        'end' => 19
                    ]
                ]
            ],
            [
                'name' => 'Kebaya Akad',
                'category' => 'kebaya-akad',
                'stock' => 30,
                'sale' => 3000000,
                'rent' => 800000,
                'images' => [
                    [
                        'counts' => 3,
                        'end' => 29
                    ],
                    [
                        'counts' => 2,
                        'end' => 39
                    ]
                ]
            ],
            [
                'name' => 'Kebaya Resepsi',
                'category' => 'kebaya-resepsi',
                'stock' => 10,
                'sale' => 6000000,
                'rent' => 2500000,
                'images' => [
                    [
                        'counts' => 1,
                        'end' => 79
                    ]
                ]
            ],
            [
                'name' => 'Kebaya Wisuda',
                'category' => 'kebaya-wisuda',
                'stock' => 50,
                'sale' => 0,
                'rent' => 0,
                'images' => [
                    [
                        'counts' => 2,
                        'end' => 89
                    ],
                    [
                        'counts' => 1,
                        'end' => 95
                    ]
                ]
            ],
            [
                'name' => 'Prewedding',
                'category' => 'prewedding',
                'stock' => 50,
                'sale' => 0,
                'rent' => 0,
                'images' => [
                    [
                        'counts' => 3,
                        'end' => 118
                    ],
                    [
                        'counts' => 2,
                        'end' => 120
                    ]
                ]
            ],
            [
                'name' => 'White Gown',
                'category' => 'white-gown',
                'stock' => 50,
                'sale' => 0,
                'rent' => 0,
                'images' => [
                    [
                        'counts' => 3,
                        'end' => 130
                    ],
                    [
                        'counts' => 2,
                        'end' => 131
                    ]
                ]
            ]
        ]

    }
}
