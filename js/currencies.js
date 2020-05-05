const CURRENCIES = {
  btc: {
    decimals: 8,
    symbol: '&#x20bf;'
  },
  usd: {
    decimals: 2,
    symbol: '$'
  },
  zar: {
    decimals: 2,
    symbol: 'R'
  },
  convert: function(currency, value) {
    var btc = "0.00000000",
      usd = "0.00",
      zar = "0.00";
    if (parseFloat(value) > 0) {
      switch (currency) {
        case 'BTC':
          btc = Big(value).toFixed(8);
          usd = Big(value * getGlobal("exchange_usd_rate_float")).toFixed(2);
          zar = Big(value * getGlobal("exchange_zar_rate_float")).toFixed(2);
          break;
        case 'USD':
          usd = Big(value).toFixed(2);
          btc = Big(value / getGlobal("exchange_usd_rate_float")).toFixed(8);
          zar = Big(btc * getGlobal("exchange_zar_rate_float")).toFixed(2);
          break;
        case 'ZAR':
          zar = Big(value).toFixed(2);
          btc = Big(value / getGlobal("exchange_zar_rate_float")).toFixed(8);
          usd = Big(btc * getGlobal("exchange_usd_rate_float")).toFixed(2);
          break;
        default:
          break;
      }
    }

    return {
      BTC: btc,
      USD: usd,
      ZAR: zar,
      USD_pretty: formatterUSD.format(usd),
      ZAR_pretty: formatterZAR.format(zar)
    };
  }
};

var formatterUSD = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
});
var formatterZAR = new Intl.NumberFormat('en-ZA', {
  style: 'currency',
  currency: 'ZAR',
});

function updateExchangeRates() {
  fetch('https://api.coindesk.com/v1/bpi/currentprice/ZAR.json')
    .then(response => response.json())
    .then((data) => {
      setGlobal('exchange_usd_rate_pretty', formatterUSD.format(Big(data.bpi.USD.rate_float)));
      setGlobal('exchange_usd_rate_float', Big(data.bpi.USD.rate_float));
      setGlobal('exchange_zar_rate_pretty', formatterZAR.format(Big(data.bpi.ZAR.rate_float)));
      setGlobal('exchange_zar_rate_float', Big(data.bpi.ZAR.rate_float));

      setGlobal('exchange_disclaimer', data.disclaimer);
      setGlobal('exchange_time_updated', data.time.updated);

      setGlobal('exchange_usd_zar_rate_float', (getGlobal("exchange_zar_rate_float").div(getGlobal("exchange_usd_rate_float"))));
    });
}
