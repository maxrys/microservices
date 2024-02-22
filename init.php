<?php

########################################
### Copyright © 2024 Maxim Rysevets. ###
########################################

namespace microservices;

use const microservices\DB_PDO_CREDENTIALS_MYSQL;
use const microservices\DB_PDO_CREDENTIALS_SQLITE;

require_once('data/credentials.php');

spl_autoload_register(function ($class) {
    require_once('classes/'.str_replace('\\', '/', $class).'.php');
});

Database::init(
    DB_PDO_CREDENTIALS_SQLITE
);

header('access-control-allow-methods: post, get');
header('access-control-allow-origin: *');
header('access-control-allow-headers: *');
header('access-control-allow-credentials: true');
