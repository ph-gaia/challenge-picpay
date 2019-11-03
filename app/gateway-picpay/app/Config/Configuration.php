<?php

namespace App\Config;

class Configuration
{
    // App configs
    const APP_VERSION = '1.0.0';
    // WARNING: CHANGE THIS CONTENT
    const SALT_KEY = 'N82NHS8MCW8TJLT41O0EJLB71B4409SD8KQG3PS6CRPL39GUO60YH1W93Q3SPLXO';
    const BASE_DIR = __DIR__;
    const DS = DIRECTORY_SEPARATOR;
    const USER_ENTITY = "users";
    const JSON_SCHEMA = self::DS . 'app' . self::DS . 'Json_schemes';
    /**
     * 8 hours in seconds
     */
    const EXPIRATE_TOKEN = 28800;
    const HOST_DEV = '192.168.0.7:4000';
}