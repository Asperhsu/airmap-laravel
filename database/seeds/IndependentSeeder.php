<?php

use Illuminate\Database\Seeder;

use App\Models\Group;
use App\Models\Thingspeak;

class IndependentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = Group::where('name', 'Independent')->first();

        $sites = [
            [
                'channel'       => 83508,
                'party'         => 'kiang-pm25',
                'maker'         => 'Kiang',
                'active'        => false,
                'fields_map'    => [
                    'pm25'          => 'field1',
                    'humidity'      => 'field3',
                    'temperature'   => 'field2',
                ],
            ],
            [
                'channel'       => 88846,
                'party'         => 'miaoski',
                'maker'         => 'miaoski',
                'active'        => false,
                'fields_map'    => [
                    'pm25'          => 'field1',
                    'humidity'      => 'field3',
                    'temperature'   => 'field2',
                ],
            ],
            [
                'channel'       => 106666,
                'party'         => 'CCU NEAT',
                'maker'         => '林俊翰',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field3',
                    'humidity'      => 'field2',
                    'temperature'   => 'field1',
                ],
            ],
            [
                'channel'       => 110747,
                'party'         => 'CCU 100',
                'maker'         => '李皇辰',
                'active'        => false,
                'fields_map'    => [
                    'pm25'          => 'field3',
                    'humidity'      => 'field2',
                    'temperature'   => 'field1',
                ],
            ],
            [
                'channel'       => 116320,
                'party'         => 'ES-AIR',
                'maker'         => 'Ethan',
                'active'        => false,
                'fields_map'    => [
                    'pm25'          => 'field1',
                    'humidity'      => 'field4',
                    'temperature'   => 'field5',
                ],
            ],
            [
                'channel'       => 101099,
                'party'         => 'ES-AIR',
                'maker'         => 'CCU_ME',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field1',
                    'humidity'      => 'field5',
                    'temperature'   => 'field6',
                ],
            ],
            [
                'channel'       => 83205,
                'party'         => 'KS-001',
                'maker'         => 'D.T.Shaw',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field7',
                    'humidity'      => 'field5',
                    'temperature'   => 'field4',
                ],
            ],
            [
                'channel'       => 203701,
                'party'         => 'IOTF4',
                'maker'         => '陳弘歷',
                'active'        => false,
                'fields_map'    => [
                    'pm25'          => 'field1',
                    'humidity'      => 'field3',
                    'temperature'   => 'field2',
                ],
            ],
            [
                'channel'       => 206063,
                'party'         => 'Home Sense',
                'maker'         => '廖晨凱',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field4',
                    'humidity'      => 'field2',
                    'temperature'   => 'field1',
                ],
            ],
            [
                'channel'       => 213273,
                'party'         => 'Dust sensor',
                'maker'         => 'bbn',
                'active'        => false,
                'fields_map'    => [
                    'pm25'          => 'field1',
                    'pm10'          => 'field2',
                ],
            ],
            [
                'channel'       => 160837,
                'party'         => '龜山楓樹村',
                'maker'         => 'CasperYang',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field5',
                    'humidity'      => 'field2',
                    'temperature'   => 'field1',
                ],
            ],
            [
                'channel'       => 251957,
                'party'         => 'RPi HSS',
                'maker'         => 'hikoyoshi',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field3',
                    'humidity'      => 'field2',
                    'temperature'   => 'field1',
                ],
            ],
            [
                'channel'       => 253706,
                'party'         => 'Dean Yang',
                'maker'         => 'Dean Yang',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field4',
                    'humidity'      => 'field2',
                    'temperature'   => 'field1',
                ],
            ],
            [
                'channel'       => 228097,
                'party'         => 'STUCOM2',
                'maker'         => '莊世彰',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field3',
                    'humidity'      => 'field2',
                    'temperature'   => 'field1',
                ],
            ],
            [
                'channel'       => 278771,
                'party'         => 'NII_317',
                'maker'         => 'Jason Liang',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field1',
                    'humidity'      => 'field3',
                    'temperature'   => 'field2',
                ],
            ],
            [
                'channel'       => 298800,
                'party'         => 'Freeman Lee',
                'maker'         => 'Freeman Lee',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field4',
                    'humidity'      => 'field2',
                    'temperature'   => 'field1',
                ],
            ],
            [
                'channel'       => 293178,
                'party'         => 'Maker',
                'maker'         => 'Maker',
                'active'        => true,
                'fields_map'    => [
                    'pm25'          => 'field1',
                    'humidity'      => 'field4',
                    'temperature'   => 'field3',
                ],
            ],
        ];

        foreach ($sites as $site) {
            Thingspeak::create([
                'channel'     => $site['channel'],
                'party'       => $site['party'],
                'maker'       => $site['maker'],
                'fields_map'  => collect($site['fields_map']),
                'active'      => isset($site['active']) ? $site['active'] : true,
                'group_id'    => $group->id,
            ]);
        }
    }
}
