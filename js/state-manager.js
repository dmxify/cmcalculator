var states = {
  modules: {
    "ledger": {},
    "currencyConverter": {},
    "exchangeRates": {},
    "calculator": {
      currency: "BTC",
      principal: {
        value: ""
      },
      interestRate: {
        value: "",
        editable: false
      },
      contractLength: {
        value: "",
        editable: false,
        makeEditable: function() {
          document.getElementById
        }
      }
    }
  }
};

var stateListeners = {
  calculator: {
    interestRate: {
      editable: (xxx) => {
        alert(xxx);
      }
    }
  }
};

window.stateManager = {
  states: states,
  stateListeners: stateListeners,
  changeState: function(module, item, property, value) {
    window.stateManager.states.modules[module][item][property] = value;
    //window.stateListeners[module][item][property](value);

  },
  initModule: function(module) {
    console.log(window.states.modules[module]);
  },
  initAll: function() {
    for (var item in window.states.modules) {
      console.log(item);
    }
  }
};
