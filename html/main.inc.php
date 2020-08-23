<?php



    /*!

     * ifsoft.co.uk

     *

     * http://ifsoft.com.ua, http://ifsoft.co.uk, https://raccoonsquare.com

     * raccoonsquare@gmail.com

     *

     * Copyright 2012-2019 Demyanchuk Dmitry (raccoonsquare@gmail.com)

     */



    if (auth::isSession()) {



        header("Location: /account/stream");

    }



    $user_username = '';
    $phone = '';



    $error = false;

    $error_message = '';



    if (!empty($_POST)) {



        //$user_username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';



        //$user_username = helper::clearText($user_username);
        $phone = helper::clearText($phone);

        $user_password = helper::clearText($user_password);



        $phone = helper::escapeText($phone);

        $user_password = helper::escapeText($user_password);



        if (auth::getAuthenticityToken() !== $token) {



            $error = true;

        }



        if (!$error) {



            $access_data = array();



            $account = new account($dbo);



            $access_data = $account->signin($phone, $user_password);



            unset($account);



            if (!$access_data['error']) {



                $account = new account($dbo, $access_data['accountId']);

                $accountInfo = $account->get();



                //print_r($accountInfo);



                switch ($accountInfo['state']) {



                    case ACCOUNT_STATE_BLOCKED: {



                        break;

                    }



                    default: {



                        $account->setState(ACCOUNT_STATE_ENABLED);



                        $clientId = 0; // Desktop version



                        $auth = new auth($dbo);

                        $access_data = $auth->create($accountInfo['id'], $clientId, APP_TYPE_WEB, "", $LANG['lang-code']);



                        if (!$access_data['error']) {



                            auth::setSession($access_data['accountId'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verified'], $accountInfo['access_level'], $access_data['accessToken']);

                            auth::updateCookie($user_username, $access_data['accessToken']);



                            unset($_SESSION['oauth']);

                            unset($_SESSION['oauth_id']);

                            unset($_SESSION['oauth_name']);

                            unset($_SESSION['oauth_email']);

                            unset($_SESSION['oauth_link']);



                            $account->setLastActive();



                            header("Location: /");

                        }

                    }

                }



            } else {



                $error = true;

            }

        }

    }



    auth::newAuthenticityToken();



    $page_id = "main";



    $css_files = array("landing.css", "my.css");

    $page_title = APP_TITLE;



    include_once("../html/common/header.inc.php");



?>



<body class="home">



    <?php



        include_once("../html/common/topbar.inc.php");

    ?>



    <div class="content-page">



        <div class="limiter">

            <div class="container-login100">

                <div class="wrap-login100">



                    <form accept-charset="UTF-8" action="/" class="custom-form login100-form" id="login-form" method="post">



                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">



                        <span class="login100-form-title "><?php echo $LANG['page-login']; ?></span>



                       



                        <div class="errors-container" style="<?php if (!$error) echo "display: none"; ?>">

                            <p class="title"><?php echo $LANG['label-errors-title']; ?></p>

                            <ul>

                                <li><?php echo $LANG['msg-error-authorize']; ?></li>

                            </ul>

                        </div>



                        <!-- <input id="username" name="user_username" placeholder="<?php echo $LANG['label-username']; ?>" required="required" size="30" type="text" value="<?php echo $user_username; ?>"> -->

                          <input id="phone" name="phone" placeholder="<?php echo $LANG['label-mobile-number']; ?>" required="required" size="30" type="number" value="<?php echo $phone; ?>"  minlength="10" maxlength="10" onKeyPress="if(this.value.length==10) return false;" > 
                           <span id="phone_error"></span>
                        <input id="password" name="user_password" placeholder="<?php echo $LANG['label-password']; ?>" required="required" size="30" type="password" value="">



                        <div class="login-button">

                            <input style="margin-right: 10px" class="submit-button button blue" name="commit" type="submit" value="<?php echo $LANG['action-login']; ?>">

                            <a href="/remind" class="help"><?php echo $LANG['action-forgot-password']; ?></a>

                        </div>

                    </form>



                    <div class="login100-more">

                        <div class="login100_content">

                            

                            <p><?php echo $LANG['main-page-prompt-app']; ?></p>

                        </div>

                    </div>



                </div>



            </div>



            <?php



                include_once("../html/common/footer.inc.php");

            ?>



        </div>





    </div>







</body

</html>


<script>
     $("#phone").keyup(function(event) {
            var input_data = $('#phone').val();
            if(input_data.length>=10 && input_data.length<=10){
                $("#phone_error").html("");
                 $(':input[type="submit"]').prop('disabled', false);
            }else{
               
                $("#phone_error").html("<div style='color:red; font-size: 13px;'>Mobile No must be 10 digits.</div>");
                 $(':input[type="submit"]').prop('disabled', true);
            }
        });
        
     
 </script>