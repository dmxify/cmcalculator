window.calculator = {
  currency: "BTC",
  principal: 0,
  rate: 0.0188,
  earnings: 0,
  setCurrency: function(currencyCode) {
    /* Update UI (currency selector radio buttons)*/
    document.querySelector(".radio-button.selected").classList.remove("selected");
    document.querySelectorAll('[data-calculator-cc]').forEach((el, i) => {
      var cc = el.dataset.calculatorCc;
      if (cc == currencyCode) {
        el.classList.add("selected");
      }
    });
    /* Update UI (currency symbols)*/

    setGlobal("ui_calculator_currency_previous", getGlobal("ui_calculator_currency"));
    setGlobal("ui_calculator_currency", currencyCode);
    ui_calculator_symbols_update();
    ui_calculator_convert_currencies();
  }
};

function ui_calculator_symbols_update() {
  var cc = getGlobal("ui_calculator_currency");
  document.querySelectorAll(".ui_calculator_symbol").forEach((el, i) => {
    el.innerHTML = cc;
  });
}

function ui_calculator_convert_currencies() {
  var principal = getPrincipal();
  /* currency conversions */
  var cc = getGlobal("ui_calculator_currency"); // get selected currency code
  var cc_previous = getGlobal("ui_calculator_currency_previous"); // get selected currency code
  /*currency_converter.js's function "convert_currency": */

  principal = CURRENCIES.convert(cc_previous, principal)[cc]
  document.getElementById('principal').value = principal;
  principal_onChange();
}

function calculateReinvestmentInterest(principal, rate, minToReinvest) {
  var days = 0;
  var interest = 0;
  if (principal > 0 && rate > 0 && minToReinvest > 0 && Big(principal).gte(Big(minToReinvest))) {
    while (interest < minToReinvest) {
      interest += principal * rate;
      days++;
    }
  }

  var cc = getGlobal("ui_calculator_currency");
  return {
    interest: CURRENCIES.convert(cc, interest)[cc],
    days: days
  };
};

function calculateDailyInterest(principal, rate) {
  var interest = 0;
  if (principal > 0 && rate > 0) {
    interest = principal * rate;
  }

  var cc = getGlobal("ui_calculator_currency");
  return CURRENCIES.convert(cc, interest)[cc];
};

function principal_onChange(e) {
  var cc = getGlobal("ui_calculator_currency"); // get selected currency code


  document.getElementById("reinvestAmount").value = "";
  var principal = getPrincipal();
  var principal_btc = CURRENCIES.convert(cc, principal)['BTC']
  document.getElementById("accumulatedInvestment").value = principal
  if (principal_btc > 15.1) {
    if (document.getElementById("interestRate").disabled) {
      document.getElementById("interestRate").value = 3.7;
    }
    if (document.getElementById("contractLength").disabled) {
      document.getElementById("contractLength").value = 360;
    }
  } else
  if (principal_btc > 2.1) {
    if (document.getElementById("interestRate").disabled) {
      document.getElementById("interestRate").value = 2.22;
    }
    if (document.getElementById("contractLength").disabled) {
      document.getElementById("contractLength").value = 180;
    }
  } else {
    if (document.getElementById("interestRate").disabled) {
      document.getElementById("interestRate").value = 1.4;
    }
    if (document.getElementById("contractLength").disabled) {
      document.getElementById("contractLength").value = 180;
    }
  }
  updateDOMReinvestment();
}

function updateDOMReinvestment() {
  var data = calculateReinvestmentInterest(getPrincipal(), getRate(), getMinToReinvest());
  document.getElementById("reinvestAmount").innerHTML = data.interest;
  document.getElementById("daysBeforeReinvestment").innerHTML = data.days;
  document.getElementById("earningsBeforeReinvest").innerHTML = getMinToReinvest();
}

// function reinvest() {
//   // clear table first
//   document.getElementById("table180Wrapper").parentElement.classList.add("hidden");
//   document.getElementById("table180Wrapper").innerHTML = "";
//   if (getAccumulatedInvestment() > 0 && getRate() > 0 && getMinToReinvest() > 0) {
//     var data = calculateReinvestmentInterest(getAccumulatedInvestment(), getRate(), getMinToReinvest());
//     //document.getElementById("principal").value = (getPrincipal() + data.reinvestAmount).toFixed(8)
//     document.getElementById("accumulatedInvestment").value = (getAccumulatedInvestment() + data.interest).toFixed(8);
//     document.getElementById("earnings").value = (getEarnings() + data.interest).toFixed(8)
//     updateDOMReinvestment();
//   } else {
//     document.getElementById("principal").focus();
//   }
// }

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

