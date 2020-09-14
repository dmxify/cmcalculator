const CURRENCIES = {
  exchange: function(base, quote, amount) {
    var value = Big(0);
    if (parseFloat(amount) > 0) {
      // get base/quote data:
      var rates = getGlobal('exchange_rates');
      try {
        var rate = Big(rates[quote].value).div(Big(rates[base].value));
        var decimals = (rates[quote].type == 'crypto') ? 8 : 2;
        value = rate.times(amount).toFixed(decimals);
      } catch (e) {}
    }
    return value;
  },
  exchange_list: function(base, arr_quotes, amount) {
    var outputValues = {};
    var rates = getGlobal('exchange_rates');

    if (parseFloat(amount) > 0) {
      var quote;
      for (var i = 0; i < arr_quotes.length; i++) {
        quote = arr_quotes[i];
        var value = Big(0);
        // get base/quote data:
        try {
          // get value:
          var rate = Big(rates[quote].value).div(Big(rates[base].value));
          var decimals = (rates[quote].type == 'crypto') ? 8 : 2;
          value = rate.times(amount).toFixed(decimals);

          // prettifying currency format:
          var pretty = "";
          switch (rates[quote].type) {
            case 'fiat':
              try {
                pretty = new Intl.NumberFormat('en-US', {
                  style: 'currency',
                  currency: quote,
                }).format(value);
              } catch {}
              break;
            case 'crypto':
            default:
              pretty = value + ' ' + rates[quote].unit;
              break;
          }

          outputValues[quote] = {
            value: value,
            pretty: pretty
          };
        } catch (e) {}
      }
    }
    return outputValues;
  }
};

function updateExchangeRates() {

  fetch('https://api.coingecko.com/api/v3/exchange_rates')
    .then(response => response.json())
    .then((data) => {
      setGlobal('exchange_rates', data.rates);

      setGlobal('exchange_disclaimer', "Data provided by CoinGecko - Updated every 1 to 10 minutes");
      setGlobal('exchange_time_updated', formatDateTime(new Date()));
      // UI:
      update_tickers();
    });
}

function getRate(currency) {
  var rate = Big(0);
  try {
    rate = Big(getGlobal('exchange_rates')[currency].value);
  } catch (e) {

  }
  return rate;
}

function formatDateTime(date) {

  var year = date.getFullYear();
  var month = date.getMonth();
  switch (month) {
    case 0:
      month = "Jan"
      break;
    case 1:
      month = "Feb"
      break;
    case 2:
      month = "Mar"
      break;
    case 3:
      month = "Apr"
      break;
    case 4:
      month = "May"
      break;
    case 5:
      month = "Jun"
      break;
    case 6:
      month = "Jul"
      break;
    case 7:
      month = "Aug"
      break;
    case 8:
      month = "Sept"
      break;
    case 9:
      month = "Oct"
      break;
    case 10:
      month = "Nov"
      break;
    case 11:
      month = "Dec"
      break;
  }
  var day = date.getDate();
  var hours = date.getHours();
  var minutes = "0" + date.getMinutes();
  var seconds = "0" + date.getSeconds();

  // Will display time in 10:30:23 format
  var formattedTime = day + ' ' + month + ', ' + year + ' ' + hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
  return formattedTime;
}

function unix_timestamp_to_datetime(unix_timestamp) {
  // Create a new JavaScript Date object based on the timestamp
  // multiplied by 1000 so that the argument is in milliseconds, not seconds.
  var date = new Date(unix_timestamp * 1000);

  return formatDate(date);
}

function update_tickers() {
  document.getElementById('exchange_tickers').innerHTML = "";

  // update exchange_tickers
  var arr_bases = ['btc', 'eth', 'xrp'];
  var arr_quotes = ['usd', 'zar', 'eur'];
  for (var b = 0; b < arr_bases.length; b++) {

    var exchange_rates = CURRENCIES.exchange_list(arr_bases[b], arr_quotes, 1);
    // new base line:
    var tickerLine = document.createElement('div');
    tickerLine.classList.add('ticker-line');
    var tickerBase = document.createElement('div');
    tickerBase.classList.add('ticker-base');
    var text = document.createElement('div');
    text.classList.add('text');
    text.innerHTML = "1 " + arr_bases[b].toUpperCase() + " = ";
    tickerBase.appendChild(text);
    tickerLine.appendChild(tickerBase);
    // generate quotes for each base
    for (var q = 0; q < arr_quotes.length; q++) {
      var ticker = document.createElement('div');
      ticker.classList.add('ticker');
      var rate = document.createElement('div');
      rate.classList.add('rate');
      try {
        rate.innerHTML = exchange_rates[arr_quotes[q]].pretty;
      } catch(e){
        console.log(e);
      }
      ticker.appendChild(rate);
      tickerLine.appendChild(ticker);
    }
    document.getElementById('exchange_tickers').appendChild(tickerLine);
  }
}
