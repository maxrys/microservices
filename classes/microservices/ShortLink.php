<?php

########################################
### Copyright © 2024 Maxim Rysevets. ###
########################################

namespace microservices;

use microservices\Database;

abstract class ShortLink {

    const LINK_HASH_LENGTH = 6;
    const LINK_MAX_LENGTH = 2047;

    static function get($hash) {
        $query = 'SELECT `url` FROM `short_links` WHERE `id` = :id';
        return Database::get_query_result($query, ['id' => $hash], 0) ?: '';
    }

    static function generateAndAdd($link) {
        $db_type = Database::get_driver();
        $hash = static::hash_get($link);
        if ($db_type === 'sqlite')
             $query = 'INSERT OR IGNORE INTO `short_links` (`id`, `link`) VALUES (:id, :link)';
        else $query = 'INSERT    IGNORE INTO `short_links` (`id`, `link`) VALUES (:id, :link)';
        Database::get_query_result($query, ['id' => $hash, 'link' => $link]);
        return $hash;
    }

    static function hash_get($link) {
        return substr(str_replace(['+', '/'], '', base64_encode(md5($link))), 0, static::LINK_HASH_LENGTH);
    }

}