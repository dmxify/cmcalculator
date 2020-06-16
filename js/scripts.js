function toggleDarkMode(e) {
  document.querySelector("body").classList.toggle("dark");
}

function change_layout() {
  alert("This is a future feature! Stay tuned...");
}

function open_modal_login() {
  document.getElementById('id01').style.display = 'block'
}

function logout() {
  alert("This is a future feature! Stay tuned...");
}

/*
el = DOM node with data-radiogroup of a unique value.
 */
function radio_button_select(el) {
  document.querySelector("[data-radiogroup='" + el.dataset.radiogroup + "'].selected").classList.remove("selected");
  el.classList.add("selected");
}
