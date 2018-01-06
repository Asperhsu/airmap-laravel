<?php

use Illuminate\Database\Seeder;

use App\Models\Admin;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'admin',
            'email' => 'asperwon@gmail.com',
            'password' => bcrypt('g0vairmap'),
        ]);
    }
}
