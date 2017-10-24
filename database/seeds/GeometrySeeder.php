<?php

use App\Models\Geometry;
use Illuminate\Database\Seeder;

class GeometrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default = new Geometry;
        $default->country = 'default';
        $default->westlng = 0.0;
        $default->eastlng = 0.0;
        $default->northlat = 0.0;
        $default->southlat = 0.0;
        $default->save();
    }
}
