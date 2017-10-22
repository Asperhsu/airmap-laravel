<?php

use Illuminate\Database\Seeder;

use App\Models\User;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'asperwon@gmail.com',
            'password' => bcrypt('g0vairmap'),
        ]);
    }
}
