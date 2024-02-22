<?php

########################################
### Copyright © 2024 Maxim Rysevets. ###
########################################

namespace microservices;

use microservices\Database;

abstract class User {

    static function get_all_uid() {
        # find all possible users by payments
        $query = 'SELECT DISTINCT `paid_to` as `uid` FROM `transactions` UNION '.
                 'SELECT DISTINCT `paid_by` as `uid` FROM `transactions`'.
                 'ORDER BY `uid`';
        return Database::get_query_result($query);
    }

    static function get_transactions_count($uid) {
        $query = 'SELECT (SELECT count(*) FROM `transactions` WHERE `paid_by` = :uid) + '.
                        '(SELECT count(*) FROM `transactions` WHERE `paid_to` = :uid)';
        return Database::get_query_result($query, ['uid' => $uid], 0);
    }

    static function get_balance($uid) {
        $query = 'SELECT (SELECT IFNULL( SUM(`amount`), 0) FROM `transactions` WHERE `paid_to` = :uid) + '.
                        '(SELECT IFNULL(-SUM(`amount`), 0) FROM `transactions` WHERE `paid_by` = :uid)';
        return Database::get_query_result($query, ['uid' => $uid], 0);
    }

}