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
  document.getElementById("accumulatedInvestment").value = getPrincipal();
  updateDOMReinvestment();
}

function updateDOMReinvestment() {
  var data = calculateReinvestmentInterest(getAccumulatedInvestment(), getRate(), getMinToReinvest());
  document.getElementById("reinvestAmount").innerHTML = data.interest;
  document.getElementById("daysBeforeReinvestment").innerHTML = data.days;
}

function reinvest() {
  // clear table first
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

function compound180Days() {

  if (getAccumulatedInvestment() > 0 && getRate() > 0 && getMinToReinvest() > 0) {

    var days = 0;
    var reinvestmentData = [];
    var totalInvestment = getAccumulatedInvestment()
    while (days < 180) {
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

  if (getAccumulatedInvestment() > 0 && getRate() > 0 && getMinToReinvest() > 0) {

    var json = compound180Days();

    /* Table Heading */
    var table = document.createElement("table");
    var tr = document.createElement("tr");
    var th_days = document.createElement("th");
    th_days.innerHTML = "Reinvestment Day";
    var th_amount = document.createElement("th");
    th_amount.innerHTML = "Total Investment";

    /* Build DOM */
    tr.appendChild(th_days);
    tr.appendChild(th_amount);
    table.appendChild(tr);
    var accumulatedInvestment = 0;
    /* Table body */
    for (var i = 0; i < json.arrdata.length; i++) {
      var tr = document.createElement("tr");
      var td_day = document.createElement("td");
      td_day.innerHTML = json.arrdata[i].day;
      if (json.arrdata[i].day > 180) {
        tr.classList.add("red");
      }
      var td_investment = document.createElement("td");
      td_investment.innerHTML = json.arrdata[i].investment;
      accumulatedInvestment = json.arrdata[i].investment
      tr.appendChild(td_day);
      tr.appendChild(td_investment);
      table.appendChild(tr);
    }

    accumulatedInvestment = parseFloat(parseFloat(accumulatedInvestment).toFixed(8));
    var totalEarnings = (parseFloat(parseFloat(accumulatedInvestment - getPrincipal()).toFixed(8)));
    document.getElementById("accumulatedInvestment").value = accumulatedInvestment;
    document.getElementById("earnings").value = totalEarnings;
    var span = document.createElement("span");
    span.innerHTML = "Total 180 day earnings (accumulated investment minus principal):<br /><b>" + totalEarnings + " BTC</b><br /><br />";
    document.getElementById("table180Wrapper").appendChild(span);

    document.getElementById("table180Wrapper").appendChild(table);
  } else {
    document.getElementById("principal").focus();
  }
}

/* DOM getters */
function getPrincipal() {
  return document.getElementById("principal").value
}

function getRate() {
  return parseFloat((parseFloat(document.getElementById("interestRate").value) / 100).toFixed(8));
}

function getMinToReinvest() {
  return parseFloat(parseFloat(document.getElementById("earningsBeforeReinvest").value).toFixed(8));
}

function getEarnings() {
  return parseFloat(parseFloat(document.getElementById("earnings").value).toFixed(8));
}

function getAccumulatedInvestment() {
  return parseFloat(parseFloat(document.getElementById("accumulatedInvestment").value).toFixed(8));
}


function reset() {
  document.getElementById("principal").value = "";
  document.getElementById("interestRate").value = 1.88;
  document.getElementById("earningsBeforeReinvest").value = 0.0028;
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

function btnCurrency_onclick(el,currencyCode,affect={}) {
  document.querySelector(".radio-button.selected").classList.remove("selected");
  el.classList.add("selected");
}
