<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * @var array
     */
    private $categories = [
        'Animals',
        'Adjectives',
        'Adverbs',
        'Birds',
        'Books',
        'Buildings',
        'Cars',
        'Celebrities/Famous People',
        'Celebrations/Holidays',
        'Cities',
        'Clothes',
        'Colours',
        'Comic book heroes',
        'Characteristics',
        'Countries',
        'Currencies',
        'Diseases',
        'Drinks',
        'Electronic goods',
        'Emotions',
        'Family members',
        'Films',
        'Film characters',
        'First names',
        'Food',
        'Football teams',
        'Fruit',
        'Furniture',
        'Hobbies',
        'Hotels',
        'Jobs',
        'Languages',
        'Liquids',
        'Mammals',
        'Materials',
        'Monsters',
        'Musical genres',
        'Musical instruments',
        'Nationalities'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(
            array_map(function($name) {
                return [
                    'name' => $name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }, $this->categories)
        );
    }
}
