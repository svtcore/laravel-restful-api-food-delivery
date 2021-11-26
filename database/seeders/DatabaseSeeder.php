<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::factory()->create(['name' => 'Administrator', 'slug' => 'admin']);
        $user_role = Role::factory()->create(['name' => 'User', 'slug' => 'user']);
        for ($i = 0; $i < 5; $i++) {
            $user = User::factory()->create([
                'first_name' => 'Petrenko',
                'last_name' => 'Oleksandr',
                'phone_country_code' => 380,
                'phone_number' => rand(111111111, 999999999),
                'email' => strval(rand(10000, 99999)).'mail@admin.com',
                'password' => bcrypt('password'),
                'state' => 'active',
            ]);
            $user->createToken('name');
            $array = array($user_role, $admin_role);
            $rand_val = $array[rand(0, count($array) - 1)];
            $user->roles()->attach($rand_val);
        }
        // \App\Models\User::factory(10)->create();
    }
}
