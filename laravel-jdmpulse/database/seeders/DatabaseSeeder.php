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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Vider les tables (clés étrangères désactivées le temps du truncate, sinon MySQL refuse)
        Schema::disableForeignKeyConstraints();
        DB::table('likes')->truncate();
        DB::table('owns')->truncate();
        DB::table('powers')->truncate();
        DB::table('motorizations')->truncate();
        DB::table('engines')->truncate();
        DB::table('cars')->truncate();
        DB::table('editions')->truncate();
        DB::table('personal_access_tokens')->truncate();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        // Le dépôt est public : en production, aucun mot de passe connu. Les comptes fictifs ont un
        // mot de passe aléatoire et le superAdmin celui défini par ADMIN_PASSWORD dans le .env du serveur
        $production    = app()->environment('production');
        $password      = fn () => bcrypt($production ? Str::random(32) : 'password');
        $adminPassword = bcrypt($production ? (config('app.admin_password') ?: Str::random(32)) : 'password');

        // Créer des utilisateurs
        User::factory()->create(['first_name' => 'Tom',   'last_name' => 'Vaillant', 'date_of_birth' => '2004-11-11', 'email' => 'tom.vaillant@eg.com',  'role' => 'superAdmin', 'password' => $adminPassword]);
        User::factory()->create(['first_name' => 'John',  'last_name' => 'Doe',      'date_of_birth' => '2000-05-01', 'email' => 'john.doe@eg.com',      'role' => 'admin',      'password' => $password()]);
        User::factory()->create(['first_name' => 'Jane',  'last_name' => 'Smith',    'date_of_birth' => '1994-02-21', 'email' => 'jane.smith@eg.com',    'role' => 'user',       'password' => $password()]);
        User::factory()->create(['first_name' => 'Alice', 'last_name' => 'Liddell',  'date_of_birth' => '2002-08-05', 'email' => 'alice.liddell@eg.com', 'role' => 'user',       'password' => $password()]);
        User::factory()->create(['first_name' => 'Bob',   'last_name' => 'Builder',  'date_of_birth' => '2001-10-17', 'email' => 'bob.builder@eg.com',   'role' => 'user',       'password' => $password()]);
        // Compte démo (id 6) : connexion uniquement via /auth/demo, mot de passe aléatoire inconnu
        User::factory()->create(['first_name' => 'Visiteur', 'last_name' => 'Démo', 'date_of_birth' => '2000-01-01', 'email' => config('app.demo_email'), 'role' => 'user', 'password' => bcrypt(Str::random(32))]);

        // Créer des éditions
        Edition::factory()->create(['edition_name' => 'Silver Blue' ]);
        Edition::factory()->create(['edition_name' => 'Type R'      ]);
        Edition::factory()->create(['edition_name' => 'Type-RS'     ]);
        Edition::factory()->create(['edition_name' => 'GT-R'        ]);
        Edition::factory()->create(['edition_name' => 'Spec-R'       ]);
        Edition::factory()->create(['edition_name' => 'GT-APEX'      ]);
        Edition::factory()->create(['edition_name' => 'Tommi Mäkinen']);
        Edition::factory()->create(['edition_name' => '22B STi'      ]);
        Edition::factory()->create(['edition_name' => 'Tourer V'     ]);
        Edition::factory()->create(['edition_name' => 'Twin Turbo'   ]);

        // Créer des voitures
        Car::factory()->create(['brand' => 'Mazda',  'model' => 'MX-5',    'year' => '2003', 'color' => 'Strato Blue', 'generation' => 'NBFL',  'image_url' => 'https://bringatrailer.com/wp-content/uploads/2021/04/2003_mazda_mx-5_miata_1619525192eb5ed0be11981a858CD2F78-C153-4A06-AA5C-CB76DC7B7CB4-scaled.jpeg', 'edition_id' => 1]);
        Car::factory()->create(['brand' => 'Honda',  'model' => 'Civic',   'year' => '1999', 'color' => 'Noir',        'generation' => 'EK9',   'image_url' => 'https://classicregister.com/sites/default/files/1997%20Honda%20Civic%20Type%20R%20EK9%20Images%202020%20NZ%20%282%29.jpg', 'edition_id' => 2]);
        Car::factory()->create(['brand' => 'Mazda',  'model' => 'RX-7',    'year' => '1997', 'color' => 'Blanc',       'generation' => 'FD3S',  'image_url' => 'https://images.squarespace-cdn.com/content/v1/556bcfd7e4b0923c3c70d86c/1527750744747-559WQIWSO7GZTCYQ69XO/IMG_1268+copy+copy.jpg', 'edition_id' => 3]);
        Car::factory()->create(['brand' => 'Nissan', 'model' => 'Skyline', 'year' => '2000', 'color' => 'Bleu',        'generation' => 'R34',   'image_url' => 'https://img1.bonhams.com/image?src=Images/live/2023-03/27/25327802-1-1.jpg', 'edition_id' => 4]);
        Car::factory()->create(['brand' => 'Toyota', 'model' => 'Supra',   'year' => '1998', 'color' => 'Rouge',       'generation' => 'Mk4',   'image_url' => 'https://www.swapland.fr/img/cms/Photo%20blog/toyota-supra-turbo-1993.jpg', 'edition_id' => null]);
        // image_url vide = placeholder affiché par le front, en attendant une photo
        Car::factory()->create(['brand' => 'Nissan',     'model' => 'Silvia',            'year' => '1999', 'color' => 'Jaune',         'generation' => 'S15',    'image_url' => 'https://grandtourismeimport.com/wp-content/uploads/2020/03/5-78.jpg', 'edition_id' => 5   ]);
        Car::factory()->create(['brand' => 'Toyota',     'model' => 'Sprinter Trueno',   'year' => '1985', 'color' => 'Blanc et noir', 'generation' => 'AE86',   'image_url' => 'https://i.ebayimg.com/images/g/TaUAAOSwqHplOs94/s-l1200.jpg', 'edition_id' => 6]);
        Car::factory()->create(['brand' => 'Honda',      'model' => 'NSX',               'year' => '1991', 'color' => 'Rouge',         'generation' => 'NA1',    'image_url' => 'https://benzin.fra1.digitaloceanspaces.com/lead/original/img_606d848b4481d.jpg', 'edition_id' => null]);
        Car::factory()->create(['brand' => 'Mitsubishi', 'model' => 'Lancer Evolution',  'year' => '1999', 'color' => 'Rouge',         'generation' => 'Evo VI', 'image_url' => 'https://assets.carandclassic.com/uploads/new/25349095/1b0f9396-2b9b-4b0e-8ad3-9edf72a0bccb.JPG?fit=fillmax&h=1200&ixlib=php-4.1.0&q=85&w=1200&s=83aea550f9404fbc9ca2c3eb6a341649', 'edition_id' => 7   ]);
        Car::factory()->create(['brand' => 'Subaru',     'model' => 'Impreza WRX STI',   'year' => '1998', 'color' => 'Bleu',          'generation' => 'GC8',    'image_url' => 'https://media-r2.carsandbids.com/cdn-cgi/image/width=2080,quality=70/9004500a220bf3a3d455d15ee052cf8c332606f8/photos/rJ2N6X7g-1v5CyTQENx-(edit).jpg?t=170041351276', 'edition_id' => 8   ]);
        Car::factory()->create(['brand' => 'Nissan',     'model' => 'Skyline',           'year' => '1990', 'color' => 'Gris',          'generation' => 'R32',    'image_url' => 'https://www.auto-forever.com/wp-content/uploads/2022/11/Skyline_1991-1993_coupe_1.jpg', 'edition_id' => 4   ]);
        Car::factory()->create(['brand' => 'Toyota',     'model' => 'Chaser',            'year' => '1998', 'color' => 'Blanc',         'generation' => 'JZX100', 'image_url' => 'https://assets.carandclassic.com/uploads/cars/toyota/C1598941/1998-toyota-chaser-tourer-v-64926092370de.jpg?ar=16%3A9&auto=&fit=crop&h=1200&ixlib=php-4.1.0&q=80&w=1200&s=a4b00c7205ec4a5561de737973addd1b', 'edition_id' => 9   ]);
        Car::factory()->create(['brand' => 'Honda',      'model' => 'S2000',             'year' => '2000', 'color' => 'Argent',        'generation' => 'AP1',    'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/d/dc/HondaS2000-004.jpg?utm_source=fr.wikipedia.org&utm_campaign=index&utm_content=original', 'edition_id' => null]);
        Car::factory()->create(['brand' => 'Nissan',     'model' => '300ZX',             'year' => '1990', 'color' => 'Noir',          'generation' => 'Z32',    'image_url' => 'https://rpmweb.ca/medias/Nissan-300ZX-1990-001.jpg', 'edition_id' => 10  ]);
        Car::factory()->create(['brand' => 'Honda',      'model' => 'Integra',           'year' => '1998', 'color' => 'Blanc',         'generation' => 'DC2',    'image_url' => 'https://images.collectingcars.com/081569/CT-1.jpg?w=3840&q=75', 'edition_id' => 2   ]);

        // Créer des moteurs
        Engine::factory()->create(['engine_name' => 'DOHC 16V', 'architecture' => 'I4',     'volume' => '1.6L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'DOHC 16V', 'architecture' => 'I4',     'volume' => '1.8L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => '2JZ-GTE',  'architecture' => 'I6',     'volume' => '3.5L', 'induction' => 'Biturbo',   'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'VQ35DE',   'architecture' => 'V6',     'volume' => '2.0L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => '13B-REW',  'architecture' => 'Wankel', 'volume' => '1.3L', 'induction' => 'Suralimenté',  'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'B16A2',    'architecture' => 'I4',     'volume' => '1.6L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'RB26DETT', 'architecture' => 'I6',     'volume' => '2.6L', 'induction' => 'Biturbo',   'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'SR20DET',  'architecture' => 'I4',     'volume' => '2.0L', 'induction' => 'Turbo',         'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => '4A-GE',    'architecture' => 'I4',     'volume' => '1.6L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'C30A',     'architecture' => 'V6',     'volume' => '3.0L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => '4G63T',    'architecture' => 'I4',     'volume' => '2.0L', 'induction' => 'Turbo',         'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'EJ22G',    'architecture' => 'Flat-4', 'volume' => '2.2L', 'induction' => 'Turbo',         'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => '1JZ-GTE',  'architecture' => 'I6',     'volume' => '2.5L', 'induction' => 'Turbo',         'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'F20C',     'architecture' => 'I4',     'volume' => '2.0L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'VG30DETT', 'architecture' => 'V6',     'volume' => '3.0L', 'induction' => 'Biturbo',       'fuel_type' => 'Essence']);
        Engine::factory()->create(['engine_name' => 'B18C',     'architecture' => 'I4',     'volume' => '1.8L', 'induction' => 'Atmospherique', 'fuel_type' => 'Essence']);
        // RB26DETT de la R32 : même moteur que la R34 mais puissance d'origine différente (la puissance est portée par le moteur)
        Engine::factory()->create(['engine_name' => 'RB26DETT', 'architecture' => 'I6',     'volume' => '2.6L', 'induction' => 'Biturbo',       'fuel_type' => 'Essence']);
        
        // Créer des motorisations
        Motorization::factory()->create(['power' => 110, 'torque' => 137, 'consumption' => 8.6,  'engine_id' => 1]);
        Motorization::factory()->create(['power' => 140, 'torque' => 170, 'consumption' => 9.5,  'engine_id' => 2]);
        Motorization::factory()->create(['power' => 330, 'torque' => 440, 'consumption' => 11.1, 'engine_id' => 3]);
        Motorization::factory()->create(['power' => 280, 'torque' => 363, 'consumption' => 8.7,  'engine_id' => 4]);
        Motorization::factory()->create(['power' => 280, 'torque' => 294, 'consumption' => 10.2, 'engine_id' => 5]);
        Motorization::factory()->create(['power' => 160, 'torque' => 150, 'consumption' => 7.8,  'engine_id' => 6]);
        Motorization::factory()->create(['power' => 316, 'torque' => 392, 'consumption' => 9.8,  'engine_id' => 7]);
        Motorization::factory()->create(['power' => 250, 'torque' => 275, 'consumption' => 9.5,  'engine_id' => 8 ]);
        Motorization::factory()->create(['power' => 130, 'torque' => 149, 'consumption' => 8.0,  'engine_id' => 9 ]);
        Motorization::factory()->create(['power' => 280, 'torque' => 294, 'consumption' => 11.0, 'engine_id' => 10]);
        Motorization::factory()->create(['power' => 280, 'torque' => 373, 'consumption' => 11.5, 'engine_id' => 11]);
        Motorization::factory()->create(['power' => 280, 'torque' => 363, 'consumption' => 11.8, 'engine_id' => 12]);
        Motorization::factory()->create(['power' => 280, 'torque' => 378, 'consumption' => 10.5, 'engine_id' => 13]);
        Motorization::factory()->create(['power' => 250, 'torque' => 218, 'consumption' => 9.5,  'engine_id' => 14]);
        Motorization::factory()->create(['power' => 280, 'torque' => 388, 'consumption' => 12.0, 'engine_id' => 15]);
        Motorization::factory()->create(['power' => 200, 'torque' => 188, 'consumption' => 8.5,  'engine_id' => 16]);
        Motorization::factory()->create(['power' => 280, 'torque' => 353, 'consumption' => 10.5, 'engine_id' => 17]);

        // Créer des puissances
        Power::factory()->create(['car_id' => 1, 'engine_id' => 1]);
        Power::factory()->create(['car_id' => 1, 'engine_id' => 2]);
        Power::factory()->create(['car_id' => 2, 'engine_id' => 6]);
        Power::factory()->create(['car_id' => 3, 'engine_id' => 5]);
        Power::factory()->create(['car_id' => 4, 'engine_id' => 7]);
        Power::factory()->create(['car_id' => 5, 'engine_id' => 3]);
        Power::factory()->create(['car_id' => 6,  'engine_id' => 8 ]);
        Power::factory()->create(['car_id' => 7,  'engine_id' => 9 ]);
        Power::factory()->create(['car_id' => 8,  'engine_id' => 10]);
        Power::factory()->create(['car_id' => 9,  'engine_id' => 11]);
        Power::factory()->create(['car_id' => 10, 'engine_id' => 12]);
        Power::factory()->create(['car_id' => 11, 'engine_id' => 17]);
        Power::factory()->create(['car_id' => 12, 'engine_id' => 13]);
        Power::factory()->create(['car_id' => 13, 'engine_id' => 14]);
        Power::factory()->create(['car_id' => 14, 'engine_id' => 15]);
        Power::factory()->create(['car_id' => 15, 'engine_id' => 16]);

        // Créer des possessions
        Own::factory()->create(['car_id' => 1, 'user_id' => 1]);
        Own::factory()->create(['car_id' => 2, 'user_id' => 2]);
        Own::factory()->create(['car_id' => 3, 'user_id' => 3]);
        Own::factory()->create(['car_id' => 4, 'user_id' => 4]);
        Own::factory()->create(['car_id' => 5, 'user_id' => 5]);
        Own::factory()->create(['car_id' => 4, 'user_id' => 6]);

        // Créer des likes
        Like::factory()->create(['car_id' => 1, 'user_id' => 1]);
        Like::factory()->create(['car_id' => 2, 'user_id' => 1]);
        Like::factory()->create(['car_id' => 3, 'user_id' => 1]);
        Like::factory()->create(['car_id' => 4, 'user_id' => 1]);
        Like::factory()->create(['car_id' => 2, 'user_id' => 2]);
        Like::factory()->create(['car_id' => 3, 'user_id' => 3]);
        Like::factory()->create(['car_id' => 4, 'user_id' => 4]);
        Like::factory()->create(['car_id' => 5, 'user_id' => 5]);
        Like::factory()->create(['car_id' => 1, 'user_id' => 6]);
        Like::factory()->create(['car_id' => 5, 'user_id' => 6]);
        Like::factory()->create(['car_id' => 7,  'user_id' => 1]);
        Like::factory()->create(['car_id' => 7,  'user_id' => 3]);
        Like::factory()->create(['car_id' => 7,  'user_id' => 4]);
        Like::factory()->create(['car_id' => 9,  'user_id' => 2]);
        Like::factory()->create(['car_id' => 10, 'user_id' => 5]);
        Like::factory()->create(['car_id' => 11, 'user_id' => 1]);
        Like::factory()->create(['car_id' => 11, 'user_id' => 2]);
        Like::factory()->create(['car_id' => 14, 'user_id' => 4]);

    }
}
