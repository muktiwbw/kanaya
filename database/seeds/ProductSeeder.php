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
        
        $sizes = ['s','m','l','xl'];

        $categories = [
            [
                'name' => 'Dress Party',
                'slug' => 'dress-party',
                'stock' => 50,
                'sale' => 700000,
                'rent' => 350000,
                'batches' => [
                    [
                        'counts' => 3,
                        'end' => 9
                    ]
                ]
            ],
            [
                'name' => 'Kaftan Ramadhan',
                'slug' => 'kaftan-ramadhan',
                'stock' => 100,
                'sale' => 800000,
                'rent' => 300000,
                'batches' => [
                    [
                        'counts' => 3,
                        'end' => 19
                    ]
                ]
            ],
            [
                'name' => 'Kebaya Akad',
                'slug' => 'kebaya-akad',
                'stock' => 30,
                'sale' => 3000000,
                'rent' => 800000,
                'batches' => [
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
                'slug' => 'kebaya-resepsi',
                'stock' => 10,
                'sale' => 6000000,
                'rent' => 2500000,
                'batches' => [
                    [
                        'counts' => 1,
                        'end' => 79
                    ]
                ]
            ],
            [
                'name' => 'Kebaya Wisuda',
                'slug' => 'kebaya-wisuda',
                'stock' => 50,
                'sale' => 0,
                'rent' => 0,
                'batches' => [
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
                'slug' => 'prewedding',
                'stock' => 50,
                'sale' => 0,
                'rent' => 0,
                'batches' => [
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
                'slug' => 'white-gown',
                'stock' => 50,
                'sale' => 0,
                'rent' => 0,
                'batches' => [
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
        ];

        $counter = 1;
        $code;

        foreach ($categories as $category) {
            
            foreach ($category['batches'] as $batch) {
                
                for($i=$counter; $i<=$batch['end']; $i++){

                    if($counter < 10){
                        $code = '0000'.$counter;
                    }elseif($counter >= 10 and $counter < 100){
                        $code = '000'.$counter;
                    }elseif($counter >= 100 and $counter < 1000){
                        $code = '00'.$counter;
                    }elseif($counter >= 1000 and $counter < 10000){
                        $code = '0'.$counter;
                    }

                    $name = "{$category['name']} {$counter}";

                    for($j=1; $j<=$batch['counts']; $j++){

                        $fileName = "{$code}-{$name}-{$j}";
                        $pathUrl = "products/{$fileName}.JPG";

                        Image::create([
                            'name' => $fileName,
                            'url' => $pathUrl,
                            'path' => $pathUrl
                        ]);

                    }

                    $images = Image::where('name', 'like', "{$code}%")->get();

                    foreach ($sizes as $size) {
                        
                        for($k=0; $k<$category['stock']; $k++){

                            $product = Product::create([
                                'code' => $code,
                                'name' => $name,
                                'price' => $category['rent'],
                                'sale' => $category['sale'],
                                'size' => $size,
                                'category' => $category['slug']
                            ]);

                            foreach ($images as $image) {
                                
                                $product->images()->save($image);
                            
                            }

                        }

                    }

                    $counter++;

                }

            }

        }

    }
}
