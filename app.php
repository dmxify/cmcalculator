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
  <script type="text/javascript" src="js/widgets.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/widgets/calculator.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/widgets/currency-converter.js<?php url_params(); ?>"></script>
  <script type="text/javascript" src="js/widgets/ledger.js<?php url_params(); ?>"></script>
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

    <!-- <div class="tab-bar">
      <div id="btnLayoutCalculator" onclick="change_layout('calculator')" class="tab-button selected">Calculator</div>
      <div id="btnLayoutLedger"  onclick="change_layout('ledger')" class="tab-button">Ledger</div>
      <div id="btnLayoutProjection" onclick="change_layout('planner')" class="tab-button">Planner</div>
    </div> -->
    <!--<div class="controlAndLabelWrapper">
        <label for="principal">Platform</label>
        <div class="controlWrapper">
          <select id="platform" onchange="platform_onchange()">
            <option>Continental Miners</option>
          </select>
        </div>
      </div>-->

    <!-- "layout" div is the wrapper for the active tab-->
    <div class="layout">


      <!-- Widget: Ledger -->
      <div class="container" id="containerLedger" style="display:none;">
        <div class="title center">
          <div class="title-text">
            Ledger
          </div>
          <div class="tooltip-trigger icon icon-small icon-right icon-info_sign" title="Click for info" onclick="showTooltip('Ledger','A record of all your deposits, reinvestments and withdrawals.')">
          </div>
        </div>

        <div class="tabs">
          <div class="tab-pane" id="ledger_tabInvestments">
            <div class="toolbar left radio-button-group slim">
              <div class="control-label slim" style="margin:5px; flex:1;">
                Investment Type:
              </div>
              <div class="button super-button bold slim selected" data-radiogroup="ledger-investment-type" data-value="" onclick="ledger.investments.setType(this)" style="flex: 2;">
                <div class="button-icon icon icon-left icon-small icon-star-web"></div>
                <div class="button-text">
                  All
                </div>
              </div>
              <div class="button super-button bold slim" data-radiogroup="ledger-investment-type" data-value="deposits" onclick="ledger.investments.setType(this)" style="flex: 2;">
                <div class="button-icon icon icon-left icon-small icon-box_in"></div>
                <div class="button-text">
                  Deposits
                </div>
              </div>
              <div class="button super-button slim bold" data-radiogroup="ledger-investment-type" data-value="reinvestments" onclick="ledger.investments.setType(this)" style="flex: 2;">
                <div class="button-icon icon icon-left icon-small icon-arrow_24"></div>
                <div class="button-text">
                  Reinvestments
                </div>
              </div>
            </div>

            <div class="toolbar left">
              <div class="control-label" style="margin:5px; flex: 1;">
                Status:
              </div>
              <div class="button super-button bold selected" data-radiogroup="ledger-investment-type" data-value="" onclick="ledger.investments.setType(this)" style="flex: 2;">
                <div class="button-icon icon icon-left icon-small icon-star-web"></div>
                <div class="button-text">
                  All
                </div>
              </div>
              <div class="button super-button bold" data-radiogroup="ledger-investment-type" data-value="deposits" onclick="ledger.investments.setType(this)" style="flex: 2;">
                <div class="button-icon icon icon-left icon-small icon-box_in"></div>
                <div class="button-text">
                  Active
                </div>
              </div>
              <div class="button super-button bold" data-radiogroup="ledger-investment-type" data-value="reinvestments" onclick="ledger.investments.setType(this)" style="flex: 2;">
                <div class="button-icon icon icon-left icon-small icon-arrow_24"></div>
                <div class="button-text">
                  Ended
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane hidden" id="ledger_tabWithdrawals">

          </div>
        </div>


      </div>

      <!-- Widget: Basic Calculator -->
      <div class="container" id="containerCalculatorInput">

        <div class="title center">
          <div class="title-text">
            Single Investment Calculator
          </div>
          <div class="tooltip-trigger icon icon-small icon-right icon-info_sign" title="Click for info"
            onclick="showTooltip('Single Investment Calculator','Useful to quickly analyse the earnings of a single investment.<br/><br/>An investment is opened as soon as you make a deposit or reinvest, and will generate a fixed percentage interest <b>DAILY</b> for the duration of the investment length.<br/><br/>Investment interest rate and duration changes with investment level (silver, gold, VIP) which is based on the amount spent on the investment. ')">
          </div>
        </div>
        <div class="container toolbar center hidden calculator-cc-buttons">
          <div class="title small">Currency conversions</div>
          <div id="btnBTC" data-calculator-cc="BTC" data-dynamicglobal-name="exchange_usd_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('BTC')"
            class="button radio-button selected hidden">
            <div class="radio-button-text bold">B</div>
          </div>
          <div id="btnUSD" data-calculator-cc="USD" data-dynamicglobal-name="exchange_usd_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('USD')" class="button radio-button hidden">
            <div class="radio-button-text bold">$</div>
          </div>
          <div id="btnZAR" data-calculator-cc="ZAR" data-dynamicglobal-name="exchange_zar_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('ZAR')" class="button radio-button hidden">
            <div class="radio-button-text bold">R</div>
          </div>
        </div>


        <div class="container info">
          Investment Levels:
          <br />
          <br />
          <table>
            <tr>
              <th>Level</th>
              <th>Required</th>
              <th>Interest Rate</th>
            </tr>
            <tr>
              <td>Silver</td>
              <td>From 0.0028 BTC</td>
              <td>1.4 %</td>
            </tr>
            <tr>
              <td>Gold</td>
              <td>From 2.1 BTC</td>
              <td>2.22 %</td>
            </tr>
            <tr>
              <td>VIP</td>
              <td>From 15.1 BTC to 100 BTC</td>
              <td>3.7 %</td>
            </tr>
          </table>
          <!-- <ul>
            <li><b>Silver</b>&lt;<span>1 BTC</span> - <span>1.4</span>%</li>
            <li><b>Gold</b>&lt;<span>15.1 BTC</span> - <span>2.22</span>%</li>
            <li><b>VIP</b>&gt;<span>15.1 BTC</span> - <span>3.7</span>%</li>
          </ul> -->
        </div>
        <br />

        <div class="controlAndLabelWrapper">
          <label for="principal">Investment<div class="tooltip-trigger icon icon-right icon-tiny icon-info_sign" title="Click for info" onclick="showTooltip('Investment','The currency value of your deposit. Must be more than 0.0028 BTC.')"></div>
          </label>
          <div class="controlWrapper">
            <input id="principal" placeholder="Enter value" type="number" class="number" onkeyup="calculator_onEnterKeyup(event)" onInput="principal_onChange()" onclick="this.select();" /><b><span class="ui_calculator_symbol">BTC</span></b>
          </div>
        </div>

        </br>

        <div class="controlAndLabelWrapper">
          <label for="interestRate">Daily Interest Rate
            <div class="tooltip-trigger icon icon-right icon-tiny icon-info_sign" title="Click for info" onclick="showTooltip('Daily Interest','The percentage (%) of your deposit, or reinvestment, which is added to your total earnings daily.')">
            </div>
            <div id="interestRate_editable_icon" class="tooltip-trigger icon icon-right icon-tiny icon-pencil icon-disabled" title="Toggle Manual Override" onclick="interestRate_toggleEditable()">
            </div>
          </label>
          <div class="controlWrapper">
            <input id="interestRate" placeholder="Daily Interest Rate" type="number" value="1.4" class="number" onkeyup="calculator_onEnterKeyup(event)" onInput="rate_onChange()" onclick="this.select();" disabled="disabled" />%
          </div>
        </div>
        </br>

        <div class="controlAndLabelWrapper">
          <label for="investmentLength">Investment Length <div class="tooltip-trigger icon icon-right icon-tiny icon-info_sign" title="Click for info"
              onclick="showTooltip('Investment Length','The number of days an active investment will earn you interest. Investments under 15.1 BTC expire in 180 days. Investments over 15.1 BTC expire in 360 days.')"></div>
            <div id="investmentLength_editable_icon" class="tooltip-trigger icon icon-right icon-tiny icon-pencil icon-disabled" title="Toggle Manual Override" onclick="investmentLength_toggleEditable()">
            </div>
          </label>
          <div class="controlWrapper">
            <input id="investmentLength" placeholder="Investment Length" type="number" value="180" class="number" onkeyup="calculator_onEnterKeyup(event)" onInput="updateDOMReinvestment()" onclick="this.select();" disabled="disabled" /> days
          </div>
        </div>

        <div class="controlAndLabelWrapper hidden">
          <label for="minToReinvest" style="font-size:9pt;">Required earnings to reinvest</label>
          <div class="controlWrapper">
            <span class="control-readonly" id="minToReinvest">0.0028</span><b><span class="ui_calculator_symbol">BTC</span></b>
          </div>
        </div>

        </br>

        <div class="controlAndLabelWrapper" id="cc_set_reinvest">
          <label>Reinvest? <div class="tooltip-trigger icon icon-right icon-tiny icon-info_sign" title="Click for info"
              onclick="showTooltip('Reinvesting On/Off','Toggles reinvesting on and off. This is the basic calculator, future versions will allow partial reinvesting in the advanced calculator.')"></div></label>
          <div class="controlWrapper">
            <div class="toolbar left">
              <div style="display:flex; margin-right: 15px;">
                <div class="button slim super-button radio-button selected" onclick="window.calculator.cc_set_reinvest(this,true)">
                  <div class="button-icon icon icon-small icon-check_sign"></div>
                </div>
                <div class="button slim super-button radio-button" onclick="window.calculator.cc_set_reinvest(this,false)">
                  <div class="button-icon icon icon-small icon-error_sign"></div>
                </div>
              </div>
              <b><span class="ui_calculator_reinvest">Yes, always reinvest as soon as possible.</span></b>
            </div>
          </div>
        </div>

        </br>


        <div class="toolbar right">
          <!-- <div class="button btn-green" onclick="generateTable()"></div> -->
          <div class="button super-button btn-green" title="Generate" onclick="btn_showEarnings()">
            <div class="button-icon icon icon-left icon-small icon-money"></div>
            <div class="button-text">
              Generate
            </div>
          </div>
          <div class="button super-button btn-red" title="Reset to default" onclick="reset()">
            <div class="button-icon icon icon-left icon-small icon-error_sign"></div>
            <div class="button-text">
              Clear
            </div>
          </div>
        </div>
      </div>
      <!-- TABLE: 180 -->
      <div class="container hidden">
        <div id="table180Toolbar" class="">
          <div class="title center">
            <div class="title-text">
              Single Investment Results*
            </div>
            <div class="tooltip-trigger icon icon-small icon-right icon-info_sign" title="Click for info"
              onclick="showTooltip('Single Investment Results','See the results of a single investment. <b>*</b><br/><br/>An investment is opened as soon as you make a deposit or reinvest, and will generate a fixed percentage interest <b>DAILY</b> for the duration of the investment length.<br/><br/>Investment interest rate and duration changes with investment level (silver, gold, VIP) which is based on the amount spent on the investment.<br/><br/><b>* Please note that in the case of reinvesting, you may still have active investments out of this calculator\'s range</b>')">
            </div>
          </div>
          <div class="container toolbar center hidden calculator-cc-buttons">
            <div class="title small">Currency conversions</div>
            <div id="btnBTC" data-calculator-cc="BTC" data-dynamicglobal-name="exchange_usd_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('BTC')"
              class="button radio-button selected hidden">
              <div class="radio-button-text bold">B</div>
            </div>
            <div id="btnUSD" data-calculator-cc="USD" data-dynamicglobal-name="exchange_usd_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('USD')" class="button radio-button hidden">
              <div class="radio-button-text bold">$</div>
            </div>
            <div id="btnZAR" data-calculator-cc="ZAR" data-dynamicglobal-name="exchange_zar_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('ZAR')" class="button radio-button hidden">
              <div class="radio-button-text bold">R</div>
            </div>
          </div>
        </div>
        <style>
        #tableSummaryWrapper td {
          text-align:left;
          font-size:8pt;
        }
        #tableSummaryWrapper th {
          font-size:8pt;
        }
        </style>
        <div id="tableSummaryWrapper" class="container info" style="margin: 10px 0px;">
          <div class="title small bold">Summary:</div>
          <table style="width:auto; margin:5px;">
            <tr>
              <td></td>
              <th>BTC</th>
              <th>USD</th>
            </tr>
            <tr>
              <td>Initial Investment</td>
              <td id="tableSummary_initialInvestment_btc"></td>
              <td id="tableSummary_initialInvestment_usd"></td>
            </tr>
            <tr>
              <td id="tableSummary_investmentLength" colspan="4" style="font-weight:normal; padding:5px 0;"></td>
            </tr>
            <tr>
              <td style="font-weight:bold;"># Active Investments</td>
              <td id="tableSummary_activeInvestmentsCount" colspan="3" style="text-align:center; font-weight:bold;"></td>
            </tr>
            <tr>
              <td>Active Investment Value</td>
              <td id="tableSummary_totalInvestments_btc"></td>
              <td id="tableSummary_totalInvestments_usd"></td>
            </tr>
            <tr>
              <td>Earnings Balance</td>
              <td id="tableSummary_balance_btc"></td>
              <td id="tableSummary_balance_usd"></td>
            </tr>
          </table>
          <div class="title hidden" id="pleaseNoteOngoingReinvestments" style="font-size:8pt;"></div>
        </div>
        <div id="tableInvestmentWrapper" style="max-height:400px; overflow-y:scroll;">
        </div>
      </div>


      <!-- Widget: Currency Converter -->
      <div class="container" id="containerCurrencyConverter">
        <div class="title center">
          <div class="title-text">
            Currency Converter
          </div>
          <div class="tooltip-trigger icon icon-small icon-right icon-info_sign" title="Click for info"
            onclick="showTooltip('Currency Converter','Instantly convert Bitcoin to US Dollars and other currencies (currently only South African Rand is supported, others are still in development).<br /><br />')">
          </div>
        </div>
        <br />
        <div class="flex-center" style="margin:5px;">
          Frequently Used:
        </div>


        <div class="toolbar center">
          <div class="button super-button" onclick="cc_set_btc(0.0028)">
            <div class="bold">B 0.0028</div>
            <div class="button-subtext">min to reinvest</div>
          </div>
          <div class="button super-button" onclick="cc_set_btc(0.14894)">
            <div class="bold">B 0.14894</div>
            <div class="button-subtext">generates 0.0028 daily (@ 1.88%)</div>
          </div>
          <div class="button super-button" onclick="cc_set_btc(0.2)">
            <div class="bold">B 0.2</div>
            <div class="button-subtext">generates 0.0028 daily (@ 1.4%)</div>
          </div>
        </div>

        <br />
        <div class="controlAndLabelWrapper">
          <label for="cc_btc">Bitcoin</label>
          <div class="controlWrapper">
            <input id="cc_btc" name="cc_btc" placeholder="Bitcoin" type="number" class="number" onInput="cc_onchange('cc_btc')" onclick="cc_onclick(this)" /><b>B</b>
          </div>
        </div>
        <br />
        <div class="controlAndLabelWrapper">
          <label for="cc_usd">US Dollars</label>
          <div class="controlWrapper">
            <input id="cc_usd" name="cc_usd" placeholder="US Dollars" type="number" class="number" onInput="cc_onchange('cc_usd')" onclick="cc_onclick(this)" /><b>$</b>
          </div>
        </div>
        <br />
        <div class="controlAndLabelWrapper">
          <label for="cc_zar">South African Rand</label>
          <div class="controlWrapper">
            <input id="cc_zar" name="cc_zar" placeholder="Rand" type="number" class="number" onInput="cc_onchange('cc_zar')" onclick="cc_onclick(this)" /><b>R</b>
          </div>
        </div>

        </br>

        <div class="toolbar right">
          <div class="button super-button btn-red" title="Reset currency converter" onclick="widgets.reset('currency-converter')">
            <div class="button-icon icon icon-left icon-small icon-error_sign"></div>
            <div class="button-text">
              Clear
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



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
