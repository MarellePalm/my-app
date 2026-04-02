<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Radiology Coffee Mug',
                'sku' => 'RAD-MUG-001',
                'description' => 'Kruus tekstiga "Trust me, I can see inside you".',
                'price' => 12.99,
                'image' => 'products/mug.jpg',
            ],
            [
                'name' => 'X-Ray Tote Bag',
                'sku' => 'RAD-TOTE-002',
                'description' => 'Kandekott röntgenkäe kujundusega.',
                'price' => 18.50,
                'image' => 'products/tote.jpg',
            ],
            [
                'name' => 'Radiology Notebook',
                'sku' => 'RAD-NOTE-003',
                'description' => 'Minimalistlik märkmik radioloogia teemaga.',
                'price' => 9.99,
                'image' => 'products/notebook.jpg',
            ],
            [
                'name' => 'CT Scan Mousepad',
                'sku' => 'RAD-MOUSE-004',
                'description' => 'Hiirematt CT scan kujundusega.',
                'price' => 11.99,
                'image' => 'products/mousepad.jpg',
            ],
            [
                'name' => 'Radiology Sticker Pack',
                'sku' => 'RAD-STICK-005',
                'description' => 'Kleebiste komplekt radioloogia teemal.',
                'price' => 6.99,
                'image' => 'products/stickers.jpg',
            ],
            [
                'name' => 'MRI Poster',
                'sku' => 'RAD-POSTER-006',
                'description' => 'Dekoratiivne MRI kujutisega poster.',
                'price' => 14.99,
                'image' => 'products/poster.jpg',
            ],
            [
                'name' => 'Radiology Keychain',
                'sku' => 'RAD-KEY-007',
                'description' => 'Väike võtmehoidja radioloogia kujundusega.',
                'price' => 5.99,
                'image' => 'products/keychain.jpg',
            ],
            [
                'name' => 'Anatomy Desk Mat',
                'sku' => 'RAD-DESK-008',
                'description' => 'Suur lauamatt inimese anatoomia skeemiga.',
                'price' => 22.99,
                'image' => 'products/deskmat.jpg',
            ],
            [
                'name' => 'Radiology Thermo Bottle',
                'sku' => 'RAD-BOTTLE-009',
                'description' => 'Termospudel radioloogia kujundusega.',
                'price' => 19.99,
                'image' => 'products/bottle.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}