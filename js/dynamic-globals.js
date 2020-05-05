function getGlobal(name) {
  if (typeof window.dynamicGlobals === 'undefined') {
    window.dynamicGlobals = {};
  }
  if (typeof window.dynamicGlobals[name] === 'undefined') {
    return null;
  }
  return window.dynamicGlobals[name];
}

function setGlobal(name, value) {
  if (typeof window.dynamicGlobals === 'undefined') {
    window.dynamicGlobals = {};
  }
  window.dynamicGlobals[name] = value;
  dynamicallyUpdate(); // update DOM with new global values
}

function dynamicallyUpdate() {
  // 1: iterate all elements with data attribute "data-dynamicglobal"
  document.querySelectorAll('[data-dynamicglobal-name]').forEach((el, i) => {
    // get global variable
    var name = el.dataset.dynamicglobalName;
    var val = getGlobal(name);

    // if hidden parent must be shown
    if (el.dataset.dynamicglobalAction) {
      var action = el.dataset.dynamicglobalAction
      switch (action) {
        case "show-on-global-update":
          el.classList.remove("hidden");
        case "show-parent-on-global-update":
          el.parentElement.classList.remove("hidden");
        case "show-this-and-parent-on-global-update":
          el.classList.remove("hidden");
          el.parentElement.classList.remove("hidden");
          break;
        default:

      }
    }

    if (el.dataset.dynamicglobalSet) {
      // apply variable value to element
      var set = el.dataset.dynamicglobalSet;
      switch (set) {
        case "innerHTML":
          el.innerHTML = val;
        case "input":
          el.value = val;
        case "select":
          //el.value = val;
          break;
        default:
      }
    }
  });



  // 1: check if value changed.
  // 2: check
}
