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



        header("Location: /account/wall");

    }



    $email = '';
    $phone='';


    $error = false;

    $error_message = array();

    $sent = false;



    if ( isset($_GET['sent']) ) {



        $sent = isset($_GET['sent']) ? $_GET['sent'] : 'false';



        if ($sent === 'success') {



            $sent = true;



        } else {



            $sent = false;

        }

    }



    if (!empty($_POST)) {


//echo "<pre/>"; print_r($_POST); die;
        //$email = isset($_POST['email']) ? $_POST['email'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';



        /*$email = helper::clearText($email);
        $email = helper::escapeText($email);*/

        $phone = helper::clearText($phone);
        $phone = helper::escapeText($phone);



        if (auth::getAuthenticityToken() !== $token) {



            $error = true;

            $error_message[] = $LANG['msg-error-unknown'];

        }



       /* if (!helper::isCorrectEmail($email)) {



            $error = true;

            $error_message[] = $LANG['msg-email-incorrect'];

        }
*/


        if ( !$error && !$helper->isPhoneExists($phone) ) {



            $error = true;

            $error_message[] = $LANG['msg-phone-not-found'];

        }



        if (!$error) {



            $accountId = $helper->getUserIdByPhone($phone);



            if ($accountId != 0) {



                $account = new account($dbo, $accountId);



                $accountInfo = $account->get();



                if ($accountInfo['error'] === false && $accountInfo['state'] != ACCOUNT_STATE_BLOCKED) {



                    $clientId = 0; // Desktop version



                    $restorePointInfo = $account->restorePointCreate($phone, $clientId);



                    ob_start();



                    ?>



                   <!--  <html>

                    <body>

                    This is link <a href="<?php echo APP_URL;  ?>/restore/?hash=<?php echo $restorePointInfo['hash']; ?>"><?php echo APP_URL;  ?>/restore/?hash=<?php echo $restorePointInfo['hash']; ?></a> to reset your password.

                    </body>

                    </html>  -->



                    <?php

                   $url= APP_URL;

                 // $message= "This is link <a href=' $url/restore/?hash=$restorePointInfo[hash] ' $url/restore/?hash=$restorePointInfo[hash]> to reset your password </a>";
                  $textmsg="This is  reset your password link $url/restore/?hash=$restorePointInfo[hash] ";
                  
                
                  $helper->sendSms($phone,$textmsg);
                   /* $from = SMTP_EMAIL;



                    $to = $email;



                    $html_text = ob_get_clean();



                    $subject = APP_TITLE." | Password reset";



                    $mail = new phpmailer();



                    $mail->isSMTP();                                      // Set mailer to use SMTP

                    $mail->Host = SMTP_HOST;                               // Specify main and backup SMTP servers

                    $mail->SMTPAuth = SMTP_AUTH;                               // Enable SMTP authentication

                    $mail->Username = SMTP_USERNAME;                      // SMTP username

                    $mail->Password = SMTP_PASSWORD;                      // SMTP password

                    $mail->SMTPSecure = SMTP_SECURE;                            // Enable TLS encryption, `ssl` also accepted

                    $mail->Port = SMTP_PORT;                                    // TCP port to connect to



                    $mail->From = $from;

                    $mail->FromName = APP_TITLE;

                    $mail->addAddress($to);                               // Name is optional



                    $mail->isHTML(true);                                  // Set email format to HTML



                    $mail->Subject = $subject;

                    $mail->Body    = $html_text;



                    $mail->send();
*/
                }

            }



            $sent = true;

            header("Location: /remind/?sent=success");

        }

    }



    auth::newAuthenticityToken();



    $page_id = "restore";



    $css_files = array("landing.css", "my.css");

    $page_title = $LANG['page-restore']." | ".APP_TITLE;



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



                    <div class="standard-page">



                        <?php



                        if ($sent) {



                            ?>



                            <h1><?php echo $LANG['page-restore']; ?></h1>



                            <div class="opt-in">

                                <label for="user_receive_digest">

                                    <b><?php echo $LANG['msg-reset-password-sent']; ?></b>

                                </label>

                            </div>



                            <?php



                        } else {



                            ?>



                            <h1><?php echo $LANG['page-restore']; ?></h1>



                            <form accept-charset="UTF-8" action="/remind" class="custom-form" id="remind-form" method="post">



                                <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">



                                <div class="errors-container" style="<?php if (!$error) echo "display: none"; ?>">

                                    <p class="title"><?php echo $LANG['label-errors-title']; ?></p>

                                    <ul>

                                        <li>

                                            <?php

                                                if (count($error_message) != 0) {



                                                    echo $error_message[0];

                                                }

                                            ?>

                                        </li>

                                    </ul>

                                </div>



                             <!--    <input id="email" name="email" placeholder="<?php echo $LANG['label-email']; ?>" required="required" size="30" type="text" value="<?php echo $email; ?>"> -->

                                  <input id="phone" name="phone" placeholder="<?php echo $LANG['label-mobile-number']; ?>" required="required" size="30" type="number" value="<?php echo $phone; ?>" onKeyPress="if(this.value.length==10) return false;" minlength="10" maxlength="10"> 
                                  <span id="phone_error"></span>
                                <div class="login-button">

                                    <input name="commit" type="submit" class="blue button" value="<?php echo $LANG['action-next']; ?>">

                                </div>



                            </form>



                            <?php



                        }

                        ?>

                    </div>



                </div>



            </div>



            <?php



                include_once("../html/common/footer.inc.php");

            ?>



        </div>





    </div>







</body>

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