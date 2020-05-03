/*
  This is a controller for all widgets within the web app.
  For now we have to manually add a widget into the JSON below, e.g:

    instance: [
      {
        id:'currency-converter',
        elements: 'currency-converter',
        id:'currency-converter'
      }
    ]

 */


window.widgets = {
  instances: {
    'currency-converter': {
      focusElement: 'cc_btc',
      elements: [{
        id: 'cc_btc',
        type: 'input',
        default: ''
      }, {
        id: 'cc_usd',
        type: 'input',
        default: ''
      }, {
        id: 'cc_zar',
        type: 'input',
        default: ''
      }]
    }
  },
  reset: function(widgetInstanceName) {
    var widget = this.instances[widgetInstanceName];
    widget.elements.forEach((item, i) => {
      switch (item.type) {
        case "input":
          document.getElementById(item.id).value = item.default;
          break;
        case "innerHTML":
          document.getElementById(item.id).innerHTML = item.default;
          break;
        default:
          break;
      }
      if (widget.focusElement) {
        document.getElementById(widget.focusElement).focus();
      }


    });

  }

};
