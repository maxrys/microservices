<?php

########################################
### Copyright © 2024 Maxim Rysevets. ###
########################################

namespace microservices;

require_once('init.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    ResponseJSON::printAndExit(
        'A GET request was sent! The URL must be sent via a POST request.', ResponseJSON::EXIT_STATE_ERROR
    );
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');

    if (!json_validate($json)) {
        ResponseJSON::printAndExit('Invalid JSON!', ResponseJSON::EXIT_STATE_ERROR);
    }

    $data = json_decode($json);

    if (property_exists($data, 'link')) {
        if (strlen($data->link) > 1) {
            if (strlen($data->link) < ShortLink::LINK_MAX_LENGTH) {
                if (filter_var($data->link, FILTER_VALIDATE_URL)) {
                    ResponseJSON::printAndExit(
                        ShortLink::generateAndAdd($data->link)
                    );
                } else ResponseJSON::printAndExit('Link is incorrect!',             ResponseJSON::EXIT_STATE_ERROR);
            }     else ResponseJSON::printAndExit('Link is too long!',              ResponseJSON::EXIT_STATE_ERROR);
        }         else ResponseJSON::printAndExit('Link is empty!',                 ResponseJSON::EXIT_STATE_ERROR);
    }             else ResponseJSON::printAndExit('Link is not presenter in JSON!', ResponseJSON::EXIT_STATE_ERROR);
    
}