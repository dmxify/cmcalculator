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


function principal_onChange(e) {
  var cc = getGlobal("ui_calculator_currency"); // get selected currency code


  document.getElementById("reinvestAmount").value = "";
  var principal = getPrincipal();
  var principal_btc = CURRENCIES.convert(cc, principal)['BTC']
  document.getElementById("accumulatedInvestment").value = principal
  if (principal_btc > 15.1) {
    document.getElementById("interestRate").value = 3.7;
    document.getElementById("contractLength").value = 360;
  } else
  if (principal_btc > 2.1) {
    document.getElementById("interestRate").value = 2.22;
    document.getElementById("contractLength").value = 180;
  } else {
    document.getElementById("interestRate").value = 1.88;
    document.getElementById("contractLength").value = 180;
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

function generateTable() {
  // validation:
  if (Big(getPrincipal()).lt(Big(getMinToReinvest()))) {
    showTooltip('Investment too small', 'Your investment must be more than ' + CURRENCIES.convert(getGlobal("ui_calculator_currency"), getMinToReinvest())[getGlobal("ui_calculator_currency")] + ' ' + getGlobal("ui_calculator_currency") + '.');
  }

  // clear previous calculations:

  document.getElementById("earnings").value = 0;
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
    th_days.innerHTML = "Reinvestment Day";
    var th_amount_btc = document.createElement("th");
    th_amount_btc.innerHTML = "Total Investment &#x20bf;";
    var th_amount_usd = document.createElement("th");
    th_amount_usd.innerHTML = "Total Investment $";
    var th_amount_zar = document.createElement("th");
    th_amount_zar.innerHTML = "Total Investment R";
    var th_total_earnings = document.createElement("th");
    th_total_earnings.innerHTML = "Total Earnings";

    /* Build DOM */
    tr.appendChild(th_days);
    tr.appendChild(th_amount_btc);
    tr.appendChild(th_amount_usd);
    tr.appendChild(th_amount_zar);
    tr.appendChild(th_total_earnings);
    table.appendChild(tr);
    var accumulatedInvestment = 0;
    /* Table body */
    for (var i = 0; i < json.arrdata.length; i++) {
      var tr = document.createElement("tr");
      var td_day = document.createElement("td");
      td_day.innerHTML = json.arrdata[i].day;
      // if (json.arrdata[i].day > 180) {
      //   tr.classList.add("red");
      // }
      var td_investment_btc = document.createElement("td");
      var td_investment_usd = document.createElement("td");
      var td_investment_zar = document.createElement("td");
      var td_investment_earnings = document.createElement("td");

      var investment = json.arrdata[i].investment;
      // get selected currency
      var cc = getGlobal("ui_calculator_currency");
      var conversions = CURRENCIES.convert(cc, investment);
      td_investment_btc.innerHTML = conversions['BTC'];
      td_investment_usd.innerHTML = conversions['USD'];
      td_investment_zar.innerHTML = conversions['ZAR'];
      td_investment_earnings.innerHTML = '<span style="color:#19b641;">coming soon...</span>';

      accumulatedInvestment = investment;
      tr.appendChild(td_day);
      tr.appendChild(td_investment_btc);
      tr.appendChild(td_investment_usd);
      tr.appendChild(td_investment_zar);
      tr.appendChild(td_investment_earnings);
      table.appendChild(tr);
    }

    // get selected currency
    var cc = getGlobal("ui_calculator_currency");
    var conversions = CURRENCIES.convert(cc, accumulatedInvestment);
    var totalEarnings_btc = conversions['BTC'];

    var totalEarnings_usd = conversions['USD_pretty'];
    var totalEarnings_zar = conversions['ZAR_pretty'];

    document.getElementById("accumulatedInvestment").value = accumulatedInvestment;
    document.getElementById("earnings").value = totalEarnings_btc;
    var span = document.createElement("span");
    span.innerHTML = "<b>Total 180 day earnings</b> (compounded interest - investment):<br /><br /><b>" + totalEarnings_btc + "</b> BTC | <b>" + totalEarnings_usd + "</b> USD | <b>" + totalEarnings_zar + "</b> ZAR<br /><br />";
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
    var reinvestmentData = [];
    var totalInvestment = getAccumulatedInvestment()
    while (days < getContractLength()) {
      var data = calculateReinvestmentInterest(totalInvestment, getRate(), getMinToReinvest());

      if (days > 0) {
        totalInvestment = totalInvestment.plus(Big(data.interest).toFixed(8));
      }
      reinvestmentData.push({
        day: days,
        investment: totalInvestment
      })
      days += data.days;
    }
    reinvestmentData.push({
      day: days,
      investment: totalInvestment - getPrincipal()
    });

    return {
      totalROI: Big(totalInvestment - getPrincipal()).toFixed(8),
      arrdata: reinvestmentData
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
  document.getElementById("interestRate").value = 1.88; // TODO: PHP ajax value
  document.getElementById("earningsBeforeReinvest").value = 0.00288; // TODO: PHP ajax value
  document.getElementById("earnings").value = 0;
  document.getElementById("accumulatedInvestment").value = 0;
  document.getElementById("reinvestAmount").innerHTML = 0;
  document.getElementById("daysBeforeReinvestment").innerHTML = 0;
  // delete table
  document.getElementById("table180Wrapper").parentElement.classList.add("hidden");
  document.getElementById("table180Wrapper").innerHTML = "";
  document.getElementById("table180Summary").innerHTML = "";

  document.getElementById("principal").focus();
}
