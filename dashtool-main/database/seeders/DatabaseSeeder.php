<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cat;
use App\Models\Permit;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ModuleSeeder::class,
            //Seeders
            //PermitSeeder::class,
        ]);

        Cat::create([
            'status' => 1,
            'nom' => 'Basic',
            'desc' => 'Basic',
            'level' => 1,
            'icon' => 'fas fa-user',
            'color' => 'success',
            'slug' => 'basic',
            'filter_on' => 'users',
        ]);

        Cat::create([
            'status' => 1,
            'nom' => 'Admin',
            'desc' => 'Admin',
            'level' => 2,
            'icon' => 'fas fa-user-cog',
            'color' => 'danger',
            'slug' => 'admin',
            'filter_on' => 'users',
        ]);

        Cat::create([
            'status' => 3,
            'nom' => 'Root',
            'desc' => 'Root',
            'level' => 1,
            'icon' => 'fas fa-user-shield',
            'color' => 'dark',
            'slug' => 'root',
            'filter_on' => 'users',
        ]);

        User::factory()->create([
            'name' => 'Andy Dev',
            'email' => 'andy@dev.com',
            'level_cat_id' => 3,
            'password' => '$2y$12$gwbh2pShXLulyjhkjo6Z5.xw13mC.faiADm0Rq5lXLPnBiQR08llC',
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'listUsers',
            'module_id' => 1,
            'sub_module_id' =>2,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'addUser',
            'module_id' => 1,
            'sub_module_id' =>3,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'editUser',
            'module_id' => 1,
            'sub_module_id' =>4,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'listModules',
            'module_id' => 5,
            'sub_module_id' =>6,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'addModule',
            'module_id' => 5,
            'sub_module_id' =>7,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'editModule',
            'module_id' => 5,
            'sub_module_id' =>8,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'profile',
            'module_id' => 9,
            'sub_module_id' =>10,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'listUsers',
            'module_id' => 1,
            'sub_module_id' =>12,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'listModules',
            'module_id' => 5,
            'sub_module_id' =>13,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'myPermits',
            'module_id' => 9,
            'sub_module_id' =>14,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'listPosts',
            'module_id' => 15,
            'sub_module_id' =>16,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'editPost',
            'module_id' => 15,
            'sub_module_id' =>17,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'addPost',
            'module_id' => 15,
            'sub_module_id' =>18,
            'user_id' => 1,
        ]);

        Permit::create([
            'status' => 1,
            'level' => 1,
            'url_module'=> 'listPosts',
            'module_id' => 15,
            'sub_module_id' =>19,
            'user_id' => 1,
        ]);
    }
}
