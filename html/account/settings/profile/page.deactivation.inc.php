<?php

    /*!
     * ifsoft.co.uk
     *
     * http://ifsoft.com.ua, http://ifsoft.co.uk
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2019 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $accountId = auth::getCurrentUserId();

    $error = false;

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $password = isset($_POST['pswd']) ? $_POST['pswd'] : '';

        $password = helper::clearText($password);
        $password = helper::escapeText($password);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if ( !$error ) {

            $account = new account($dbo, $accountId);

            $result = array();

            $result = $account->deactivation($password);

            if ($result['error'] === false) {

                header("Location: /logout/?access_token=".auth::getAccessToken());
                exit;
            }
        }

        header("Location: /account/settings/profile/deactivation/?error=true");
        exit;
    }

    auth::newAuthenticityToken();

    $page_id = "settings_deactivation";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-profile-deactivation']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");
?>

<body class="settings-page">

    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column">

            <div class="main-content">

                <div class="profile-content standard-page">

                    <h1><?php echo $LANG['page-profile-deactivation']; ?></h1>

                    <form accept-charset="UTF-8" action="/account/settings/profile/deactivation" autocomplete="off" class="edit_user" id="settings-form" method="post">

                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                        <div class="tabbed-content">

                            <div class="tab-container">
                                <nav class="tabs">
                                    <a href="/account/settings/profile"><span class="tab"><?php echo $LANG['page-profile-settings']; ?></span></a>
                                    <a href="/account/settings/privacy"><span class="tab"><?php echo $LANG['label-privacy']; ?></span></a>
                                    <a href="/account/settings/services"><span class="tab"><?php echo $LANG['label-services']; ?></span></a>
                                    <a href="/account/settings/profile/password"><span class="tab"><?php echo $LANG['label-password']; ?></span></a>
                                    <a href="/account/balance"><span class="tab"><?php echo $LANG['page-balance']; ?></span></a>
                                    <a href="/account/settings/referrals"><span class="tab"><?php echo $LANG['page-referrals']; ?></span></a>
                                    <a href="/account/settings/blacklist"><span class="tab"><?php echo $LANG['label-blacklist']; ?></span></a>
                                    <a href="/account/settings/profile/deactivation"><span class="tab active"><?php echo $LANG['action-deactivation-profile']; ?></span></a>

                                </nav>
                            </div>

                            <div class="warning-container">
                                <ul>
                                    <?php echo $LANG['page-profile-deactivation-sub-title']; ?>
                                </ul>
                            </div>

                            <?php

                            if ( isset($_GET['error']) ) {

                                ?>

                                <div class="errors-container" style="margin-top: 15px;">
                                    <ul>
                                        <?php echo $LANG['msg-error-deactivation']; ?>
                                    </ul>
                                </div>

                                <?php
                            }
                            ?>

                            <div class="tab-pane active form-table">

                                <div class="profile-basics form-row">
                                    <div class="form-cell left">
                                        <p class="info"><?php echo $LANG['label-settings-deactivation-sub-title']; ?></p>
                                    </div>

                                    <div class="form-cell">
                                        <input id="pswd" name="pswd" placeholder="<?php echo $LANG['label-password']; ?>" maxlength="32" type="password" value="">
                                    </div>
                                </div>

                            </div>

                        </div>

                        <input style="margin-top: 25px" name="commit" class="button blue" type="submit" value="<?php echo $LANG['action-deactivation-profile']; ?>">

                    </form>
                </div>


            </div>
        </div>


    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

</body
</html>