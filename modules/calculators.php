<?php ?>
<!-- Begin: module_calculators -->
<div class="module tab-selected" id="module_calculators" style="display:none;">
  <!-- Widget: Basic Calculator -->
  <div class="container" id="container_SingleInvestmentCalculator">

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

    <!-- TABLE: 180 -->
    <div class="hidden container" id="calculatorOutputWrapper">
      <div id="table180Toolbar" data-html2canvas-ignore="true">
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
          <div class="button super-button btn-green" title="Download image" onclick="download_png_singleInvestmentResults()">
            <div class="button-icon icon icon-left icon-small icon-image"></div>
            <div class="button-text">
              Download image
            </div>
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

      <div id="calculatorExportWrapper">
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
        <div id="tableInvestmentWrapper" class="" style="max-height:400px; overflow-y:scroll;">
        </div>
      </div>
    </div>
    <!-- table180Toolbar -->
  </div>
  <!-- container_SingleInvestmentCalculator -->


  <!-- Widget: Currency Converter -->
  <div class="container" id="container_CurrencyConverter">
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
  <!-- container_CurrencyConverter -->

</div>
<!-- End: module_calculators -->
