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



    if (auth::isSession()) {



        $ticket_email = "";

    }



    if (!empty($_POST)) {

          $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        if(isset($_POST['otp']))
        {
             $otp = isset($_POST['otp']) ? $_POST['otp'] : '';
            if($_SESSION['otp']==$otp)
            {
                $account = new account($dbo, $accountId);
                $result1 = $account->saveMobile($_SESSION['new_mobile']);

                unset($_SESSION['otp'],$_SESSION['new_mobile'],$_SESSION['old_mobile']);
                header("Location: /account/settings/profile/mobile/?verror=false");

            }else{
                header("Location: /account/settings/profile/mobile/?verror=true");
            }
            exit;
        }else{

      



        $old_mobile = isset($_POST['old_mobile']) ? $_POST['old_mobile'] : '';

        $new_mobile = isset($_POST['new_mobile']) ? $_POST['new_mobile'] : '';



        $old_mobile = helper::clearText($old_mobile);

        $new_mobile = helper::clearText($new_mobile);



        $old_mobile = helper::escapeText($old_mobile);

        $new_mobile = helper::escapeText($new_mobile);



        if (auth::getAuthenticityToken() !== $token) {



            $error = true;

        }



        if ( !$error ) {



            $account = new account($dbo, $accountId);



            if (auth::getCurrentUserLogin() === 'qascript' && APP_DEMO === true) {



                header("Location: /account/settings/profile/mobile/?error=demo");

                exit;

            }



            /*if ( helper::isCorrectPassword($new_password) ) {*/



                $result = array();



                $result = $account->setMobile($old_mobile, $new_mobile);

//echo "<pre/>"; print_r($result); die;

                if ( $result['error'] === false ) {



                    header("Location: /account/settings/profile/mobile/?error1=false");
                
                    exit;



                } else {



                    header("Location: /account/settings/profile/mobile/?error=old_mobile");

                    exit;

                }



            /*} else {



                header("Location: /account/settings/profile/mobile/?error=new_mobile");

               exit;

            }*/

        }



        header("Location: /account/settings/profile/mobile/?error=true");

        exit;

        }

    }



    auth::newAuthenticityToken();



    $page_id = "settings_mobile";



    $css_files = array("main.css", "my.css");

    $page_title = $LANG['page-profile-mobile']." | ".APP_TITLE;



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



                    <h1><?php echo $LANG['page-profile-mobile']; ?></h1>



                    <form accept-charset="UTF-8" action="/account/settings/profile/mobile" autocomplete="off" class="edit_user" id="settings-form" method="post">



                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">



                        <div class="tabbed-content">



                            <div class="tab-container">

                                <nav class="tabs">

                                    <a href="/account/settings/profile"><span class="tab"><?php echo $LANG['page-profile-settings']; ?></span></a>

                                    <a href="/account/settings/privacy"><span class="tab"><?php echo $LANG['label-privacy']; ?></span></a>

                                    <a href="/account/settings/services"><span class="tab"><?php echo $LANG['label-services']; ?></span></a>

                                    <a href="/account/settings/profile/password"><span class="tab"><?php echo $LANG['label-password']; ?></span></a>

                                    <a href="/account/settings/profile/mobile"><span class="tab active"><?php echo $LANG['label-mobile']; ?></span></a>

                                    <a href="/account/balance"><span class="tab"><?php echo $LANG['page-balance']; ?></span></a>

                                    <a href="/account/settings/referrals"><span class="tab"><?php echo $LANG['page-referrals']; ?></span></a>

                                    <a href="/account/settings/blacklist"><span class="tab"><?php echo $LANG['label-blacklist']; ?></span></a>

                                    <a href="/account/settings/profile/deactivation"><span class="tab"><?php echo $LANG['action-deactivation-profile']; ?></span></a>



                                </nav>

                            </div>



                            <?php



                            if ( isset($_GET['error']) ) {



                                switch ($_GET['error']) {



                                    case "true" : {



                                        ?>



                                        <div class="errors-container" style="margin-top: 15px;">

                                            <ul>

                                                <?php echo $LANG['msg-error-unknown']; ?>

                                            </ul>

                                        </div>



                                        <?php



                                        break;

                                    }



                                    case "old_mobile" : {



                                        ?>



                                        <div class="errors-container" style="margin-top: 15px;">

                                            <ul>

                                                <?php echo $LANG['msg-mobile-save-error']; ?>

                                            </ul>

                                        </div>



                                        <?php



                                        break;

                                    }



                                    case "new_mobile" : {



                                        ?>



                                        <div class="errors-container" style="margin-top: 15px;">

                                            <ul>

                                                <?php echo $LANG['msg-password-incorrect']; ?>

                                            </ul>

                                        </div>



                                        <?php



                                        break;

                                    }



                                    case "demo" : {



                                        ?>



                                        <div class="errors-container" style="margin-top: 15px;">

                                            <ul>

                                                Not available! This demo account!

                                            </ul>

                                        </div>



                                        <?php



                                        break;

                                    }



                                    default: {



                                        ?>



                                        <div class="success-container" style="margin-top: 15px;">

                                            <ul>

                                                <b><?php echo $LANG['label-thanks']; ?></b>

                                                <br>

                                                <?php echo $LANG['label-password-saved']; ?>

                                            </ul>

                                        </div>



                                        <?php



                                        break;

                                    }

                                }

                            }
                             if ( isset($_GET['verror']) ) {
                                if ($_GET['verror']=='true') {
                                ?>
                                 <div class="errors-container" style="margin-top: 15px;">

                                            <ul>

                                                Your otp not match.

                                            </ul>
                                        </div>

                             <?php 
                             }else{
                                ?>

                                 <div class="success-container" style="margin-top: 15px;">

                                            <ul>

                                                <b><?php echo $LANG['label-thanks']; ?></b>

                                                <br>

                                                Mobile number change successfully.

                                            </ul>

                                        </div>


                               <?php
                             }         

                             }


                            ?>

     <?php     
                     if ( isset($_GET['error1']) ) {

    ?> 
                <div class="success-container" style="margin-top: 15px;">

                                            <ul>

                                                

                                                <br>

                                                Send otp. Please check your mobile.

                                            </ul>

                                        </div>
<?php  } ?>

                            <div class="tab-pane active form-table">



                                <div class="profile-basics form-row">

                                    <div class="form-cell left">

                                        <p class="info"><?php echo $LANG['label-settings-mobile-sub-title']; ?></p>

                                    </div>

  <?php     
                     if ( isset($_GET['error1']) || $_GET['verror']=='true') {

    ?> 



                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">
                         <input id="otp" name="otp" placeholder="Enter otp" required="required" size="30" type="number" value=""  > 
                                       
                          <input style="margin-top: 25px" name="commit" class="button blue" type="submit" value="Verify & Change"> 



                 <?php  }else{ ?>  

                                    <div class="form-cell">

                                        <input id="phone" name="old_mobile" placeholder="<?php echo $LANG['label-mobile-number_old']; ?>" required="required" size="30" type="number" value="<?php echo $phone; ?>"  minlength="10" maxlength="10" onKeyPress="if(this.value.length==10) return false;" > 
                                        <span id="phone_error"></span>

                                        <input id="phone1" name="new_mobile" placeholder="<?php echo $LANG['label-mobile-number_new']; ?>" required="required" size="30" type="number" value="<?php echo $phone; ?>"  minlength="10" maxlength="10" onKeyPress="if(this.value.length==10) return false;" > 
                                        <span id="phone_error1"></span>



                                    </div>

                          <?php  } ?>          
                   
               

                                </div>



                            </div>



                        </div>

    <?php     
                     if (! isset($_GET['error1']) || !$_GET['verror']=='true') {

    ?> 

                        <input style="margin-top: 25px" name="commit" class="button blue" type="submit" value="Send">


<?php } ?>
                    </form>

                </div>





            </div>

        </div>





    </div>



    <?php



        include_once("../html/common/footer.inc.php");

    ?>



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



      $("#phone1").keyup(function(event) {
            var input_data = $('#phone1').val();
            if(input_data.length>=10 && input_data.length<=10){
                $("#phone_error1").html("");
                 $(':input[type="submit"]').prop('disabled', false);
            }else{
               
                $("#phone_error1").html("<div style='color:red; font-size: 13px;'>Mobile No must be 10 digits.</div>");
                 $(':input[type="submit"]').prop('disabled', true);
            }
        });
        
     
 </script>