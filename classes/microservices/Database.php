<?php

########################################
### Copyright © 2024 Maxim Rysevets. ###
########################################

namespace microservices;

use Exception;
use PDO;
use PDOStatement;

abstract class Database {

    private static $connection;
    private static $driver;

    static function init($credentials) {
        try {
            if ($credentials['driver'] === 'mysql') {
                static::$connection = new PDO(
                    $credentials['driver'  ].':host='.
                    $credentials['host'    ].';port='.
                    $credentials['port'    ].';dbname='.
                    $credentials['database'].';charset=utf8',
                    $credentials['login'   ],
                    $credentials['password']
                );
            }
            if ($credentials['driver'] === 'sqlite') {
                static::$connection = new PDO(
                    $credentials['driver'].':'.
                    $credentials['file'  ]
                );
            }
            static::$driver = $credentials['driver'];
            return true;
        } catch (Exception $e) {
            ResponseJSON::printAndExit(
                $e->getMessage().' | '.$e->getCode(), ResponseJSON::EXIT_STATE_ERROR
            );
        }
    }

    static function get_connection_state() {
        return static::$connection instanceof PDO;
    }

    static function get_driver() {
        return static::$driver;
    }

    static function get_query_result($query, $args = [], $col_num = null) {
        try {
            $statement = static::$connection->prepare($query);
            if ($statement instanceof PDOStatement) {
                $statement->execute($args);
                if ($statement->errorInfo()[0] === PDO::ERR_NONE) {
                    if ($col_num !== null) return $statement->fetchColumn($col_num);
                    if ($col_num === null) return $statement->fetchAll();
                } else throw new Exception('pdo execute error');
            }     else throw new Exception('pdo prepare error');
        } catch (Exception $e) {
            ResponseJSON::printAndExit(
                $e->getMessage().' | '.$e->getCode(), ResponseJSON::EXIT_STATE_ERROR
            );
        }
    }

}