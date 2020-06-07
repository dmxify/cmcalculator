var investments = [];
var globals = {
  balance: Big(0),
  day: 0,
  invest_weekends: false,
  reinvestment_rate: Big(0.0188),
  days_to_reinvest: 360
};
const MIN_REINVEST = 0.0028;


/* Use this function to start an investment */
var deposit = function(amount, rate, duration_days) {
  console.log(amount + " @ " + (rate * 100).toString().substring(0, 4) + "% for " + duration_days + " days, " + ((globals.invest_weekends) ? "including weekends" : "excluding weekends"));
  document.writeln(amount + " @ " + (rate * 100).toString().substring(0, 4) + "% for " + duration_days + " days, " + ((globals.invest_weekends) ? "including weekends" : "excluding weekends") + "</br>");
  investments.push({
    amount: Big(amount).toFixed(8),
    rate: Big(rate).toFixed(8),
    duration_days: duration_days,
    active: true,
    day: 0
  });
}
/* Use this function to start an investment / reinvestment */
var reinvest = function(amount, rate, duration_days) {
  console.log("new reinvestment");
  //console.log(amount + " @ " + (rate * 100).toString().substring(0, 4) + "% for " + duration_days + " days, " + ((globals.invest_weekends) ? "including weekends" : "excluding weekends") + "</br>");
  investments.push({
    amount: Big(amount).toFixed(8),
    rate: Big(rate).toFixed(8),
    duration_days: duration_days,
    active: true,
    day: 0,
    start_day: globals.day
  });
}

/* Use this function to reinvest constantly */
var compound = function(reinvestment_rate, duration_days) {
  for (var x = 0; x < duration_days; x++) {

    // manage current investments
    for (var i = 0; i < investments.length; i++) {
      /* check if contract still running */
      if (investments[i].active) {
        if (investments[i].day < investments[i].duration_days) {
          /* increase day count */
          investments[i].day++;
          /* if weekends are paused, and it is a weekend, do nothing, otherwise get earning, make reinvestment, etc... */
          if (isBotRunningToday()) {
            globals.balance = Big(globals.balance).plus(Big(investments[i].amount * investments[i].rate).toFixed(8));
          }

          //
        } else {
          investments[i].active = false;
        }
      }
    }
    /* do reinvestment */
    if (isBotRunningToday()) {
      if (globals.balance >= MIN_REINVEST && globals.day < globals.days_to_reinvest) {
        reinvest(globals.balance, reinvestment_rate, 180);
        globals.balance = 0;
      }
    }
    /* end of day */
    globals.day++;
  }
}

var getEarnings_NextDay = function() {
  // go through each investment
  for (var i = 0; i < investments.length; i++) {
    /* check if contract still running */
    if (investments[i].active) {
      if (investments[i].day < investments[i].duration_days) {
        /* increase day count */
        investments[i].day++;
        /* if weekends are paused, and it is a weekend, do nothing, otherwise get earning, make reinvestment, etc... */
        if (isBotRunningToday()) {
          globals.balance = Big(globals.balance).plus(Big(investments[i].amount * investments[i].rate).toFixed(8));
        }

        //
      } else {
        investments[i].active = false;
      }
    }
  }
  console.log("Earnings Complete");
  globals.day++;
  return Big(globals.balance).toFixed(8);
}

var getEarnings_OnDay = function(day) {
  while (globals.day < day) {
    // go through each investment
    for (var i = 0; i < investments.length; i++) {
      /* check if contract still running */
      if (investments[i].active) {
        if (investments[i].day < investments[i].duration_days) {
          /* increase day count */
          investments[i].day++;
          /* if weekends are paused, and it is a weekend, do nothing, otherwise get earning, make reinvestment, etc... */
          if (isBotRunningToday()) {
            globals.balance = Big(globals.balance).plus(Big(investments[i].amount * investments[i].rate).toFixed(8));
          }

          //
        } else {
          investments[i].active = false;
        }
      }
    }
    console.log("DAY COMPLETE");
    globals.day++;
  }
  return globals.balance;
}

function reset() {
  investments = [];
  globals.balance = Big(0);
  globals.day = 0;
  globals.invest_weekends = false;
  globals.reinvestment_rate = Big(0.0188);

}

var weeks = function(numWeeks) {
  days_total = 7 * numWeeks;
  return {
    days: days_total,
    week_days: days_total / 7 * 5,
    weekend_days: days_total / 7 * 2
  }
}
var isWeekend = function(day_number) {
  return (day_number % 7 == 0 || day_number % 7 == 6);
}

var isBotRunningToday = function() {
  if (globals.invest_weekends) {
    return true;
  } else if (isWeekend(globals.day)) {
    return false;
  } else {
    return true
  }
}


function printNext10DaysEarnings() {
  document.writeln("<br>");
  document.writeln("<br>");
  document.writeln("<b>Day 361 to 370 earnings:</b>");
  document.writeln("<br>");
  document.writeln("<br>");
  document.writeln("<span style='font-size:9pt;'>");
  for (var i = 0; i < 10; i++) {
    var weekendallowed = !isBotRunningToday();
    var earnings = getEarnings_NextDay();
    if (weekendallowed) {
      document.writeln("<span style='background-color:#e47272'>");
    }
    document.writeln("<b>Day " + globals.day + " balance:</b>");
    document.writeln(earnings);

    if (weekendallowed) {
      document.writeln("weekend </span>");
    }
    document.writeln("<br/>");
    //globals.balance = 0;
  }
  document.writeln("</span>");
}

function printEarnings(days) {
  document.writeln("<b>Day " + days + " earnings:</b>");
  document.writeln(getEarningsOnDay(days));
  document.writeln("<br/>");
  globals.balance = 0;
}

function demo1() {
  reset();
  globals.invest_weekends = true;
  globals.reinvestment_rate = 0.0188;
  deposit(0.0061, 0.0188, 180, true);
  compound(0.0188, 360);

  printNext10DaysEarnings();
}

function demo2() {
  reset();
  // FIRST 4 WEEKS:
  globals.invest_weekends = false;
  globals.reinvestment_rate = 0.014;
  deposit(0.0061, 0.014, weeks(4).days);
  compound(0.014, weeks(4).days);

  // AFTER 4 WEEKS:
  globals.invest_weekends = true;
  globals.reinvestment_rate = 0.0188;
  deposit(0.0061, 0.014, 180 - weeks(4).days);
  compound(0.0188, 360 - weeks(4).days);

  printNext10DaysEarnings();
}

function demo3() {
  reset();
  globals.invest_weekends = false;
  globals.reinvestment_rate = 0.014;
  deposit(0.0061, 0.014, 180);
  compound(0.014, 360);

  printNext10DaysEarnings();
}
