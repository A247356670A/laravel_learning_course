<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\Flight;

class FlightSeeder extends Seeder
{
    public function run()
    {
        Flight::factory(10)->create();
    }
}    