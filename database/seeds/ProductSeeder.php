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
        $products = [
            [
                'id' => 1,
                'code' => '00001',
                'name' => 'Elite Knight Armor',
                'price' => 900000,
                'notes' => 'Armor of a nameless knight, perhaps an elite knight of Astora, based on the fire-warding heraldic symbol on it\'s blue surcoat.Although he was loath to give up on his Undead mission, he perished at the Undead Asylum, and went Hollow.',
                'stock' => 20,
                'available' => 4,
                'rent' => 16
            ],
            [
                'id' => 2,
                'code' => '00002',
                'name' => 'Havel\'s Armor',
                'price' => 1000000,
                'notes' => 'Apparel worn by Havel the Rock\'s warriors. Carved from solid rock, its tremendous weight is matched only by the defense it provides.\r\nHavel\'s Warriors never flinched nor retreated from battle. Those unfortunate enough to face them were inevitably beaten to a pulp.',
                'stock' => 30,
                'available' => 7,
                'rent' => 23
            ],    
            [
                'id' => 3,
                'code' => '00003',
                'name' => 'Artorias Armor',
                'price' => 400000,
                'notes' => 'Armor of Artorias the Abysswalker, one of Gwyn\'s Four Knights.\r\nThe death of the armor\'s owner can be surmised from the corrosive Dark of the Abyss, and the tattered azure-blue cape, once a symbol of pride and glory.',
                'stock' => 23,
                'available' => 11,
                'rent' => 12
            ],  
            [
                'id' => 4,
                'code' => '00004',
                'name' => 'Dark Souls Set',
                'price' => 2000000,
                'notes' => 'Sets of Elite Knight, Havel\'s, and Artorias',
                'stock' => 10,
                'available' => 2,
                'rent' => 8
            ],  
            [
                'id' => 5,
                'code' => '00005',
                'name' => 'Black Knight Set',
                'price' => 900000,
                'notes' => 'Armor of the Black Knights who haunt Lordran.\r\nThe knights followed Lord Gwyn when he departed to link the Fire but they were burned to ashes in the newly kindled flame, wandering the world as disembodied spirits ever after',
                'stock' => 12,
                'available' => 2,
                'rent' => 10
            ],  
            [
                'id' => 6,
                'code' => '00006',
                'name' => 'Faraam Set',
                'price' => 200000,
                'notes' => 'Designed in the style of the Lion Knights, a once-mighty order from Forossa. Although the Lion Knights wore heavy armor, they were feared for their nimble two-handed swordplay.\r\nBut their legacy was cut short with the fall of Forossa.',
                'stock' => 30,
                'available' => 18,
                'rent' => 12
            ], 
            [
                'id' => 7,
                'code' => '00007',
                'name' => 'Black Iron Set',
                'price' => 400000,
                'notes' => 'Armor set of Black Iron Tarkus, a knight known for his great strength\r\nBuilt of a special black iron and providing strong defense, notably against fire, but so terribly heavy to be unwieldy to all but Tarkus himself.',
                'stock' => 30,
                'available' => 18,
                'rent' => 12
            ],  
            [
                'id' => 8,
                'code' => '00008',
                'name' => 'Alonne Captain Set',
                'price' => 700000,
                'notes' => 'The bonds of the Alonne Knights, who served the Old Iron King, were mightier than the land\'s iron, but in the end the knights were subsumed by the flames that brought the castle down.',
                'stock' => 23,
                'available' => 11,
                'rent' => 12
            ],  
            [
                'id' => 9,
                'code' => '00009',
                'name' => 'Iudex Gundyr Set',
                'price' => 500000,
                'notes' => 'Ancient armor of a set of cast iron armor, belonging to Champion Gundyr. Modeled after a former king.\r\n\r\nGundyr, or the Belated Champion, was bested by an unknown warrior. He then became sheath to a coiled sword in the hopes that someday, the first flame would be linked once more.',
                'stock' => 11,
                'available' => 9,
                'rent' => 2
            ],  
            [
                'id' => 10,
                'code' => '00010',
                'name' => 'Sir Alonne Set',
                'price' => 1200000,
                'notes' => 'Sir Alonne came to this land from the east, chose to serve a little-known and unestablished lord, and helped him become the Old Iron King.\r\nThen, at the very peak of his sire\'s rule, Sir Alonne set out again, in search of lands yet unknown.',
                'stock' => 10,
                'available' => 1,
                'rent' => 9
            ],  
            [
                'id' => 11,
                'code' => '00011',
                'name' => 'Dark Souls II Sets',
                'price' => 4000000,
                'notes' => 'Sets from Dark Souls II with additional Aurous Set.',
                'stock' => 3,
                'available' => 3,
                'rent' => 0
            ]    
        ];
        
        $images = [
            [
                'id' => 1,
                'name' => 'Elite Knight Armor', 
                'url' => 'products/1-1.png',
                'path' => 'products/1-1.png',
                'product_id' => 1
            ],
            [
                'id' => 2,
                'name' => 'Havel\'s Armor', 
                'url' => 'products/2-1.png',
                'path' => 'products/2-1.png',
                'product_id' => 2
            ],
            [
                'id' => 3,
                'name' => 'Artorias Armor', 
                'url' => 'products/3-1.jpeg',
                'path' => 'products/3-1.jpeg',
                'product_id' => 3
            ],
            [
                'id' => 4,
                'name' => 'Dark Souls Set', 
                'url' => 'products/4-1.jpeg',
                'path' => 'products/4-1.jpeg',
                'product_id' => 4
            ],
            [
                'id' => 5,
                'name' => 'Dark Souls Set', 
                'url' => 'products/4-2.png',
                'path' => 'products/4-2.png',
                'product_id' => 4
            ],
            [
                'id' => 6,
                'name' => 'Dark Souls Set', 
                'url' => 'products/4-3.png',
                'path' => 'products/4-3.png',
                'product_id' => 4
            ],
            [
                'id' => 7,
                'name' => 'Black Knight Set', 
                'url' => 'products/5-1.png',
                'path' => 'products/5-1.png',
                'product_id' => 5
            ],
            [
                'id' => 8,
                'name' => 'Black Knight Set', 
                'url' => 'products/5-2.png',
                'path' => 'products/5-2.png',
                'product_id' => 5
            ],
            [
                'id' => 9,
                'name' => 'Faraam Set', 
                'url' => 'products/6-1.png',
                'path' => 'products/6-1.png',
                'product_id' => 6
            ],
            [
                'id' => 10,
                'name' => 'Faraam Set', 
                'url' => 'products/6-2.jpeg',
                'path' => 'products/6-2.jpeg',
                'product_id' => 6
            ],
            [
                'id' => 11,
                'name' => 'Black Iron Set', 
                'url' => 'products/7-1.png',
                'path' => 'products/7-1.png',
                'product_id' => 7
            ],
            [
                'id' => 12,
                'name' => 'Alonne Captain Set', 
                'url' => 'products/8-1.jpeg',
                'path' => 'products/8-1.jpeg',
                'product_id' => 8
            ],
            [
                'id' => 13,
                'name' => 'Iudex Gundyr Set', 
                'url' => 'products/9-1.jpeg',
                'path' => 'products/9-1.jpeg',
                'product_id' => 9
            ],
            [
                'id' => 14,
                'name' => 'Sir Alonne Set', 
                'url' => 'products/10-1.jpeg',
                'path' => 'products/10-1.jpeg',
                'product_id' => 10
            ],
            [
                'id' => 15,
                'name' => 'Dark Souls II Sets', 
                'url' => 'products/11-1.jpeg',
                'path' => 'products/11-1.jpeg',
                'product_id' => 11
            ],
            [
                'id' => 16,
                'name' => 'Dark Souls II Sets', 
                'url' => 'products/11-2.jpeg',
                'path' => 'products/11-2.jpeg',
                'product_id' => 11
            ],
            [
                'id' => 17,
                'name' => 'Dark Souls II Sets', 
                'url' => 'products/11-3.jpeg',
                'path' => 'products/11-3.jpeg',
                'product_id' => 11
            ],
            [
                'id' => 18,
                'name' => 'Dark Souls II Sets', 
                'url' => 'products/11-4.jpeg',
                'path' => 'products/11-4.jpeg',
                'product_id' => 11
            ]
        ];

        foreach($products as $product){
            Product::create($product);
        }

        foreach($images as $image){
            Image::create($image);
        }
    }
}
