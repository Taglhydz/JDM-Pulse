<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Car;
use App\Models\Edition;
use App\Models\Engine;
use App\Models\Motorization;
use App\Models\Power;
use App\Models\Own;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Vider les tables
        DB::table('users')->truncate();
        DB::table('cars')->truncate();
        DB::table('editions')->truncate();
        DB::table('engines')->truncate();
        DB::table('motorizations')->truncate();
        DB::table('powers')->truncate();
        DB::table('owns')->truncate();

        // Créer des utilisateurs
        User::factory()->create(['first_name' => 'Tom',   'last_name' => 'Vaillant', 'date_of_birth' => '2004-11-11', 'email' => 'tom.vaillant@eg.com',  'password' => bcrypt('password')]);
        User::factory()->create(['first_name' => 'John',  'last_name' => 'Doe',      'date_of_birth' => '2000-05-01', 'email' => 'john.doe@eg.com',      'password' => bcrypt('password')]);
        User::factory()->create(['first_name' => 'Jane',  'last_name' => 'Smith',    'date_of_birth' => '1994-02-21', 'email' => 'jane.smith@eg.com',    'password' => bcrypt('password')]);
        User::factory()->create(['first_name' => 'Alice', 'last_name' => 'Liddell',  'date_of_birth' => '2002-08-05', 'email' => 'alice.liddell@eg.com', 'password' => bcrypt('password')]);
        User::factory()->create(['first_name' => 'Bob',   'last_name' => 'Builder',  'date_of_birth' => '2001-10-17', 'email' => 'bob.builder@eg.com',   'password' => bcrypt('password')]);

        // Créer des éditions
        Edition::factory()->create(['edition_name' => 'Silver Blue']);
        Edition::factory()->create(['edition_name' => 'Limited']);
        Edition::factory()->create(['edition_name' => 'Sport']);
        Edition::factory()->create(['edition_name' => 'Nismo']);
        Edition::factory()->create(['edition_name' => 'Convertible']);

        // Créer des voitures
        Car::factory()->create(['brand' => 'Mazda',  'model' => 'MX-5',    'year' => '2003', 'color' => 'Strato Blue',   'generation' => 'NBFL',  'image_url' => 'https://www.mazdausa.com/siteassets/vehicles/2022/mx-5-miata/hero/mazda-2022-mx-5-miata-hero.png', 'edition_id' => 1]);
        Car::factory()->create(['brand' => 'Honda',  'model' => 'Civic',   'year' => '1999', 'color' => 'Black',         'generation' => 'EK9',   'image_url' => 'https://www.honda.com/content/dam/honda-cars/2022/civic-sedan/overview/2022-civic-sedan-overview-exterior-gallery-01.jpg']);
        Car::factory()->create(['brand' => 'Mazda',  'model' => 'RX-7',    'year' => '1997', 'color' => 'White',         'generation' => 'FD3S',  'image_url' => 'https://www.mazdausa.com/siteassets/vehicles/2022/rx-7/hero/mazda-2022-rx-7-hero.png']);
        Car::factory()->create(['brand' => 'Nissan', 'model' => 'Skyline', 'year' => '2000', 'color' => 'Blue',          'generation' => 'R34',   'image_url' => 'https://www.nissanusa.com/content/dam/Nissan/us/vehicles/gt-r/2022/overview/22-NTB-GTR-Overview-Exterior-01.jpg']);
        Car::factory()->create(['brand' => 'Toyota', 'model' => 'Supra',   'year' => '1998', 'color' => 'Red',           'generation' => 'Mk4',   'image_url' => 'https://www.toyota.com/imgix/responsive/images/mlp/colorizer/2022/supra/1H5/1.png?bg=fff&fm=webp&w=1024']);

        // Créer des moteurs
        Engine::factory()->create(['engine_name' => 'DOHC 16V', 'architecture' => 'I4',     'volume' => '1.6L', 'induction' => 'Atmospheric', 'fuel_type' => 'Petrol']);
        Engine::factory()->create(['engine_name' => '2JZ-GTE',  'architecture' => 'I6',     'volume' => '3.5L', 'induction' => 'Twinturbo',   'fuel_type' => 'Petrol']);
        Engine::factory()->create(['engine_name' => 'VQ35DE',   'architecture' => 'V6',     'volume' => '2.0L', 'induction' => 'Atmospheric', 'fuel_type' => 'Petrol']);
        Engine::factory()->create(['engine_name' => '13B-REW',  'architecture' => 'Wankel', 'volume' => '1.3L', 'induction' => 'Twinturbo',   'fuel_type' => 'Petrol']);
        Engine::factory()->create(['engine_name' => 'B16A2',    'architecture' => 'I4',     'volume' => '1.6L', 'induction' => 'Atmospheric', 'fuel_type' => 'Petrol']);
        Engine::factory()->create(['engine_name' => 'RB26DETT', 'architecture' => 'I6',     'volume' => '2.6L', 'induction' => 'Twinturbo',   'fuel_type' => 'Petrol']);
        
        // Créer des motorisations
        Motorization::factory()->create(['power' => 110, 'torque' => 137, 'consumption' => 8.6,  'engine_id' => 1]);
        Motorization::factory()->create(['power' => 330, 'torque' => 440, 'consumption' => 11.1, 'engine_id' => 2]);
        Motorization::factory()->create(['power' => 280, 'torque' => 363, 'consumption' => 8.7,  'engine_id' => 3]);
        Motorization::factory()->create(['power' => 280, 'torque' => 294, 'consumption' => 10.2, 'engine_id' => 4]);
        Motorization::factory()->create(['power' => 160, 'torque' => 150, 'consumption' => 7.8,  'engine_id' => 5]);
        Motorization::factory()->create(['power' => 316, 'torque' => 392, 'consumption' => 9.8,  'engine_id' => 6]);

        // Créer des puissances
        Power::factory()->create(['car_id' => 1, 'engine_id' => 1]);
        Power::factory()->create(['car_id' => 5, 'engine_id' => 2]);
        Power::factory()->create(['car_id' => 3, 'engine_id' => 4]);
        Power::factory()->create(['car_id' => 2, 'engine_id' => 5]);
        Power::factory()->create(['car_id' => 4, 'engine_id' => 6]);

        // Créer des possessions
        Own::factory()->create(['car_id' => 1, 'user_id' => 1]);
        Own::factory()->create(['car_id' => 2, 'user_id' => 2]);
        Own::factory()->create(['car_id' => 3, 'user_id' => 3]);
        Own::factory()->create(['car_id' => 4, 'user_id' => 4]);
        Own::factory()->create(['car_id' => 5, 'user_id' => 5]);
    }
}
