<?php

namespace App\Datasource;

interface Contract {
    /**
     * feed resource provider
     *
     * @return array or string
     */
    public static function feedResource();

    /**
     * parse raw feed to record
     *
     * @param array $raw  raw feed
     * @return App\Models\Record
     */
    public static function parse(array $raw);
    
}