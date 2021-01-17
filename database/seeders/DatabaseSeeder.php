<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $guru = new Role([
            "name" => "guru"
        ]);
        $guru->save();
        $murid = new Role([
            "name" => "murid"
        ]);
        $murid->save();
    }
}
