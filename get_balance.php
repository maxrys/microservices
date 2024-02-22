<?php

########################################
### Copyright © 2024 Maxim Rysevets. ###
########################################

namespace microservices;

require_once('init.php');

$uid = $_GET['uid'] ?? '';

if ((string)$uid !== (string)(int)$uid || (int)$uid < 1) {
    ResponseJSON::printAndExit('uid error', ResponseJSON::EXIT_STATE_ERROR);
}

if (User::get_transactions_count($uid) === 0) {
    ResponseJSON::printAndExit('no transactions for this uid', ResponseJSON::EXIT_STATE_ERROR);
}

ResponseJSON::printAndExit(
    User::get_balance($uid)
);