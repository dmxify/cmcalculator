window.calculator = {
  tableDrawn: false,
  currency: "BTC",
  previousCurrency: "",
  principal: Big(0),
  rate: Big(0.014),
  earnings: Big(0),
  totalInvestment: Big(0),
  numActiveInvestments: Big(0),
  reinvest: true,
  minToReinvest: Big(0.0028),
  setCurrency: function(currencyCode) {
    // if its already this currency, return;
    if (window.calculator.currency == currencyCode) {
      return;
    }
    /* Update UI (currency selector radio buttons)*/
    document.querySelectorAll(".calculator-cc-buttons .radio-button.selected").forEach((el, i) => {

      el.classList.remove("selected");

    });

    document.querySelectorAll('[data-calculator-cc]').forEach((el, i) => {
      var cc = el.dataset.calculatorCc;
      if (cc == currencyCode) {
        el.classList.add("selected");
      }
    });
    /* Update UI (currency symbols)*/

    window.calculator.previousCurrency = window.calculator.currency;
    window.calculator.currency = currencyCode;
    ui_calculator_symbols_update();
    ui_calculator_convert_currencies();

    // if table is already generated, regenerate
    if (window.calculator.tableDrawn) {
      btn_showEarnings();
    }
  },
  setPrincipal: function(value) {
    if (parseFloat(value) < 0 || isNaN(parseFloat(value))) {
      value = 0;
    }

    window.calculator.principal = Big(CURRENCIES.convert(window.calculator.currency, Big(value))["BTC"]);
  },
  cc_set_reinvest: function(el, reinvest) {

    // only proceed if there are changes:
    if (window.calculator.reinvest == reinvest) {
      return;
    }

    // set global
    window.calculator.reinvest = reinvest;
    // if table is already generated, regenerate
    if (window.calculator.tableDrawn) {
      btn_showEarnings();
    }
    /* Update UI (@cc_set_reinvest radio buttons)*/
    document.querySelector("#cc_set_reinvest .radio-button.selected").classList.remove("selected");

    el.classList.add("selected");

    switch (reinvest) {
      case true:
        document.querySelector(".ui_calculator_reinvest").innerHTML = "Yes, always reinvest as soon as possible.";
        break;
      case false:
        document.querySelector(".ui_calculator_reinvest").innerHTML = "No, never reinvest. Accumulate my balance only!";
        break;
      default:
        break;
    }
  }
};

function ui_calculator_symbols_update() {

  document.querySelectorAll(".ui_calculator_symbol").forEach((el, i) => {
    el.innerHTML = window.calculator.currency;
  });
}

function ui_calculator_convert_currencies() {
  var principal = CURRENCIES.convert("BTC", window.calculator.principal)[window.calculator.currency]
  document.getElementById('principal').value = principal;
}

function calculateReinvestmentInterest(principal, rate, minToReinvest) {
  var days = 0;
  var interest = 0;
  if (principal > 0 && rate > 0 && minToReinvest > 0 && principal.gte(minToReinvest)) {
    while (interest < minToReinvest) {
      interest += principal * rate;
      days++;
    }
  }

  return {
    interest: interest, //CURRENCIES.convert(window.calculator.currency, interest)[window.calculator.currency],
    days: days
  };
};

function calculateDailyInterest(principal, rate) {
  var interest = 0;
  if (principal > 0 && rate > 0) {
    interest = Big(principal * rate).toFixed(8);
  }

  return interest;
};

function principal_onChange() {

  window.calculator.setPrincipal(document.getElementById("principal").value);

  // convert to selected currency
  var principal = CURRENCIES.convert("BTC", window.calculator.principal)[window.calculator.currency]

  if (window.calculator.principal > 15.1) {
    if (document.getElementById("interestRate").disabled) {
      document.getElementById("interestRate").value = 3.7;
    }
    if (document.getElementById("investmentLength").disabled) {
      document.getElementById("investmentLength").value = 360;
    }
  } else
  if (window.calculator.principal > 2.1) {
    if (document.getElementById("interestRate").disabled) {
      document.getElementById("interestRate").value = 2.22;
    }
    if (document.getElementById("investmentLength").disabled) {
      document.getElementById("investmentLength").value = 180;
    }
  } else {
    if (document.getElementById("interestRate").disabled) {
      document.getElementById("interestRate").value = 1.4;
    }
    if (document.getElementById("investmentLength").disabled) {
      document.getElementById("investmentLength").value = 180;
    }
  }
  rate_onChange();
  updateDOMReinvestment();
}

