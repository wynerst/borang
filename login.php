<?php
// key to authenticate
define('INDEX_AUTH', '1');
// file sysconfig sebaiknya berada di paling atas kode
require 'sysconfig.inc.php';
// required file
require LIB_DIR.'admin_logon.inc.php';

// https connection (if enabled)
if ($sysconf['https_enable']) {
  simbio_security::doCheckHttps($sysconf['https_port']);
}

// check if session browser cookie already exists
if (isset($_COOKIE['admin_logged_in'])) {
  header('location: ./index.php');
}

// start the output buffering for main content
ob_start();

// if there is login action
if (isset($_POST['logMeIn'])) {
    $username = strip_tags($_POST['userName']);
    $password = strip_tags($_POST['passWord']);
    if (!$username OR !$password) {
        echo '<script type="text/javascript">alert(\'Please supply valid username and password\');</script>';
    } else {
        require LIB_DIR.'session.inc.php';
        // regenerate session ID to prevent session hijacking
        session_regenerate_id(true);
        // create logon class instance
        $logon = new admin_logon($username, $password, $sysconf['auth']['user']['method']);
        if ($sysconf['auth']['user']['method'] == 'ldap') {
            $ldap_configs = $sysconf['auth']['user'];
        }
        if ($logon->adminValid($dbs)) {

            # <!-- Captcha form processing - start -->
            if ($sysconf['captcha']['smc']['enable']) {
                if ($sysconf['captcha']['smc']['type'] == 'recaptcha') {
                    require_once LIB.$sysconf['captcha']['smc']['folder'].'/'.$sysconf['captcha']['smc']['incfile'];
                    $privatekey = $sysconf['captcha']['smc']['privatekey'];
                    $resp = recaptcha_check_answer ($privatekey,
                        $_SERVER["REMOTE_ADDR"],
                        $_POST["recaptcha_challenge_field"],
                        $_POST["recaptcha_response_field"]);

                    if (!$resp->is_valid) {
                        // What happens when the CAPTCHA was entered incorrectly
                        session_unset();
                        header("location: ./login.php");
                        die();
                    }
                } elseif ($sysconf['captcha']['smc']['type'] == 'others') {
                    # other captchas here
                }
            }
            # <!-- Captcha form processing - end -->

            // set cookie admin flag
            setcookie('admin_logged_in', true, time()+14400, SENAYAN_WEB_ROOT_DIR);
            // write log
            utility::writeLogs($dbs, 'staff', $username, 'Login', 'Login success for user '.$username.' from address '.$_SERVER['REMOTE_ADDR']);
            echo '<script type="text/javascript">';
            if ($sysconf['login_message']) {
                echo 'alert(\'Welcome, '.$logon->real_name.'\');';
            }
            #echo 'location.href = \'admin/index.php\';';
            echo 'location.href = \'./index.php\';';
            echo '</script>';
            exit();
        } else {
            // write log
            utility::writeLogs($dbs, 'staff', $username, 'Login', 'Login FAILED for user '.$username.' from address '.$_SERVER['REMOTE_ADDR']);
            // message
            $msg = '<script type="text/javascript">';
            $msg .= 'alert(\'Wrong Username or Password. ACCESS DENIED\');';
            $msg .= 'history.back();';
            $msg .= '</script>';
            simbio_security::destroySessionCookie($msg, SENAYAN_SESSION_COOKIES_NAME, SENAYAN_WEB_ROOT_DIR.'admin', false);
            exit();
        }
    }
}
?>
<div class="row">

<div class="span5 offset4" id="loginForm">
    <noscript>
        <div class="alert alert-error"><?php echo 'Your browser does not support Javascript or Javascript is disabled. Application won\'t run without Javascript!'; ?><div>
    </noscript>
    <!-- Captcha preloaded javascript - start -->
    <?php if ($sysconf['captcha']['smc']['enable']) { ?>
      <?php if ($sysconf['captcha']['smc']['type'] == "recaptcha") { ?>
      <script type="text/javascript">
        var RecaptchaOptions = {
          theme : '<?php echo$sysconf['captcha']['smc']['recaptcha']['theme']; ?>',
          lang : '<?php echo$sysconf['captcha']['smc']['recaptcha']['lang']; ?>',
          <?php if($sysconf['captcha']['smc']['recaptcha']['customlang']['enable']) { ?>
                custom_translations : {
                instructions_visual : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['instructions_visual']; ?>",
                instructions_audio : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['instructions_audio']; ?>",
                play_again : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['play_again']; ?>",
                cant_hear_this : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['cant_hear_this']; ?>",
                visual_challenge : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['visual_challenge']; ?>",
                audio_challenge : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['audio_challenge']; ?>",
                refresh_btn : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['refresh_btn']; ?>",
                help_btn : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['help_btn']; ?>",
                incorrect_try_again : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['incorrect_try_again']; ?>",
                },
          <?php } ?>
        };
      </script>
      <?php } ?>
    <?php } ?>
    <!-- Captcha preloaded javascript - end -->
    <form action="./login.php" method="post">
    <div class="heading1">Username</div>
    <div class="login_input"><input type="text" name="userName" id="userName" class="login_input" /></div>
    <div class="heading1">Password</div>
    <div class="login_input"><input type="password" name="passWord" class="login_input" /></div>
    <!-- Captcha in form - start -->
    <?php if ($sysconf['captcha']['smc']['enable']) { ?>
      <?php if ($sysconf['captcha']['smc']['type'] == "recaptcha") { ?>
      <div class="captchaAdmin">
      <?php
        require_once LIB.$sysconf['captcha']['smc']['folder'].'/'.$sysconf['captcha']['smc']['incfile'];
        $publickey = $sysconf['captcha']['smc']['publickey'];
        echo recaptcha_get_html($publickey);
      ?>
      </div>
      <!-- <div><input type="text" name="captcha_code" id="captcha-form" style="width: 80%;" /></div> -->
    <?php
      }
    } ?>
    <!-- Captcha in form - end -->

    <div class="marginTop">
    <input type="submit" name="logMeIn" value="LOGIN" class="btn btn-primary loginButton" />
    </div>
    </form>
</div>
</div>
<script type="text/javascript">jQuery('#userName').focus();</script>

<?php
// main content
$main_content = ob_get_clean();

// page title
$page_title = 'Application Login';

require './template/sosek/page_tpl.inc.php';
