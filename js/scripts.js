function toggleDarkMode(e) {
  document.querySelector("body").classList.toggle("dark");
}

function open_modal_profile() {
  alert("User profiles are still under construction... Stay tuned!");
}


/*
el = DOM node with data-radiogroup of a unique value.
 */
function radio_button_select(el) {
  document.querySelector("[data-radiogroup='" + el.dataset.radiogroup + "'].selected").classList.remove("selected");
  el.classList.add("selected");
}