function rate_onChange() {
  window.calculator.rate = Big((document.getElementById("interestRate").value / 100).toFixed(4));
}

function updateDOMReinvestment() {
  return;
  var data = calculateReinvestmentInterest(window.calculator.principal, window.calculator.rate, window.calculator.minToReinvest);
  // document.getElementById("daysBeforeReinvestment").innerHTML = data.days;
  document.getElementById("minToReinvest").innerHTML = window.calculator.minToReinvest;
}

function interestRate_toggleEditable() {
  if (document.getElementById("interestRate").disabled) {
    document.getElementById("interestRate").disabled = false;
    document.getElementById("interestRate_editable_icon").classList.remove("icon-disabled");
    document.getElementById("interestRate").focus();
  } else {
    document.getElementById("interestRate").disabled = true;
    document.getElementById("interestRate_editable_icon").classList.add("icon-disabled");
  }
  //window.stateManager.changeState("calculator","interestRate","editable", true);
}

function investmentLength_toggleEditable() {
  if (document.getElementById("investmentLength").disabled) {
    document.getElementById("investmentLength").disabled = false;
    document.getElementById("investmentLength_editable_icon").classList.remove("icon-disabled");
    document.getElementById("investmentLength").focus();
  } else {
    document.getElementById("investmentLength").disabled = true;
    document.getElementById("investmentLength_editable_icon").classList.add("icon-disabled");
  }
  //window.stateManager.changeState("calculator","interestRate","editable", true);
}

function btn_showEarnings() {
  generateTable();
}

