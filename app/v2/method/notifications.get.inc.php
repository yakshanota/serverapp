<?php

/*!
 * ifsoft.co.uk
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2019 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $notifyId = isset($_POST['notifyId']) ? $_POST['notifyId'] : 0;

    $notifyId = helper::clearInt($notifyId);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $account = new profile($dbo, $accountId);
    $account->setLastNotifyView();

    $notify = new notify($dbo);
    $notify->setRequestFrom($accountId);
    $result = $notify->getAll($notifyId);

    echo json_encode($result);
    exit;
}
