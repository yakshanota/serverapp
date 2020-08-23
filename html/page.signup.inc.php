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



    $user_username = '';

    $user_email = '';

    $user_fullname = '';

    $user_referrer = 0;

    $artist='';
    $mela='';

    $error = false;

    $error_message = array();



    if (!empty($_POST)) {



        $error = false;



        //$user_username = isset($_POST['username']) ? $_POST['username'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

        $user_fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';

        $user_password = isset($_POST['password']) ? $_POST['password'] : '';
        $artist = isset($_POST['artist']) ? $_POST['artist'] : '';
        $mela = isset($_POST['mela']) ? $_POST['mela'] : '';

        //$user_email = isset($_POST['email']) ? $_POST['email'] : '';

        $user_referrer = isset($_POST['referrer']) ? $_POST['referrer'] : 0;

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';



        $user_referrer = helper::clearInt($user_referrer);



       // $user_username = helper::clearText($user_username);
        $phone = helper::clearText($phone);

        $user_fullname = helper::clearText($user_fullname);

        $user_password = helper::clearText($user_password);
        $artist = helper::clearText($artist);
        $mela = helper::clearText($mela);

        //$user_email = helper::clearText($user_email);



        //$user_username = helper::escapeText($user_username);
        $phone = helper::escapeText($phone);

        $user_fullname = helper::escapeText($user_fullname);

        $user_password = helper::escapeText($user_password);

        //$user_email = helper::escapeText($user_email);



        if (auth::getAuthenticityToken() !== $token) {



            $error = true;

            $error_token = true;

            $error_message[] = $LANG['msg-error-unknown'];

        }



       /* if (!helper::isCorrectLogin($user_username)) {



            $error = true;

            $error_username = true;

            $error_message[] = $LANG['msg-login-incorrect'];

        }



        if ($helper->isLoginExists($user_username)) {



            $error = true;

            $error_username = true;

            $error_message[] = $LANG['msg-login-taken'];

        }*/
         if ($helper->phone_verify($phone)) {



            $error = true;

            $error_username = true;

           
            $error_message[] = $LANG['msg-login-mobile-taken'];

        }


        if (!helper::isCorrectFullname($user_fullname)) {



            $error = true;

            $error_fullname = true;

            $error_message[] = $LANG['msg-fullname-incorrect'];

        }



        if (!helper::isCorrectPassword($user_password)) {



            $error = true;

            $error_password = true;

            $error_message[] = $LANG['msg-password-incorrect'];

        }



       /* if (!helper::isCorrectEmail($user_email)) {



            $error = true;

            $error_email = true;

            $error_message[] = $LANG['msg-email-incorrect'];

        }*/



        /*if ($helper->isEmailExists($user_email)) {



            $error = true;

            $error_email = true;

            $error_message[] = $LANG['msg-email-taken'];

        }*/



        if (!$error) {


//echo "<pre/>"; print_r($_POST); die;
            $account = new account($dbo);



            $result = array();

            //$result = $account->signup($user_username, $user_fullname, $user_password, $user_email, $LANG['lang-code']);
            $result = $account->signup($phone, $user_fullname, $user_password, $artist, $mela,  $LANG['lang-code']);



            if ($result['error'] === false) {



                $clientId = 0; // Desktop version



                $auth = new auth($dbo);

                $access_data = $auth->create($result['accountId'], $clientId, APP_TYPE_WEB, "", $LANG['lang-code']);



                $account = new account($dbo, $access_data['accountId']);

                $accountInfo = $account->get();



                if ($access_data['error'] === false) {



                    auth::setSession($access_data['accountId'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verified'], $accountInfo['access_level'], $access_data['accessToken']);

                    auth::updateCookie($phone, $access_data['accessToken']);



                    $language = $account->getLanguage();



                    $account->setState(ACCOUNT_STATE_ENABLED);



                    $account->setLastActive();



                    // refsys



                    if ($user_referrer != 0) {



                        $ref = new refsys($dbo);

                        $ref->setRequestFrom($account->getId());

                        $ref->setBonus(BONUS_REFERRAL);

                        $ref->setReferrer($user_referrer);



                        unset($ref);

                    }



                    //Facebook connect



                    if (isset($_SESSION['oauth']) && $_SESSION['oauth'] === 'facebook' && $helper->getUserIdByFacebook($_SESSION['oauth_id']) == 0) {



                        $account->setFacebookId($_SESSION['oauth_id']);



                        $time = time();

                        $fb_id = $_SESSION['oauth_id'];



                        $img = @file_get_contents('https://graph.facebook.com/'.$fb_id.'/picture?type=large');

                        $file =  TEMP_PATH.$time.".jpg";

                        @file_put_contents($file, $img);



                        $imglib = new imglib($dbo);

                        $response = $imglib->createPhoto($file, $file);

                        unset($imglib);



                        if ($response['error'] === false) {



                            $account->setPhoto($response);

                        }



                        unset($_SESSION['oauth']);

                        unset($_SESSION['oauth_id']);

                        unset($_SESSION['oauth_name']);

                        unset($_SESSION['oauth_email']);

                        unset($_SESSION['oauth_link']);



                    } else {



                        $account->setFacebookId("");

                    }



                    $_SESSION['welcome_hash'] = helper::generateHash(5);



                    header("Location: /account/welcome");

                    exit;

                }



            } else {



                $error = true;

                $error_message[] = "You can not create multi-accounts!";

            }

        }

    }



    if (isset($_SESSION['oauth']) && empty($user_username) && empty($user_email)) {



        $user_fullname = $_SESSION['oauth_name'];

        $user_email = $_SESSION['oauth_email'];

    }



    auth::newAuthenticityToken();



    $page_id = "signup";



    $css_files = array("landing.css", "my.css");

    $page_title = $LANG['page-signup']." | ".APP_TITLE;



    include_once("../html/common/header.inc.php");



?>



<body class="home signup-page">



    <?php



        include_once("../html/common/topbar.inc.php");

    ?>



    <div class="content-page">



        <div class="limiter">



            <div class="container-login100">



                <div class="wrap-login100">



                    <form accept-charset="UTF-8" action="/signup" class="custom-form login100-form" id="signup-form" method="post">



                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">



                        <span class="login100-form-title "><?php echo $LANG['page-signup']; ?></span>



                        <?php



                        if (isset($_SESSION['oauth'])) {



                            ?>



                            <div class="opt-in">

                                <label for="user_receive_digest">

                                    <?php



                                    $headers = get_headers('https://graph.facebook.com/'.$_SESSION['oauth_id'].'/picture',1);



                                    if (isset($headers['Location'])) {



                                        $url = $headers['Location']; // string



                                        ?>



                                        <img src="<?php echo $url; ?>" alt="" style="width: 50px; float: left">



                                        <?php



                                    } else {



                                        ?>



                                        <img src="\img\profile_default_photo.png" alt="" style="width: 50px; float: left">



                                        <?php

                                    }

                                    ?>



                                    <div style="padding-left: 60px;">

                                        <b><a target="_blank" href="https://www.facebook.com/app_scoped_user_id/<?php echo $_SESSION['oauth_id']; ?>"><?php echo $_SESSION['oauth_name']; ?></a></b>

                                        <span><?php echo $LANG['label-authorization-with-facebook']; ?></span>

                                        <br>

                                        <a href="/facebook"><?php echo $LANG['action-back-to-default-signup']; ?></a>

                                    </div>

                                </label>

                            </div>



                            <?php



                        } else {



                            if (FACEBOOK_AUTHORIZATION) {



                                

                            }

                        }



                        ?>



                        <div class="errors-container" style="<?php if (!$error) echo "display: none"; ?>">

                            <p class="title"><?php echo $LANG['label-errors-title']; ?></p>

                            <ul>

                                <?php



                                foreach ($error_message as $key => $value) {



                                    echo "<li>{$value}</li>";

                                }

                                ?>

                            </ul>

                        </div>



                        <!-- <input id="username" name="username" placeholder="<?php echo $LANG['label-username']; ?>" required="required" size="30" type="text" value="<?php echo $user_username; ?>">  -->

                        <input id="phone" name="phone" placeholder="<?php echo $LANG['label-mobile-number']; ?>" required="required" size="30" type="number" value="<?php echo $phone; ?>" onKeyPress="if(this.value.length==10) return false;" minlength="10" maxlength="10"> 
                        <span id="phone_error"></span>

                        <input id="fullname" name="fullname" placeholder="<?php echo $LANG['label-fullname']; ?>" required="required" size="30" type="text" value="<?php echo $user_fullname; ?>">

                        <input id="password" name="password" placeholder="<?php echo $LANG['label-password']; ?>" required="required" size="30" type="password" value="">
                        
                        <p class="artist_list">ನೀವು ಕಲಾವಿದರ? :
                         <span>
                          <input type="radio" id="artistYes" name="artist" value="Yes">
                             <label for="artist">ಹೌದು</label>
                        </span>
                        <span>
                          <input type="radio" id="artistNo" name="artist" value="No">
                          <label for="artist">ಅಲ್ಲ</label>
                        </span>
                        <span id="artist_error"></span>
                        </p>
                        <div class="artist_select">
                         <select id="mela" name="mela" style="display: none;" required="required">
                             <option value="">ಯಾವ ಮೇಳದಲಿರುವಿರಿ ?</option>
                             <option value="ಧರ್ಮಸ್ಥಳ">ಧರ್ಮಸ್ಥಳ</option>
                             <option value="ಮ೦ದಾರ್ತಿ">ಮ೦ದಾರ್ತಿ</option>
                             <option value="ಪೆರ್ಡೂರು">ಪೆರ್ಡೂರು</option>
							  <option value="ಇತರೆ ">ಇತರೆ </option>
                         </select> 
                        </div>
                      <!--   <input id="email" name="email" placeholder="<?php echo $LANG['label-email']; ?>" required="required" size="48" type="text" value="<?php echo $user_email; ?>"> -->



                       



                       

                      <div>

                        <input class="submit-button blue button" name="commit" type="submit" id="submitButton" value="<?php echo $LANG['action-signup']; ?>">
                    </div>
                    </form>



                    <div class="login100-more">

                        <div class="login100_content">

                            <h1 class="mb-10"><?php echo $LANG['label-signup-sub-title']; ?></h1>

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

 <script>
$(document).ready(function(){
$('#artistYes').click(function () {
		$("#artist_error").html("");
        $('#mela').prop('disabled', false);
        $("#mela").show();
  });
$('#artistNo').click(function () {
		$("#artist_error").html("");
        $("#mela").hide();
        $('#mela').prop('disabled', true);
  });  

  
  });
</script>

<script>
$("#submitButton").click(function(){

	var radioValue = $("input[name='artist']:checked").val();
	if(radioValue){
		$("#artist_error").html("");
		return true;
	}else{
		 
		 $("#artist_error").html("<div style='color:red;'>You must select artist Yes or No.</div>");
        return false;
	}

});

</script>