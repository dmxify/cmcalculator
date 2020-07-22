<?php

  if (!isset($_SESSION)) {
      // server should keep session data for AT LEAST 1 hour
      ini_set('session.gc_maxlifetime', 3600);

      // each client should remember their session id for EXACTLY 1 hour
      session_set_cookie_params(3600);
      session_start();
  }
  if (!isset($_SESSION['theme'])) {
      $_SESSION['theme'] = "light";
  }

  function handleSessionMessages($action)
  {
      if (isset($_SESSION['action'])) {
          if ($_SESSION['action']==$action) {
              echo $_SESSION['message'];
          }
      }
  }

  function handleSessionMessageType($action)
  {
      if (isset($_SESSION['action'])) {
          if ($_SESSION['action']==$action) {
              echo $_SESSION['message-type'];
          }
      }
  }

  function isSessionAction($action)
  {
      if (isset($_SESSION['action'])) {
          if ($_SESSION['action']==$action) {
              return true;
          }
      }
      return false;
  }

  function getSessionEmail()
  {
      if (isset($_SESSION['email'])) {
          return $_SESSION['email'];
      }
      return "";
  }
  function getSessionName()
  {
      if (isset($_SESSION['user'])) {
          if (isset($_SESSION['user']['name'])) {
              return $_SESSION['user']['name'];
          }
      }
      return "";
  }

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="CM Calculator - The unofficial compound interest calculator, ledger & planner for Continental Miners.">
  <script type="text/javascript" src="js/scripts.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/menu.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/modals.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/big.min.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/clipboard.min.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/state-manager.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/currencies.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/tooltip.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/widgets/calculator.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/widgets/currency-converter.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/dynamic-globals.js<?php url_params(); ?>"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <link rel="manifest" href="manifest.webmanifest">
  <link rel="icon" type="image/png" href="btc.png">
  <link rel="stylesheet" type="text/css" href="styles/style.css<?php url_params(); ?>">
  <link rel="stylesheet" type="text/css" href="styles/menu.css<?php url_params(); ?>">
  <link rel="stylesheet" type="text/css" href="styles/modals.css<?php url_params(); ?>">
  <link rel="stylesheet" type="text/css" href="icons/icons.css<?php url_params(); ?>">
  <link rel="stylesheet" type="text/css" href="styles/tooltip.css<?php url_params(); ?>">
  <link rel="stylesheet" type="text/css" href="styles/buttons.css<?php url_params(); ?>">
  <title>CM Calculator - Compound Interest Calculator | Ledger | Planner </title>
</head>

