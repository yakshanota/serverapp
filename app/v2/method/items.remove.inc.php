<?php

/*!
 * ifsoft.co.uk
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * qascript@ifsoft.co.uk
 *
 * Copyright 2012-2019 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;

    $accountId = helper::clearInt($accountId);

    $itemId = helper::clearInt($itemId);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $posts = new post($dbo);
    $posts->setRequestFrom($accountId);

    $result = $posts->remove($itemId);

    echo json_encode($result);
    exit;
}
