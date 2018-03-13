<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLatestRecordsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW latest_records");

        DB::statement("CREATE VIEW latest_records AS
            SELECT
            records.id as record_id, records.uuid as uuid, records.name as name,
            records.maker as maker, records.lat as lat, records.lng as lng,
            records.group_id as group_id, records.pm25 as pm25, records.humidity as humidity, records.temperature as temperature,
            records.published_at as published_at,
            groups.name as group_name
            FROM records
            JOIN (select max(id) AS id from records group by group_id, `uuid`) as ids on records.id = ids.id
            JOIN groups on groups.id = records.group_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW latest_records");

        DB::statement("CREATE VIEW latest_records AS
            select `airmap`.`records`.`id` AS `record_id`,`airmap`.`records`.`uuid` AS `uuid`,`airmap`.`records`.`name` AS `name`,`airmap`.`records`.`maker` AS `maker`,
            `airmap`.`records`.`lat` AS `lat`,`airmap`.`records`.`lng` AS `lng`,`airmap`.`records`.`group_id` AS `group_id`,`airmap`.`groups`.`name` AS `group_name`,
            `airmap`.`records`.`pm25` AS `pm25`,`airmap`.`records`.`humidity` AS `humidity`,`airmap`.`records`.`temperature` AS `temperature`,
            `airmap`.`records`.`published_at` AS `published_at`,`airmap`.`site_geometries`.`geometry_id` AS `geometry_id`
            from (((`airmap`.`records`
            join (
                select max(`airmap`.`records`.`id`) AS `id` from `airmap`.`records`
                group by `airmap`.`records`.`group_id`,`airmap`.`records`.`uuid`) `ids`
                on((`airmap`.`records`.`id` = `ids`.`id`))
            )
            join `airmap`.`groups`
                on((`airmap`.`records`.`group_id` = `airmap`.`groups`.`id`))
            )
            left join `airmap`.`site_geometries`
                on(( (`airmap`.`records`.`uuid` = `airmap`.`site_geometries`.`uuid`) and (`airmap`.`records`.`group_id` = `airmap`.`site_geometries`.`group_id`) ))
            )");
    }
}
