<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ["code" => "ENC", "name" => "Encroachment"],
            ["code" => "DR", "name" => "Dilapidated Roads"],
            ["code" => "SW", "name" => "Solid Waste"],
            ["code" => "HC", "name" => "Hospitals Cleanliness"],
            ["code" => "WC", "name" => "Wall Chalking"],
            ["code" => "DG", "name" => "Decanting Of Gas"],
            ["code" => "SL", "name" => "Street Lights"],
            ["code" => "ZC", "name" => "Zebra Crossing"],
            ["code" => "OMC", "name" => "Open Manholes Cover"],
            ["code" => "QB", "name" => "Quackery"],
            ["code" => "DB", "name" => "Dog Bites"],
            ["code" => "BF", "name" => "Buses Fares"],
            ["code" => "DBR", "name" => "Dilapidated Bridges"],
            ["code" => "BB", "name" => "Bill Boards"],
            ["code" => "DRN", "name" => "Drains"],
            ["code" => "FH", "name" => "Faith Healers"],
            ["code" => "GY", "name" => "Graveyard"],
            ["code" => "VMA", "name" => "Violation of Marriage Act"],
            ["code" => "BS", "name" => "Bus Stands"],
            ["code" => "GB", "name" => "Green Belts"],
            ["code" => "SWAT", "name" => "Stagnant Water"],
            ["code" => "SD", "name" => "Spurious Drugs"],
            ["code" => "MPP", "name" => "Mini Petrol Pumps"]
        ];

        foreach($categories as $key => $category) {
            Category::updateOrCreate(['name' => $category['name']], ['code' => $category['code'], 'ordering' => $key+1]);
        }
    }
}