function generateTable() {
  // validation:
  if (window.calculator.principal.lt(window.calculator.minToReinvest)) {
    showTooltip('Investment too small', 'Your investment must be more than ' + CURRENCIES.convert("BTC", window.calculator.minToReinvest)[window.calculator.currency] + ' ' + window.calculator.currency + '.');
  }

  // clear previous calculations:

  window.calculator.numActiveInvestments = Big(1);

  // document.getElementById("earnings").value = 0;
  window.calculator.totalInvestment = window.calculator.principal;


  // delete table
  document.getElementById("tableInvestmentWrapper").innerHTML = "";

  if (window.calculator.totalInvestment > 0 && window.calculator.rate > 0 && window.calculator.minToReinvest > 0 && window.calculator.principal.gte(window.calculator.minToReinvest)) {

    var json = generateInvestmentData();
    /* Table Heading */
    var tableInvestment = document.createElement("table");
    var tableSummary = document.createElement("table");
    var tr = document.createElement("tr");
    var th_days = document.createElement("th");
    th_days.style.width = "20px";
    th_days.style.minWidth = "20px";
    th_days.innerHTML = "Day";
    var th_transaction_type = document.createElement("th");
    th_transaction_type.style.width = "70px";
    th_transaction_type.style.minWidth = "70px";
    th_transaction_type.innerHTML = "Transaction";

    // get selected currency
    var cc = window.calculator.currency;
    var th_amount = document.createElement("th");
    var th_earnings = document.createElement("th");
    switch (cc) {
      case "BTC":
        th_amount.innerHTML = "Investment &#x20bf;";
        th_earnings.innerHTML = "Earnings &#x20bf;";
        break;
      case "USD":
        th_amount.innerHTML = "Investment $";
        th_earnings.innerHTML = "Earnings $";
        break;
      case "ZAR":
        th_amount.innerHTML = "Investment R";
        th_earnings.innerHTML = "Earnings R";
        break;
      default:
        break;
    }

    /* Build DOM */


    /* Investment table */
    tr = document.createElement("tr");
    tr.appendChild(th_days);
    tr.appendChild(th_transaction_type);
    tr.appendChild(th_earnings);
    tr.appendChild(th_amount);
    tableInvestment.appendChild(tr);

    //var accumulatedInvestment = 0;
    /* Table body */
    for (var i = 0; i < json.arrdata.length; i++) {
      var tr = document.createElement("tr");

      var td_day = document.createElement("td");
      td_day.innerHTML = json.arrdata[i].day;

      var transactionType = json.arrdata[i].type;
      tr.classList.add(transactionType);

      var td_transaction_type = document.createElement("td");
      td_transaction_type.classList.add('transaction');

      var reinvestedAmount = "";
      switch (transactionType) {
        case "reinvest":
          reinvestedAmount = json.arrdata[i].reinvestedAmount.toString();
          var reinvest_link = document.createElement("div");

          var reinvest_link_icon = document.createElement("div");
          reinvest_link_icon.setAttribute("title", "Show reinvestment calculation");
          reinvest_link_icon.classList.add('icon');
          reinvest_link_icon.classList.add('icon-tiny');
          reinvest_link_icon.classList.add('icon-pin');

          var reinvest_link_text = document.createElement("div");
          reinvest_link_text.setAttribute("title", "Show reinvestment calculation");
          reinvest_link_text.classList.add('text');
          reinvest_link_text.innerHTML = "Reinvest >";

          reinvest_link.classList.add('link');
          reinvest_link_text.dataset.reinvestedAmount = reinvestedAmount;
          reinvest_link_icon.dataset.reinvestedAmount = reinvestedAmount;
          reinvest_link.addEventListener('click', function(e) {
            document.getElementById("principal").value = CURRENCIES.convert("BTC", e.target.dataset.reinvestedAmount)[window.calculator.currency];
            principal_onChange();
            ui_calculator_convert_currencies();
            btn_showEarnings();
          });

          reinvest_link.appendChild(reinvest_link_icon);
          reinvest_link.appendChild(reinvest_link_text);
          td_transaction_type.appendChild(reinvest_link);
          break;
        case "interest":
          td_transaction_type.innerHTML = "Interest +";
          break;
        case "ended":
          td_transaction_type.innerHTML = "Ended -";
          break;
        default:
          break;
      }

      var investment = json.arrdata[i].investment;
      // get selected currency
      var cc_investment = CURRENCIES.convert("BTC", investment);

      var earnings = json.arrdata[i].earnings;

      var cc_earnings = CURRENCIES.convert("BTC", earnings);
      var td_investment = document.createElement("td");
      td_investment.classList.add('investment');
      var td_earnings = document.createElement("td");
      td_earnings.classList.add('balance');


      td_investment.innerHTML = cc_investment[window.calculator.currency];
      var strEarnings = "";
      if (reinvestedAmount != "") {
        //strEarnings += "( - ) ";// + CURRENCIES.convert("BTC", reinvestedAmount)[window.calculator.currency] + ") ";
      }
      strEarnings += cc_earnings[window.calculator.currency];

      td_earnings.innerHTML = strEarnings;

      window.calculator.totalInvestment = Big(investment).toFixed(8);
      tr.appendChild(td_day);
      tr.appendChild(td_transaction_type);
      tr.appendChild(td_earnings);
      tr.appendChild(td_investment);
      tableInvestment.appendChild(tr);
    }

    // get selected currency
    var conversions = CURRENCIES.convert("BTC", window.calculator.totalInvestment);
    var totalEarnings_btc = conversions['BTC_pretty'];

    var totalEarnings_usd = conversions['USD_pretty'];
    var totalEarnings_zar = conversions['ZAR_pretty'];

    if (window.calculator.reinvest) {
      document.getElementById("pleaseNoteOngoingReinvestments").classList.remove("hidden");
    } else {
      document.getElementById("pleaseNoteOngoingReinvestments").classList.add("hidden");
    }

    document.getElementById("tableSummary_investmentLength").innerHTML = "On day " + getInvestmentLength() + " your initial investment expires. " + ((window.calculator.reinvest) ? (window.calculator.numActiveInvestments + " active reinvestments remain!") : "");
    document.getElementById("tableSummary_initialInvestment_btc").innerHTML = CURRENCIES.convert("BTC", window.calculator.principal)["BTC_pretty"];
    document.getElementById("tableSummary_initialInvestment_usd").innerHTML = CURRENCIES.convert("BTC", window.calculator.principal)["USD_pretty"];
    document.getElementById("tableSummary_initialInvestment_zar").innerHTML = CURRENCIES.convert("BTC", window.calculator.principal)["ZAR_pretty"];
    document.getElementById("tableSummary_activeInvestmentsCount").innerHTML = window.calculator.numActiveInvestments;

    document.getElementById("tableSummary_totalInvestments_btc").innerHTML = totalEarnings_btc;
    document.getElementById("tableSummary_totalInvestments_usd").innerHTML = totalEarnings_usd;
    document.getElementById("tableSummary_totalInvestments_zar").innerHTML = totalEarnings_zar;

    document.getElementById("tableSummary_balance_btc").innerHTML = cc_earnings["BTC_pretty"];
    document.getElementById("tableSummary_balance_usd").innerHTML = cc_earnings["USD_pretty"];
    document.getElementById("tableSummary_balance_zar").innerHTML = cc_earnings["ZAR_pretty"];

    document.getElementById("pleaseNoteOngoingReinvestments").innerHTML = "* Please note that you may still have " + window.calculator.numActiveInvestments + " ongoing reinvestments after " + getInvestmentLength() + " days!";

    document.getElementById("tableSummaryWrapper").parentElement.classList.remove("hidden");
    document.getElementById("tableInvestmentWrapper").appendChild(tableInvestment);
    document.getElementById("tableInvestmentWrapper").parentElement.classList.remove("hidden");
  } else {
    document.getElementById("principal").focus();
  }
  window.calculator.tableDrawn = true;
}

