<?php
  if (!isset($_SESSION)) {
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
  <script type="text/javascript" src="js/scripts.js"></script>
  <script type="text/javascript" src="js/big.min.js"></script>
  <script type="text/javascript" src="js/state-manager.js"></script>
  <script type="text/javascript" src="js/currencies.js"></script>
  <script type="text/javascript" src="js/tooltip.js"></script>
  <script type="text/javascript" src="js/widgets.js"></script>
  <script type="text/javascript" src="js/widgets/calculator.js"></script>
  <script type="text/javascript" src="js/widgets/currency-converter.js"></script>
  <script type="text/javascript" src="js/widgets/ledger.js"></script>
  <script type="text/javascript" src="js/dynamic-globals.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <link rel="manifest" href="manifest.webmanifest">
  <link rel="icon" type="image/png" href="btc.png">
  <link rel="stylesheet" type="text/css" href="styles/style.css">
  <link rel="stylesheet" type="text/css" href="styles/login.css">
  <link rel="stylesheet" type="text/css" href="icons/icons.css">
  <link rel="stylesheet" type="text/css" href="styles/tooltip.css">
  <link rel="stylesheet" type="text/css" href="styles/buttons.css">
  <title>CM Calculator - Compound Interest Calculator | Ledger | Planner </title>
</head>

<body class="<?php echo $_SESSION['theme']; ?>">
  <div class="title-bar">
    <div class="title">
      <div style="width:30px;height:30px;min-width:30px;margin-right:10px;background-image: url('btc.png');background-size: cover;"></div>
      CM Calculator
    </div>

    <?php if (isset($_SESSION["user"])) { ?>
    <div class="title welcome">
      Welcome, <?php echo getSessionName(); ?>!
    </div>
    <?php }?>

    <div class="toolbar right">
      <!--onclick="open_modal_login()"-->
      <?php if (!isset($_SESSION["user"])) { ?>
      <div class="button super-button bold btn-green" title="Register for advanced features!" id="btnRegister" onclick="open_modal_register()">
        <div class="button-icon icon icon-left icon-small icon-id_user"></div>
        <div class="button-text">
          Register
        </div>
      </div>
      <div class="button super-button bold" title="Login for advanced features!" id="btnLogin" onclick="open_modal_login()">
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

      <div class="button super-button bold" title="Log out" id="btnProfile" onclick="open_modal_profile()">
        <div class="button-icon icon icon-left icon-small icon-id_user"></div>
        <div class="button-text">
          My Profile
        </div>
      </div>
    <?php } ?>
      <!-- <div class="button super-button bold" title="End session and log out" id="btnLogout" onclick="logout()">
        <div class="button-icon icon icon-left icon-small icon-lock-1"></div>
        <div class="button-text">
          Logout
        </div>
      </div> -->

      <div class="button super-button bold" title="Toggle Dark Mode" id="toggleDarkMode" onclick="toggleDarkMode()">
        <div class="button-icon icon icon-small icon-bulb_light"></div>
      </div>
      <div class="button super-button bold" title="About cmcalculator" id="btnAbout"
        onclick="showTooltip('About cmcalculator','cmcalculator is a tool to help you (and your friends!) plan your investment strategy, and reach financial goals.<br /><br />It started as a side project to calculate compound interest, and now it is under active development with new features in the pipelines.<br /><br />cmcalculator is not an official Continental Miners app, nor is it affiliated with or endorsed by them.<br /><br />Please subscribe to the telegram channel (link at the bottom of the webpage), and if cmcalculator has helped you at all, please consider donating (BTC address at the bottom of the webpage) ')">
        <div class="button-icon icon icon-left icon-small icon-cat"></div>
        <div class="button-text">
          About
        </div>
      </div>
    </div>
  </div>
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
            <div class="radio-button-text bold">&#x20bf;</div>
          </div>
          <div id="btnUSD" data-calculator-cc="USD" data-dynamicglobal-name="exchange_usd_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('USD')" class="button radio-button hidden">
            <div class="radio-button-text bold">$</div>
          </div>
          <div id="btnZAR" data-calculator-cc="ZAR" data-dynamicglobal-name="exchange_zar_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('ZAR')" class="button radio-button hidden">
            <div class="radio-button-text bold">R</div>
          </div>
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
              <b><span class="ui_calculator_reinvest">Yes, always.</span></b>
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
              <td>0.0028 BTC to 2 BTC</td>
              <td>1.4 %</td>
            </tr>
            <tr>
              <td>Gold</td>
              <td>2.1 BTC to 15 BTC</td>
              <td>2.22 %</td>
            </tr>
            <tr>
              <td>VIP</td>
              <td>15.1 BTC to 100 BTC</td>
              <td>3.7 %</td>
            </tr>
          </table>
          <!-- <ul>
            <li><b>Silver</b>&lt;<span>1 BTC</span> - <span>1.4</span>%</li>
            <li><b>Gold</b>&lt;<span>15.1 BTC</span> - <span>2.22</span>%</li>
            <li><b>VIP</b>&gt;<span>15.1 BTC</span> - <span>3.7</span>%</li>
          </ul> -->
        </div>
      </div>
      <!-- TABLE: 180 -->
      <div class="container hidden">
        <div id="table180Toolbar" class="">
          <div class="title center">
            <div class="title-text">
              Single Investment Table
            </div>
            <div class="tooltip-trigger icon icon-small icon-right icon-info_sign" title="Click for info"
              onclick="showTooltip('Single Investment Table','See the results of a single investment.<br/><br/>An investment is opened as soon as you make a deposit or reinvest, and will generate a fixed percentage interest <b>DAILY</b> for the duration of the investment length.<br/><br/>Investment interest rate and duration changes with investment level (silver, gold, VIP) which is based on the amount spent on the investment. ')">
            </div>
          </div>

          <div class="container toolbar center hidden calculator-cc-buttons">
            <div class="title small">Currency conversions</div>
            <div id="btnBTC" data-calculator-cc="BTC" data-dynamicglobal-name="exchange_usd_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('BTC')"
              class="button radio-button selected hidden">
              <div class="radio-button-text bold">&#x20bf;</div>
            </div>
            <div id="btnUSD" data-calculator-cc="USD" data-dynamicglobal-name="exchange_usd_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('USD')" class="button radio-button hidden">
              <div class="radio-button-text bold">$</div>
            </div>
            <div id="btnZAR" data-calculator-cc="ZAR" data-dynamicglobal-name="exchange_zar_rate_float" data-dynamicglobal-action="show-this-and-parent-on-global-update" onclick="calculator.setCurrency('ZAR')" class="button radio-button hidden">
              <div class="radio-button-text bold">R</div>
            </div>
          </div>
        </div>
        <div id="table180Summary" style="margin-top: 10px;">
        </div>
        <div id="table180Wrapper" style="max-height:400px; overflow-y:scroll;">
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
            <div class="bold">&#x20bf; 0.0028</div>
            <div class="button-subtext">min to reinvest</div>
          </div>
          <div class="button super-button" onclick="cc_set_btc(0.14894)">
            <div class="bold">&#x20bf; 0.14894</div>
            <div class="button-subtext">generates 0.0028 daily (@ 1.88%)</div>
          </div>
          <div class="button super-button" onclick="cc_set_btc(0.2)">
            <div class="bold">&#x20bf; 0.2</div>
            <div class="button-subtext">generates 0.0028 daily (@ 1.4%)</div>
          </div>
        </div>

        <br />
        <div class="controlAndLabelWrapper">
          <label for="cc_btc">Bitcoin</label>
          <div class="controlWrapper">
            <input id="cc_btc" name="cc_btc" placeholder="Bitcoin" type="number" class="number" onInput="cc_onchange('cc_btc')" onclick="cc_onclick(this)" /><b>&#x20bf;</b>
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
    Useful? Donate BTC :) <b><span>36mCGspguTLP5tx74U3dmPp6xxEMvkmWV1</span></b>
  </div>
  <a href="disclaimer.html" target="_blank" style="float:right;margin:15px 25px 0 0;">Disclaimer, T's & C's</a>&nbsp;&nbsp;
  <a href="https://t.me/cmcalculator" target="_blank" style="float:right;margin:15px 25px 0 0;">Join Telegram Channel</a>

  <!-- tooltip -->
  <div class="tooltip-background-blur"></div>
  <div id="tooltip" class="tooltip">
    <div class="tooltip-title"></div>
    <div class="tooltip-text"></div>
  </div>
  <div class="tooltip-overlay" onclick="hideTooltip()"></div>

  <!-- end buttons -->

  <div id="loginModal" style="font-family: Arial, Helvetica, sans-serif;">


    <div id="id01" class="modal">

      <div class="modal-content animate">
        <div class="imgcontainer title">
          <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close login window">&times;</span>
          <img src="btc.png" alt="Avatar" class="avatar">
          <div style="font-size: 20pt; margin-left: 20px;">CM Calculator</div>
        </div>
        <div class="container">
          <form onsubmit="return login();" method="post">
            <?php if (isSessionAction('login')) { ?>
            <div class="success-message">
              <?php handleSessionMessages('login'); ?>
            </div>
          <?php } ?>
            <div class="title bold">
              Existing user login:
            </div>
            <br />
            <label for="email"><b>Email Address</b></label>
            <input id="login_email" type="text" placeholder="Enter Email Address" autocomplete="username" name="email" value="<?php echo getSessionEmail(); ?>" required>

            <label for="password"><b>Password</b></label>
            <input id="login_password" type="password" autocomplete="current-password" placeholder="Enter Password" name="password" required>

            <!-- <button onclick="login()">Login</button> -->
            <input type="submit" value="Login" id="login_btnSubmit" />
            <div id="login_button_mask">
              <div class="loading-animation" id="login_loading">
                Logging in...
              </div>
            </div>
          </form>
          <div id="login-msg"></div>
        </div>

        <div class="container" >
          <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
          <span class="psw">Forgot <a href="#">password?</a></span>
        </div>
      </div>
    </div>

    <script>
      // Get the modal
      var modal1 = document.getElementById('id01');
      var modal2 = document.getElementById('id02');

      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        switch (event.target) {
          case modal1:
            modal1.style.display = "none";
            break;
          case modal2:
            modal2.style.display = "none";
            break;
          default:
            break;
        }
      }

      function login() {
        document.getElementById("login_btnSubmit").style.display = "none";
        document.getElementById("login_button_mask").style.display = "block";

        fetch('api/user/login/index.php', {
            method: 'post',
            headers: {
              'Accept': 'application/json, text/plain, */*',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              email: document.getElementById('login_email').value,
              password: document.getElementById('login_password').value
            })
          })
          .then(response => response.json())
          .then((data) => {
            if (!data.verified) {
              document.getElementById("login-msg").innerHTML = "Invalid email address or password.";
              document.getElementById("login-msg").style.display = "block";
              document.getElementById("login_btnSubmit").style.display = "block";
              document.getElementById("login_button_mask").style.display = "none";
            } else {
                window.location.reload();
            }
          });
        return false;
      }

      function logout(){
        fetch('api/user/logout/index.php').then(()=>{
            window.location.reload();
        });
      }
    </script>

  </div>

  <div id="registerModal" style="font-family: Arial, Helvetica, sans-serif;">

    <div id="id02" class="modal">

      <div class="modal-content animate">
        <div class="imgcontainer title">
          <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close register window">&times;</span>
          <img src="btc.png" alt="Avatar" class="avatar">
          <div style="font-size: 20pt; margin-left: 20px;">CM Calculator</div>
        </div>
        <div class="container">
          <form onsubmit="return register();" method="post">
            <div class="title bold">
              New user registration:
            </div>
            <br />

              <label for="register_name"><b>First Name</b></label>
              <input id="register_name" name="register_name" type="text" placeholder="Enter First Name" autocomplete="given-name" required>

              <label for="register_email"><b>Email Address</b></label>
              <input id="register_email" name="register_email" type="text" placeholder="Enter Email Address" autocomplete="username" required>

              <label for="password"><b>Set Password</b></label>
              <input id="register_password" name="register_password" type="password" autocomplete="new-password" placeholder="Enter New Password" required>

              <label for="password"><b>Confirm Password</b></label>
              <input id="register_confirm_password" name="register_confirm_password" type="password" autocomplete="new-password" placeholder="Confirm Password" required>

              <div class="g-recaptcha" data-sitekey="6LesXKYZAAAAAOg5KsgrKPyds_elGqXAnaZFDr6v" data-callback="captcha_solved" data-theme="<?php echo $_SESSION['theme']; ?>"></div>
              <!-- <button onclick="login()">Login</button> -->
              <input type="submit" value="Register" id="register_btnSubmit" class="disabled" disabled="disabled"/>
              <div id="register_button_mask">
                <div class="loading-animation" id="register_loading">
                  Registering...
                </div>
              </div>
            </form>
            <div id="register-msg"></div>
        </div>

        <div class="container" >
          <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelbtn">Cancel</button>
        </div>
      </div>
    </div>
    <script>
    window.captcha_response_token = "";
      function captcha_solved(captcha_response_token){
        window.captcha_response_token = captcha_response_token;
        if (document.getElementById("register_btnSubmit").disabled) {
            document.getElementById("register_btnSubmit").disabled = false;
              document.getElementById("register_btnSubmit").classList.remove("disabled");
        }
      }
      function register() {
        document.getElementById("register_btnSubmit").style.display = "none";
        document.getElementById("register_button_mask").style.display = "block";

        fetch('api/user/register/index.php', {
            method: 'post',
            headers: {
              'Accept': 'application/json, text/plain, */*',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              register_name: document.getElementById('register_name').value,
              register_email: document.getElementById('register_email').value,
              register_password: document.getElementById('register_password').value,
              register_confirm_password: document.getElementById('register_confirm_password').value,
              captcha_response_token: window.captcha_response_token
            })
          })
          // .then(response => response.json())
          .then((response) => {
            return response.json();
           })
          .then((data) => {
            if (!data.registered) {
              document.getElementById("register-msg").innerHTML = "Unable to register user: "+(data.error)?data.errorMsg:"";
              document.getElementById("register-msg").style.display = "block";
              document.getElementById("register_btnSubmit").style.display = "block";
              document.getElementById("register_button_mask").style.display = "none";
            } else {
                // window.location.reload();
                document.getElementById("registerModal")
                document.querySelector('#registerModal form').style.display = "none";
                document.getElementById("register-msg").style.display = "none";
                document.querySelector('#registerModal .container').innerHTML = data.msg;
                document.querySelector('#registerModal .container').classList.add("success");
                // document.getElementById("register-msg").classList.add('success');
            }
          });
        return false;
      }
    </script>
  </div>

  <script>
    (function() {

  <?php
  // IF USER VERIFIED EMAIL: SHOW LOGIN WITH MESSAGE
  if (isSessionAction('login')) {?>
      open_modal_login();
  <?php } else { ?>
  // ELSE, CONTINUE AS NORMAL
      document.getElementById("principal").focus();
      document.getElementById("principal").select();
  <?php }?>
      updateExchangeRates();
      window.calculator.minToReinvest = 0.0028;
      window.calculator.currency = "BTC";

    })();
  </script>
</body>

</html>
