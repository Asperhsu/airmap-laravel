<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GroupSeeder::class);
        $this->call(ProbecubeSeeder::class);
        $this->call(IndependentSeeder::class);
        $this->call(DefaultUserSeeder::class);
        $this->call(GeometrySeeder::class);
    }
}