function generateInvestmentData() {

  if (window.calculator.totalInvestment > 0 && window.calculator.rate > 0 && window.calculator.minToReinvest > 0) {

    var days = 0;
    var tableData = []; // stores day and investment
    //var totalInvestment = getAccumulatedInvestment();
    var earnings = Big(0); // running balance

    /* All earnings */
    while (days < (parseInt(getInvestmentLength()))) {
      days++;

      // get earnings for the day
      var interest = calculateDailyInterest(window.calculator.totalInvestment, window.calculator.rate);
      earnings = earnings.plus(Big(interest).toFixed(8));



      // send to table data for making a table
      tableData.push({
        type: 'interest',
        day: days,
        investment: window.calculator.totalInvestment, //CURRENCIES.convert("BTC", window.calculator.totalInvestment)[window.calculator.currency],
        earnings: earnings //CURRENCIES.convert("BTC", earnings)[window.calculator.currency]
      });

      if (window.calculator.reinvest) {
        // reinvest if more than minToReinvest, and clear earnings
        // if (days > 0 && earnings >= window.calculator.minToReinvest) {
        if (days > 0 && earnings.gte(window.calculator.minToReinvest)) {
          window.calculator.numActiveInvestments = window.calculator.numActiveInvestments.plus(1);
          window.calculator.totalInvestment = window.calculator.totalInvestment.plus(earnings);

          var reinvestedAmount = Big(earnings);
          // Table Data
          earnings = Big(0);
          tableData.push({
            type: 'reinvest',
            day: "",
            investment: window.calculator.totalInvestment, //CURRENCIES.convert("BTC", window.calculator.totalInvestment)[window.calculator.currency],
            reinvestedAmount: reinvestedAmount,
            earnings: earnings //CURRENCIES.convert("BTC", earnings)[window.calculator.currency]
          });
        }
      }
      // day investment ends
      if (days == getInvestmentLength()) {
        window.calculator.totalInvestment = window.calculator.totalInvestment.minus(window.calculator.principal);
        window.calculator.numActiveInvestments = window.calculator.numActiveInvestments.minus(1);
        if (window.calculator.reinvest) {
          earnings = "";
        } else {
          var interest = calculateDailyInterest(window.calculator.totalInvestment, window.calculator.rate);
          earnings = earnings.plus(interest);
        }
        //window.calculator.totalInvestment = window.calculator.totalInvestment.plus(earnings);
        tableData.push({
          type: 'ended',
          day: "",
          investment: window.calculator.totalInvestment, //CURRENCIES.convert("BTC", window.calculator.totalInvestment)[window.calculator.currency],
          earnings: earnings // CURRENCIES.convert("BTC", earnings)[window.calculator.currency]
        });
      }
    }

    return {
      totalROI: Big(window.calculator.totalInvestment).toFixed(8),
      arrdata: tableData
    }
  }
}


function getInvestmentLength() {
  return document.getElementById("investmentLength").value;
}

function reset() {
  window.calculator.setPrincipal(0);
  document.getElementById("principal").value = window.calculator.principal;

  // reset interest rate
  document.getElementById("interestRate").value = 1.4; // TODO: PHP ajax value
  window.calculator.rate = Big(0.014);

  // reset min to reinvest
  document.getElementById("minToReinvest").value = 0.0028; // TODO: PHP ajax value
  window.calculator.minToReinvest = Big(0.0028);

  // reset total investment
  window.calculator.totalInvestment = Big(0);
  window.calculator.numActiveInvestments = Big(0);
  // document.getElementById("daysBeforeReinvestment").innerHTML = 0;
  // delete table
  document.getElementById("tableInvestmentWrapper").parentElement.classList.add("hidden");
  document.getElementById("tableInvestmentWrapper").innerHTML = "";

  document.getElementById("principal").focus();
  document.getElementById("principal").select()
}

function calculator_onEnterKeyup(event) {
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    btn_showEarnings();
  }
}

function reinvestLink_onClick(amount) {
  alert(amount);
}
