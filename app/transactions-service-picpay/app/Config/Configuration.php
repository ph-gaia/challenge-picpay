<?php

namespace App\Config;

class Configuration
{
    const BASE_HOST = '192.168.0.7';
    const BASE_PORT = '4000';
    const HOST_DEV = self::BASE_HOST . ':' . self::BASE_PORT;
}