function contractLength_toggleEditable() {
  if (document.getElementById("contractLength").disabled) {
    document.getElementById("contractLength").disabled = false;
    document.getElementById("contractLength_editable_icon").classList.remove("icon-disabled");
    document.getElementById("contractLength").focus();
  } else {
    document.getElementById("contractLength").disabled = true;
    document.getElementById("contractLength_editable_icon").classList.add("icon-disabled");
  }
  //window.stateManager.changeState("calculator","interestRate","editable", true);
}

function btn_showEarnings() {
  generateTable();
}

function generateTable() {
  // validation:
  if (Big(getPrincipal()).lt(Big(getMinToReinvest()))) {
    showTooltip('Investment too small', 'Your investment must be more than ' + CURRENCIES.convert(getGlobal("ui_calculator_currency"), getMinToReinvest())[getGlobal("ui_calculator_currency")] + ' ' + getGlobal("ui_calculator_currency") + '.');
  }

  // clear previous calculations:

  // document.getElementById("earnings").value = 0;
  document.getElementById("accumulatedInvestment").value = getPrincipal();
  document.getElementById("reinvestAmount").innerHTML = 0;
  document.getElementById("daysBeforeReinvestment").innerHTML = 0;

  // delete table
  document.getElementById("table180Wrapper").innerHTML = "";
  document.getElementById("table180Summary").innerHTML = "";

  if (getAccumulatedInvestment() > 0 && getRate() > 0 && getMinToReinvest() > 0 && Big(getPrincipal()).gte(Big(getMinToReinvest()))) {

    var json = compoundDays();

    /* Table Heading */
    var table = document.createElement("table");
    var tr = document.createElement("tr");
    var th_days = document.createElement("th");
    th_days.innerHTML = "Day";
    var th_transaction_type = document.createElement("th");
    th_transaction_type.innerHTML = "Transaction";
    var th_amount_btc = document.createElement("th");
    th_amount_btc.innerHTML = "Total Investment &#x20bf;";
    var th_amount_usd = document.createElement("th");
    th_amount_usd.innerHTML = "Total Investment $";
    var th_amount_zar = document.createElement("th");
    th_amount_zar.innerHTML = "Total Investment R";
    var th_total_earnings = document.createElement("th");
    th_total_earnings.innerHTML = "Daily Earnings";

    /* Build DOM */
    tr.appendChild(th_days);
    tr.appendChild(th_transaction_type);
    tr.appendChild(th_total_earnings);
    tr.appendChild(th_amount_btc);
    tr.appendChild(th_amount_usd);
    tr.appendChild(th_amount_zar);
    table.appendChild(tr);
    var accumulatedInvestment = 0;
    /* Table body */
    for (var i = 0; i < json.arrdata.length; i++) {
      var tr = document.createElement("tr");

      var td_day = document.createElement("td");
      td_day.innerHTML = json.arrdata[i].day;

      var transactionType = json.arrdata[i].type;
      tr.classList.add(transactionType);

      var td_transaction_type = document.createElement("td");
      td_transaction_type.classList.add('transaction');

      var transactionTypeText = ""
      switch (transactionType) {
        case "reinvest":
          transactionTypeText = "Reinvest >";
          break;
        case "interest":
          transactionTypeText = "Interest +";
          break;
        case "ended":
          transactionTypeText = "Ended -";
          break;
        default:
          break;
      }

      td_transaction_type.innerHTML = transactionTypeText;

      // if (json.arrdata[i].day > 180) {
      //   tr.classList.add("red");
      // }
      var td_investment_btc = document.createElement("td");
      td_investment_btc.classList.add('investment');
      var td_investment_usd = document.createElement("td");
      td_investment_usd.classList.add('investment');
      var td_investment_zar = document.createElement("td");
      td_investment_zar.classList.add('investment');
      var td_investment_earnings = document.createElement("td");
      td_investment_earnings.classList.add('balance');

      var investment = json.arrdata[i].investment;
      // get selected currency
      var cc = getGlobal("ui_calculator_currency");
      var conversions = CURRENCIES.convert(cc, investment);
      td_investment_btc.innerHTML = conversions['BTC'];
      td_investment_usd.innerHTML = "$" + conversions['USD'];
      td_investment_zar.innerHTML = "R" + conversions['ZAR'];

      var earnings = json.arrdata[i].earnings;
      conversions = CURRENCIES.convert(cc, earnings);
      var earningsBTC = conversions['BTC'];

      td_investment_earnings.innerHTML = earningsBTC;

      accumulatedInvestment = Big(investment).toFixed(8);
      tr.appendChild(td_day);
      tr.appendChild(td_transaction_type);
      tr.appendChild(td_investment_earnings);
      tr.appendChild(td_investment_btc);
      tr.appendChild(td_investment_usd);
      tr.appendChild(td_investment_zar);
      table.appendChild(tr);
    }

    // get selected currency
    var cc = getGlobal("ui_calculator_currency");
    var conversions = CURRENCIES.convert(cc, accumulatedInvestment);
    var totalEarnings_btc = conversions['BTC'];

    var totalEarnings_usd = conversions['USD_pretty'];
    var totalEarnings_zar = conversions['ZAR_pretty'];

    document.getElementById("accumulatedInvestment").value = accumulatedInvestment;
    // document.getElementById("earnings").value = totalEarnings_btc;
    var span = document.createElement("span");
    span.innerHTML = "<b>Total " + getContractLength() + " day investment</b> (compounded interest - investment):<br /><br /><b>" + totalEarnings_btc + "</b> BTC | <b>" + totalEarnings_usd + "</b> USD | <b>" + totalEarnings_zar + "</b> ZAR<br /><br />";
    document.getElementById("table180Summary").appendChild(span);

    document.getElementById("table180Wrapper").appendChild(table);
    document.getElementById("table180Wrapper").parentElement.classList.remove("hidden");
  } else {
    document.getElementById("principal").focus();
  }
}

