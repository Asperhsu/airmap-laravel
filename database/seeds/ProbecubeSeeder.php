<?php

use Illuminate\Database\Seeder;

use App\Models\Group;
use App\Models\Thingspeak;

class ProbecubeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = Group::where('name', 'Probecube')->first();

        $party = 'ProbeCube';
        $fields_map = collect([
            'pm25' => 'field5',
            'humidity' => 'field2',
            'temperature' => 'field1',
        ]);

        $sites = [
            ['channel' => 26769, 'maker' => 'Lafudoci', 'active' => true],
            ['channel' => 23139, 'maker' => 'Lafudoci', 'active' => true],
            ['channel' => 33497, 'maker' => 'Lafudoci', 'active' => true],
            ['channel' => 101039, 'maker' => 'lemon', 'active' => true],
            ['channel' => 104435, 'maker' => 'Lafudoci', 'active' => true],
            ['channel' => 116521, 'maker' => '+0', 'active' => true],
            ['channel' => 107318, 'maker' => '大娘', 'active' => true],
            ['channel' => 113078, 'maker' => 'red', 'active' => true],
            ['channel' => 143057, 'maker' => 'Asper', 'active' => true],
            ['channel' => 105264, 'maker' => 'KnifeLi', 'active' => true],
            ['channel' => 105256, 'maker' => 'HN Pen', 'active' => true],
            ['channel' => 105257, 'maker' => '蔡茂青', 'active' => true],
            ['channel' => 105258, 'maker' => 'Littlejump', 'active' => true],
            ['channel' => 105259, 'maker' => 'Ou-Yang', 'active' => true],
            ['channel' => 105260, 'maker' => 'CH Liu', 'active' => true],
            ['channel' => 105262, 'maker' => 'Superbil', 'active' => true],
            ['channel' => 105263, 'maker' => '余振全', 'active' => true],
            ['channel' => 105261, 'maker' => '李玟玟', 'active' => true],
            ['channel' => 102823, 'maker' => 'Lafudoci', 'active' => true],
        ];

        foreach ($sites as $site) {
            Thingspeak::create([
                'channel'     => $site['channel'],
                'party'       => $party,
                'maker'       => $site['maker'],
                'fields_map'  => $fields_map,
                'active'      => isset($site['active']) ? $site['active'] : true,
                'group_id'    => $group->id,
            ]);
        }
    }
}