<body class="<?php echo $_SESSION['theme']; ?>">
  <div class="title-bar">
    <div class="title-bar-item-wrapper">
      <div class="title">
        <div style="width:30px;height:30px;min-width:30px;margin-right:10px;background-image: url('btc.png');background-size: cover;"></div>
        CM Calculator
      </div>

    <?php if (isset($_SESSION["user"])) { ?>
    <div class="title welcome">
      Welcome, <?php echo getSessionName(); ?>!
    </div>
    <?php }?>

    </div>

    <div class="title-bar-item-wrapper toolbar right">
        <!--onclick="open_modal_login()"-->
        <?php if (!isset($_SESSION["user"])) { ?>
        <div class="button super-button bold btn-green" title="Register for advanced features!" id="btnRegister" onclick="open_modal('modalRegister')">
          <div class="button-icon icon icon-left icon-small icon-id_user"></div>
          <div class="button-text">
            Register
          </div>
        </div>
        <div class="button super-button bold" title="Login for advanced features!" id="btnLogin" onclick="open_modal('modalLogin')">
          <div class="button-icon icon icon-left icon-small icon-lock_open"></div>
          <div class="button-text">
            Login
          </div>
        </div>
      <?php } else { ?>

        <div class="button super-button bold" title="Log out" id="btnLogout" onclick="logout()">
          <div class="button-icon icon icon-left icon-small icon-error_shield"></div>
          <div class="button-text">
            Logout
          </div>
        </div>


      <?php } ?>
        <!-- <div class="button super-button bold" title="End session and log out" id="btnLogout" onclick="logout()">
          <div class="button-icon icon icon-left icon-small icon-lock-1"></div>
          <div class="button-text">
            Logout
          </div>
        </div> -->


        <div class="button super-button bold" title="Main menu" id="btnMenu" onclick="toggleMenu('menu-main')">
          <div class="button-icon icon icon-left icon-small icon-menu"></div>
          <div class="button-text">
            Menu
          </div>
        </div>
        <!-- <div class="button super-button bold hidden" title="Share CM Calculator" id="btnShare"
          onclick="">
          <div class="button-icon icon icon-left icon-small icon-bubbles-3"></div>
          <div class="button-text">
            Share
          </div>
        </div> -->
    </div><!-- title-bar-item-wrapper -->
    <div id="menu-main" class="menu hidden">

      <div class="menu-item-wrapper">
        <?php if (isset($_SESSION["user"])) { ?>
        <div class="menu-item">
          <div class="button super-button bold" title="My Profile" id="btnProfile" onclick="open_modal_profile()">
            <div class="button-icon icon icon-left icon-small icon-user-1"></div>
            <div class="button-text">
              My Profile
            </div>
          </div>
        </div><!-- menu-item -->
        <?php } ?>
        <div class="menu-item">
          <div class="button super-button bold" title="Toggle Dark Mode" id="toggleDarkMode" onclick="toggleDarkMode()">
            <div class="button-icon icon icon-small icon-bulb_light"></div>
            <div class="button-text">
              Mode
            </div>
          </div>
        </div><!-- menu-item -->
        <div class="menu-item">
          <div class="button super-button bold" title="Help" id="btnHelp"
            onclick="showTooltip('Under Development!','If you need assistance, please ask on the discussion group found on the Telegram channel: t.me/cmcalculator (link at the bottom of the webpage) ')">
            <div class="button-icon icon icon-left icon-small icon-buoy_life"></div>
            <div class="button-text">
              Help!
            </div>
          </div>
        </div><!-- menu-item -->
        <div class="menu-item">
          <div class="button super-button bold" title="About CM Calculator" id="btnAbout"
            onclick="showTooltip('About cmcalculator','cmcalculator is a tool to help you (and your friends!) plan your investment strategy, and reach financial goals.<br /><br />It started as a side project to calculate compound interest, and now it is under active development with new features in the pipelines.<br /><br />cmcalculator is not an official Continental Miners app, nor is it affiliated with or endorsed by them. (DISCLAIMER, TERMS & CONDITIONS link at the bottom of the web page)<br /><br />Please subscribe to the Telegram channel (link at the bottom of the webpage), and if cmcalculator has helped you at all, please consider donating (BTC address at the bottom of the webpage) ')">
            <div class="button-icon icon icon-left icon-small icon-cat"></div>
            <div class="button-text">
              About
            </div>
          </div>
        </div><!-- menu-item -->
        <div class="menu-item">
          <div class="button super-button bold" title="Close menu" onclick="toggleMenu('menu-main')">
            <div class="button-icon icon icon-left icon-small icon-error_sign"></div>
            <div class="button-text">
              Close
            </div>
          </div>
        </div><!-- menu-item -->
      </div><!-- menu-item-wrapper -->

    </div><!-- menu -->
  </div> <!-- title-bar -->
  <div class="main">

    <?php
      include("components/tab-controls.php");

      include("modules/dashboard.php");
      include("modules/transactions.php");
      include("modules/strategy-planner.php");
      include("modules/alerts.php");
      include("modules/calculators.php");

    ?>

  </div>
  <!-- main -->



  <!--  buttons  -->
  <div>
    <div class="ticker-wrapper container">
      <div>
        <b>1 BTC = </b>
      </div>
      <div class="ticker hidden" id="btc_zar">
        <div class="rate" data-dynamicglobal-name="exchange_zar_rate_pretty" data-dynamicglobal-action="show-parent-on-global-update" data-dynamicglobal-set="innerHTML"></div>
      </div>
      <div class="ticker hidden" id="btc_usd">
        <div class="rate" data-dynamicglobal-name="exchange_usd_rate_pretty" data-dynamicglobal-action="show-parent-on-global-update" data-dynamicglobal-set="innerHTML"></div>
      </div>
      <div>
        <div class="button super-button bold" id="btnUpdateExchangeRates" onclick="updateExchangeRates()" style="margin-left:10px;">
          <div class="button-icon icon icon-small icon-cloud_sync"></div>
          <div class="button-text">
            Latest Prices
          </div>
        </div>
      </div>
      <div class="hidden time">
        Last Updated <span data-dynamicglobal-name="exchange_time_updated" data-dynamicglobal-action="show-parent-on-global-update" data-dynamicglobal-set="innerHTML"></span>
      </div>
      <div class="hidden disclaimer">
        <span data-dynamicglobal-name="exchange_disclaimer" data-dynamicglobal-action="show-parent-on-global-update" data-dynamicglobal-set="innerHTML"></span>
      </div>
    </div>
  </div>
  <br />
  <br />
  <div style="width:100%;text-align:center;">
    <div class="divider"></div>
    <br />
    <div id="donate_btc_address_wrapper" class="container info hover clipboard" style="font-size:10pt;cursor:pointer;" data-clipboard-target="#donate_btc_address">
      <div style="margin:5px;">
        Find this useful? <span style="font-size:14pt">🙄</span> Please donate!
      </div>
      <div style="display: flex;align-items: center; justify-content: center; flex-wrap: wrap;">
        <div style="width:90px;height:90px;min-width:90px;background-image: url('btc.png');background-size: cover;"></div><div style="font-weight:bold; margin:5px 10px;" id="donate_btc_address">36mCGspguTLP5tx74U3dmPp6xxEMvkmWV1</div>
      </div>
      <span id="donate_btc_address_copied" class="animation click" style="margin:5px;"><span style="font-size:14pt">👉</span>&nbsp;Click to copy&nbsp;<span style="font-size:14pt">📋</span</span>
    </div>
    <p>
      <a href="LICENSE"><i>Copyright &copy; <?php echo date("Y");?> cmcalculator.com</i></a>
    </p>
  </div>

  <a href="release-notes" target="_blank" style="float:left;margin:15px 0px 5px 25px;">Version <?php echo get_version(); ?> release notes</a>
  <a href="disclaimer.html" target="_blank" style="float:right;margin:15px 25px 5px 0;">Disclaimer, T's & C's</a>&nbsp;&nbsp;
  <a href="https://t.me/cmcalculator" target="_blank" style="float:right;margin:15px 25px 5px 0;">Join Telegram Channel</a>

  <!-- tooltip -->
  <div class="tooltip-background-blur"></div>
  <div id="tooltip" class="tooltip">
    <div class="tooltip-title"></div>
    <div class="tooltip-text"></div>
  </div>
  <div class="tooltip-overlay" onclick="hideTooltip()"></div>

  <!-- end buttons -->

  <?php include("modals/modal-login.php"); ?>
  <?php include("modals/modal-register.php"); ?>
  <?php include("modals/modal-forgot-password.php"); ?>
  <?php include("modals/modal-change-password.php"); ?>


  <script>
  // Get the modal
  var modalLogin = document.getElementById('modalLogin');
  var modalRegister = document.getElementById('modalRegister');
  var modalForgotPassword = document.getElementById('modalForgotPassword');
  var modalChangePassword = document.getElementById('modalChangePassword');

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    switch (event.target) {
      case modalLogin:
        modalLogin.style.display = "none";
        break;
      case modalRegister:
        modalRegister.style.display = "none";
        break;
      case modalForgotPassword:
        modalForgotPassword.style.display = "none";
        break;
      case modalChangePassword:
        modalChangePassword.style.display = "none";
        break;
      default:
        break;
    }
  }
  </script>
  <script>
    (function() {
    var clipboard = new ClipboardJS('.clipboard');
    clipboard.on('success', function(e) {
      document.getElementById("donate_btc_address_copied").innerHTML = "Copied to clipboard!<span style='font-size:14pt'>🤝👏👏👏</span>";
      document.getElementById("donate_btc_address_wrapper").classList.add("copied");

    e.clearSelection();
});

  <?php if (isSessionAction('login')) { ?>
      open_modal('modalLogin'); // IF USER VERIFIED EMAIL: SHOW LOGIN WITH MESSAGE
  <?php } elseif (isSessionAction('change-password')) { ?>
      open_modal('modalChangePassword'); // IF USER CLICKED VALID PASSWORD RESET LINK IN EMAIL: SHOW PASSWORD CHANGE
  <?php } elseif (isSessionAction('forgot-password')) { ?>
      open_modal('modalForgotPassword'); // IF USER CLICKED INVALID PASSWORD RESET LINK IN EMAIL: SHOW FORGOT PASSWORD AGAIN
  <?php } else { ?>
  // ELSE, CONTINUE AS NORMAL
      document.getElementById("principal").focus();
      document.getElementById("principal").select();
  <?php } ?>
      updateExchangeRates();
      window.calculator.minToReinvest = 0.0028;
      window.calculator.currency = "BTC";

    })();
  </script>
</body>

</html>


<?php

// CLEAR SESSION MESSAGES
$_SESSION['action'] = "";
$_SESSION['message'] = "";
$_SESSION['message-type'] = "";
$_SESSION['token'] = "";

?>
