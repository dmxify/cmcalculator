// TODO: validate only if number, fail gracefully

function convert_currency(pair, value) {
  var btc_eth = getGlobal("exchange_btc_eth_rate_float");
  var btc_usd = getGlobal("exchange_btc_usd_rate_float");
  var btc_zar = getGlobal("exchange_btc_zar_rate_float");

  var eth_usd = getGlobal("exchange_eth_usd_rate_float");
  var eth_zar = getGlobal("exchange_eth_zar_rate_float");

  var usd_zar = getGlobal("exchange_usd_zar_rate_float");

  switch (pair) {
    /* Bitcoin  */
    case 'BTC_ETH':
      return (value * btc_eth).toFixed(2);
      break;
    case 'BTC_USD':
      return (value * btc_usd).toFixed(2);
      break;
    case 'BTC_ZAR':
      return (value * btc_zar).toFixed(2);
      break;

      /* Ethereum  */
    case 'ETH_BTC':
      return (value / btc_eth).toFixed(8);
      break;
    case 'ETH_USD':
      return (value * eth_usd).toFixed(2);
      break;
    case 'ETH_ZAR':
      return (value * eth_zar).toFixed(2);
      break;

      /* USD */
    case 'USD_BTC':
      return (value / btc_usd).toFixed(8);
      break;
    case 'USD_ETH':
      return (value / eth_usd).toFixed(8);
      break;
    case 'USD_ZAR':
      return (value * usd_zar).toFixed(2);
      break;

      /* ZAR */
    case 'ZAR_BTC':
      return (value / btc_zar).toFixed(8);
      break;
    case 'ZAR_ETH':
      return (value / eth_zar).toFixed(2);
      break;
    case 'ZAR_USD':
      return (value / usd_zar).toFixed(2);
      break;
    default:
      break;
  }
  return '';
}

function cc_set_btc(value) {
  document.getElementById('cc_btc').value = value;
  cc_onchange('cc_btc');
}

function cc_set_eth(value) {
  document.getElementById('cc_eth').value = value;
  cc_onchange('cc_eth');
}

function cc_set_usd(value) {
  document.getElementById('cc_usd').value = value;
  cc_onchange('cc_usd');
}

function cc_set_zar(value) {
  document.getElementById('cc_zar').value = value;
  cc_onchange('cc_zar');
}

function cc_onclick(e) {
  e.select();
}

function cc_onchange(id) {
  var value = document.getElementById(id).value;
  switch (id) {
    case "cc_btc":
      document.getElementById('cc_eth').value = convert_currency('BTC_ETH', value);
      document.getElementById('cc_usd').value = convert_currency('BTC_USD', value);
      document.getElementById('cc_zar').value = convert_currency('BTC_ZAR', value);
      break;
    case "cc_eth":
      document.getElementById('cc_btc').value = convert_currency('ETH_BTC', value);
      document.getElementById('cc_usd').value = convert_currency('ETH_USD', value);
      document.getElementById('cc_zar').value = convert_currency('ETH_ZAR', value);
      break;
    case "cc_usd":
      document.getElementById('cc_btc').value = convert_currency('USD_BTC', value);
      document.getElementById('cc_eth').value = convert_currency('USD_ETH', value);
      document.getElementById('cc_zar').value = convert_currency('USD_ZAR', value);
      break;
    case "cc_zar":
      document.getElementById('cc_btc').value = convert_currency('ZAR_BTC', value);
      document.getElementById('cc_eth').value = convert_currency('ZAR_ETH', value);
      document.getElementById('cc_usd').value = convert_currency('ZAR_USD', value);
      break;
    default:
      break;
  }
  //document.getElementById('cc_btc');
}
