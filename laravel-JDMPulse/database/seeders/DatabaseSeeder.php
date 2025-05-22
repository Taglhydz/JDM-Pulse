<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Car;
use App\Models\Edition;
use App\Models\Engine;
use App\Models\Motorization;
use App\Models\Power;
use App\Models\Own;
use App\Models\Like;
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
        User::factory()->create(['first_name' => 'Tom',   'last_name' => 'Vaillant', 'date_of_birth' => '2004-11-11', 'email' => 'tom.vaillant@eg.com',  'role' => 'superAdmin', 'password' => bcrypt('password')]);
        User::factory()->create(['first_name' => 'John',  'last_name' => 'Doe',      'date_of_birth' => '2000-05-01', 'email' => 'john.doe@eg.com',      'role' => 'admin',      'password' => bcrypt('password')]);
        User::factory()->create(['first_name' => 'Jane',  'last_name' => 'Smith',    'date_of_birth' => '1994-02-21', 'email' => 'jane.smith@eg.com',    'role' => 'user',       'password' => bcrypt('password')]);
        User::factory()->create(['first_name' => 'Alice', 'last_name' => 'Liddell',  'date_of_birth' => '2002-08-05', 'email' => 'alice.liddell@eg.com', 'role' => 'user',       'password' => bcrypt('password')]);
        User::factory()->create(['first_name' => 'Bob',   'last_name' => 'Builder',  'date_of_birth' => '2001-10-17', 'email' => 'bob.builder@eg.com',   'role' => 'user',       'password' => bcrypt('password')]);

        // Créer des éditions
        Edition::factory()->create(['edition_name' => 'Silver Blue' ]);
        Edition::factory()->create(['edition_name' => 'Type R'      ]);
        Edition::factory()->create(['edition_name' => 'Type-RS'     ]);
        Edition::factory()->create(['edition_name' => 'GT-R'        ]);

        // Créer des voitures
        Car::factory()->create(['brand' => 'Mazda',  'model' => 'MX-5',    'year' => '2003', 'color' => 'Strato Blue', 'generation' => 'NBFL',  'image_url' => 'https://bringatrailer.com/wp-content/uploads/2021/04/2003_mazda_mx-5_miata_1619525192eb5ed0be11981a858CD2F78-C153-4A06-AA5C-CB76DC7B7CB4-scaled.jpeg', 'edition_id' => 1]);
        Car::factory()->create(['brand' => 'Honda',  'model' => 'Civic',   'year' => '1999', 'color' => 'Noir',        'generation' => 'EK9',   'image_url' => 'https://classicregister.com/sites/default/files/1997%20Honda%20Civic%20Type%20R%20EK9%20Images%202020%20NZ%20%282%29.jpg', 'edition_id' => 2]);
        Car::factory()->create(['brand' => 'Mazda',  'model' => 'RX-7',    'year' => '1997', 'color' => 'Blanc',       'generation' => 'FD3S',  'image_url' => 'https://images.squarespace-cdn.com/content/v1/556bcfd7e4b0923c3c70d86c/1527750744747-559WQIWSO7GZTCYQ69XO/IMG_1268+copy+copy.jpg', 'edition_id' => 3]);
        Car::factory()->create(['brand' => 'Nissan', 'model' => 'Skyline', 'year' => '2000', 'color' => 'Bleu',        'generation' => 'R34',   'image_url' => 'https://img1.bonhams.com/image?src=Images/live/2023-03/27/25327802-1-1.jpg', 'edition_id' => 4]);
        Car::factory()->create(['brand' => 'Toyota', 'model' => 'Supra',   'year' => '1998', 'color' => 'Rouge',       'generation' => 'Mk4',   'image_url' => 'https://carjager-dev.mo.cloudinary.net/https://wp.carjager.com/wp-content/uploads/2023/03/Toyota-Supra-EU-02.jpeg?tx=w_1905']);

        // Créer des moteurs
        Engine::factory()->create(['engine_name' => 'DOHC 16V', 'architecture' => 'I4',     'volume' => '1.6L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'DOHC 16V', 'architecture' => 'I4',     'volume' => '1.8L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => '2JZ-GTE',  'architecture' => 'I6',     'volume' => '3.5L', 'induction' => 'Biturbo',   'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'VQ35DE',   'architecture' => 'V6',     'volume' => '2.0L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => '13B-REW',  'architecture' => 'Wankel', 'volume' => '1.3L', 'induction' => 'Suralimenté',  'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'B16A2',    'architecture' => 'I4',     'volume' => '1.6L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'RB26DETT', 'architecture' => 'I6',     'volume' => '2.6L', 'induction' => 'Biturbo',   'fuel_type' => 'Essence']);
        
        // Créer des motorisations
        Motorization::factory()->create(['power' => 110, 'torque' => 137, 'consumption' => 8.6,  'engine_id' => 1]);
        Motorization::factory()->create(['power' => 140, 'torque' => 170, 'consumption' => 9.5,  'engine_id' => 2]);
        Motorization::factory()->create(['power' => 330, 'torque' => 440, 'consumption' => 11.1, 'engine_id' => 3]);
        Motorization::factory()->create(['power' => 280, 'torque' => 363, 'consumption' => 8.7,  'engine_id' => 4]);
        Motorization::factory()->create(['power' => 280, 'torque' => 294, 'consumption' => 10.2, 'engine_id' => 5]);
        Motorization::factory()->create(['power' => 160, 'torque' => 150, 'consumption' => 7.8,  'engine_id' => 6]);
        Motorization::factory()->create(['power' => 316, 'torque' => 392, 'consumption' => 9.8,  'engine_id' => 7]);

        // Créer des puissances
        Power::factory()->create(['car_id' => 1, 'engine_id' => 1]);
        Power::factory()->create(['car_id' => 1, 'engine_id' => 2]);
        Power::factory()->create(['car_id' => 2, 'engine_id' => 6]);
        Power::factory()->create(['car_id' => 3, 'engine_id' => 5]);
        Power::factory()->create(['car_id' => 4, 'engine_id' => 7]);
        Power::factory()->create(['car_id' => 5, 'engine_id' => 3]);

        // Créer des possessions
        Own::factory()->create(['car_id' => 1, 'user_id' => 1]);
        Own::factory()->create(['car_id' => 2, 'user_id' => 2]);
        Own::factory()->create(['car_id' => 3, 'user_id' => 3]);
        Own::factory()->create(['car_id' => 4, 'user_id' => 4]);
        Own::factory()->create(['car_id' => 5, 'user_id' => 5]);

        // Créer des likes
        Like::factory()->create(['car_id' => 1, 'user_id' => 1]);
        Like::factory()->create(['car_id' => 2, 'user_id' => 1]);
        Like::factory()->create(['car_id' => 3, 'user_id' => 1]);
        Like::factory()->create(['car_id' => 4, 'user_id' => 1]);
        Like::factory()->create(['car_id' => 2, 'user_id' => 2]);
        Like::factory()->create(['car_id' => 3, 'user_id' => 3]);
        Like::factory()->create(['car_id' => 4, 'user_id' => 4]);
        Like::factory()->create(['car_id' => 5, 'user_id' => 5]);

    }
}