function compoundDays() {

  if (getAccumulatedInvestment() > 0 && getRate() > 0 && getMinToReinvest() > 0) {

    var days = 0;
    var tableData = []; // stores day and investment
    var totalInvestment = getAccumulatedInvestment();
    var earnings = Big(0); // running balance

    /* All earnings */
    while (days < (parseInt(getContractLength()))) {
      days++;

      // get earnings for the day
      var interest = calculateDailyInterest(totalInvestment, getRate());
      earnings = earnings.plus(Big(interest).toFixed(8));



      // send to table data for making a table
      tableData.push({
        type: 'interest',
        day: days,
        investment: totalInvestment,
        earnings: earnings
      });
      // reinvest if more than minToReinvest, and clear earnings
      // if (days > 0 && earnings >= getMinToReinvest()) {
      if (days > 0 && earnings.gt(getMinToReinvest())) {

        totalInvestment = totalInvestment.plus(Big(earnings).toFixed(8));
        earnings = Big(0);
        tableData.push({
          type: 'reinvest',
          day: "",
          investment: totalInvestment,
          earnings: earnings
        })
      }

      // day investment ends
      if (days == getContractLength()) {
        totalInvestment = totalInvestment.minus(getPrincipal());
        var interest = calculateDailyInterest(totalInvestment, getRate());
        earnings = earnings.plus(Big(interest).toFixed(8));
        //totalInvestment = totalInvestment.plus(earnings);
        tableData.push({
          type: 'ended',
          day: "",
          investment: totalInvestment,
          earnings: ""
        });
      }
    }

    /* Reinvestments only: */
    // while (days < getContractLength()) {
    //   var data = calculateReinvestmentInterest(totalInvestment, getRate(), getMinToReinvest());
    //
    //   if (days > 0) {
    //     totalInvestment = totalInvestment.plus(Big(data.interest).toFixed(8));
    //   }
    //   tableData.push({
    //     day: days+1,
    //     investment: totalInvestment
    //   })
    //   days += data.days;
    // }


    return {
      totalROI: Big(totalInvestment).toFixed(8),
      arrdata: tableData
    }
  }
}


/* DOM getters */
function getPrincipal() {
  var principal = parseFloat(document.getElementById("principal").value);
  if (principal < 0 || isNaN(principal)) {
    principal = 0;
  }

  return Big(principal);
}

function getContractLength() {
  return document.getElementById("contractLength").value;
}

function getRate() {
  return Big(document.getElementById("interestRate").value / 100).toFixed(4);
}

function getMinToReinvest() {
  var cc = getGlobal("ui_calculator_currency");
  return CURRENCIES.convert('BTC', getGlobal("earningsBeforeReinvest"))[cc];
}

// function getEarnings() {
//   return Big(document.getElementById("earnings").value).toFixed(8);
// }

function getAccumulatedInvestment() {
  var val = document.getElementById("accumulatedInvestment").value;
  if (parseFloat(val) > 0) {
    return Big(Big(val).toFixed(8));
  }
  return 0;
}


function reset() {
  document.getElementById("principal").value = "";
  document.getElementById("interestRate").value = 1.4; // TODO: PHP ajax value
  document.getElementById("earningsBeforeReinvest").value = 0.00288; // TODO: PHP ajax value
  // document.getElementById("earnings").value = 0;
  document.getElementById("accumulatedInvestment").value = 0;
  document.getElementById("reinvestAmount").innerHTML = 0;
  document.getElementById("daysBeforeReinvestment").innerHTML = 0;
  // delete table
  document.getElementById("table180Wrapper").parentElement.classList.add("hidden");
  document.getElementById("table180Wrapper").innerHTML = "";
  document.getElementById("table180Summary").innerHTML = "";

  document.getElementById("principal").focus();
}

function calculator_onEnterKeyup(event) {
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    btn_showEarnings();
  }
}
