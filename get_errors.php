<?php

########################################
### Copyright © 2024 Maxim Rysevets. ###
########################################

namespace microservices;

require_once('init.php');

if (isset($_GET['type'])) {

    switch ($_GET['type']) {
        case 'not_200':
         // header('Location: http://example.com');
         // header('203 Non-Authoritative Information');
            header('HTTP/1.1 403 Forbidden');
            print '403 Forbidden';
            exit();
        case 'empty_json':
            header('content-type: application/json');
            print '';
            exit();
        case 'invalid_json':
            header('content-type: application/json');
            print 'invalid json';
            exit();
        case 'status_ok':
            header('content-type: application/json');
            print json_encode(['status' => 'ok', 'data' => 'ok body']);
            exit();
        case 'status_warning':
            header('content-type: application/json');
            print json_encode(['status' => 'warning', 'data' => 'warning body']);
            exit();
        case 'status_error':
            header('content-type: application/json');
            print json_encode(['status' => 'error', 'data' => 'error body']);
            exit();
        case 'status_unknown':
            header('content-type: application/json');
            print json_encode(['status' => 'unknown', 'data' => 'unknown body']);
            exit();
    }

}