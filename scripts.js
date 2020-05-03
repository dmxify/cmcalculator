var formatterUSD = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
});
var formatterZAR = new Intl.NumberFormat('en-ZA', {
  style: 'currency',
  currency: 'ZAR',
});

function toggleDarkMode(e) {
  document.querySelector("body").classList.toggle("dark");
}

//https://api.coindesk.com/v1/bpi/currentprice/ZAR.json

function calculateReinvestmentInterest(principal, rate, minToReinvest) {
  var days = 0;
  var interest = 0;
  if (principal > 0 && rate > 0 && minToReinvest > 0) {
    while (interest < minToReinvest) {
      interest += principal * rate;
      days++;
    }
  }

  interest = parseFloat(parseFloat(interest).toFixed(8));
  return {
    interest,
    days
  };
};

function principal_onChange(e) {
  document.getElementById("reinvestAmount").value = "";
  var principal = getPrincipal();
  document.getElementById("accumulatedInvestment").value = principal;
  if (principal > 15.1) {
    document.getElementById("interestRate").value = 3.7;
    document.getElementById("contractLength").value = 360;
  } else
  if (principal > 2.1) {
    document.getElementById("interestRate").value = 2.22;
    document.getElementById("contractLength").value = 180;
  } else {
    document.getElementById("interestRate").value = 1.88;
    document.getElementById("contractLength").value = 180;
  }
  updateDOMReinvestment();
}

function updateDOMReinvestment() {
  var data = calculateReinvestmentInterest(getAccumulatedInvestment(), getRate(), getMinToReinvest());
  document.getElementById("reinvestAmount").innerHTML = data.interest;
  document.getElementById("daysBeforeReinvestment").innerHTML = data.days;
}

function reinvest() {
  // clear table first
  document.getElementById("table180Wrapper").parentElement.classList.add("hidden");
  document.getElementById("table180Wrapper").innerHTML = "";
  if (getAccumulatedInvestment() > 0 && getRate() > 0 && getMinToReinvest() > 0) {
    var data = calculateReinvestmentInterest(getAccumulatedInvestment(), getRate(), getMinToReinvest());
    //document.getElementById("principal").value = (getPrincipal() + data.reinvestAmount).toFixed(8)
    document.getElementById("accumulatedInvestment").value = (getAccumulatedInvestment() + data.interest).toFixed(8);
    document.getElementById("earnings").value = (getEarnings() + data.interest).toFixed(8)
    updateDOMReinvestment();
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
      days += data.days;
      totalInvestment += parseFloat(parseFloat(data.interest).toFixed(8));
      totalInvestment = parseFloat(parseFloat(totalInvestment).toFixed(8));
      reinvestmentData.push({
        day: days,
        investment: totalInvestment
      })
    }

    return {
      totalROI: parseFloat(parseFloat(totalInvestment - getPrincipal()).toFixed(8)),
      arrdata: reinvestmentData
    }
  }
}

function generateTable() {

  // clear previous calculations:

  document.getElementById("earnings").value = 0;
  document.getElementById("accumulatedInvestment").value = getPrincipal();
  document.getElementById("reinvestAmount").innerHTML = 0;
  document.getElementById("daysBeforeReinvestment").innerHTML = 0;

  // delete table
  document.getElementById("table180Wrapper").innerHTML = "";
  document.getElementById("table180Summary").innerHTML = "";

  if (getAccumulatedInvestment() > 0 && getRate() > 0 && getMinToReinvest() > 0) {

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

    /* Build DOM */
    tr.appendChild(th_days);
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
      // if (json.arrdata[i].day > 180) {
      //   tr.classList.add("red");
      // }
      var td_investment_btc = document.createElement("td");
      var td_investment_usd = document.createElement("td");
      var td_investment_zar = document.createElement("td");
      var investmentBTC = json.arrdata[i].investment;
      td_investment_btc.innerHTML = investmentBTC;
      td_investment_usd.innerHTML = formatterUSD.format(getGlobal('exchange_usd_rate_float') * investmentBTC);
      td_investment_zar.innerHTML = formatterZAR.format(getGlobal('exchange_usd_rate_float') * investmentBTC);;

      accumulatedInvestment = investmentBTC
      tr.appendChild(td_day);
      tr.appendChild(td_investment_btc);
      tr.appendChild(td_investment_usd);
      tr.appendChild(td_investment_zar);
      table.appendChild(tr);
    }

    accumulatedInvestment = parseFloat(parseFloat(accumulatedInvestment).toFixed(8));
    var totalEarnings_btc = (parseFloat(parseFloat(accumulatedInvestment - getPrincipal()).toFixed(8)));
    var totalEarnings_usd = formatterUSD.format(getGlobal('exchange_usd_rate_float') * totalEarnings_btc);
    var totalEarnings_zar = formatterZAR.format(getGlobal('exchange_zar_rate_float') * totalEarnings_btc);

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

/* DOM getters */
function getPrincipal() {
  return document.getElementById("principal").value;
}

function getContractLength() {
  return document.getElementById("contractLength").value;
}

function getRate() {
  return parseFloat((parseFloat(document.getElementById("interestRate").value) / 100).toFixed(8));
}

function getMinToReinvest() {
  return parseFloat(parseFloat(getGlobal("earningsBeforeReinvest")).toFixed(8));
}

function getEarnings() {
  return parseFloat(parseFloat(document.getElementById("earnings").value).toFixed(8));
}

function getAccumulatedInvestment() {
  return parseFloat(parseFloat(document.getElementById("accumulatedInvestment").value).toFixed(8));
}


function reset() {
  document.getElementById("principal").value = "";
  document.getElementById("interestRate").value = 1.88; // TODO: PHP ajax value
  document.getElementById("earningsBeforeReinvest").value = 0.00288; // TODO: PHP ajax value
  document.getElementById("earnings").value = 0;
  document.getElementById("accumulatedInvestment").value = 0;
  document.getElementById("reinvestAmount").innerHTML = 0;
  document.getElementById("daysBeforeReinvestment").innerHTML = 0;
  document.getElementById("principal").focus();
}


function copy(el) {

  el.select();
  document.execCommand("copy");
}

function btnCurrency_onclick(el, currencyCode) {
  document.querySelector(".radio-button.selected").classList.remove("selected");
  el.classList.add("selected");
  setGlobal("ui_simple_currency", currencyCode);
}

function updateExchangeRates() {
  fetch('https://api.coindesk.com/v1/bpi/currentprice/ZAR.json')
    .then(response => response.json())
    .then((data) => {
      setGlobal('exchange_usd_rate_pretty', formatterUSD.format(parseFloat(parseFloat(data.bpi.USD.rate_float).toFixed(2))));
      setGlobal('exchange_usd_rate_float', parseFloat(parseFloat(data.bpi.USD.rate_float).toFixed(2)));
      setGlobal('exchange_zar_rate_pretty', formatterZAR.format(parseFloat(parseFloat(data.bpi.ZAR.rate_float).toFixed(2))));
      setGlobal('exchange_zar_rate_float', parseFloat(parseFloat(data.bpi.ZAR.rate_float).toFixed(2)));
      setGlobal('exchange_disclaimer', data.disclaimer);
      setGlobal('exchange_time_updated', data.time.updated);
    });
}

function change_layout() {
  alert("This is a future feature! Stay tuned...");
}
