<?php

########################################
### Copyright © 2024 Maxim Rysevets. ###
########################################

namespace microservices;

abstract class ResponseJSON {

    const EXIT_STATE_OK      = 0b00;
    const EXIT_STATE_WARNING = 0b01;
    const EXIT_STATE_ERROR   = 0b10;

    static function printAndExit($data, $state = self::EXIT_STATE_OK) {
        header('content-type: application/json');
        if ($state === static::EXIT_STATE_OK     ) print json_encode(['status' => 'ok',      'data' => $data]);
        if ($state === static::EXIT_STATE_WARNING) print json_encode(['status' => 'warning', 'data' => $data]);
        if ($state === static::EXIT_STATE_ERROR  ) print json_encode(['status' => 'error',   'data' => $data]);
        exit();
    }

}