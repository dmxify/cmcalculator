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

Date.prototype.monthDays = function() {
  var d = new Date(this.getFullYear(), this.getMonth() + 1, 0);
  return d.getDate();
}

getDaysThisMonth = function() {
  var today = new Date();
  return today.monthDays();
}

labelArray_days_this_month = function() {
  const daysThisMonth = getDaysThisMonth();
  var labelArray = [];
  for (var i = 0; i < daysThisMonth; i++) {
    labelArray.push(i);
  }
  return labelArray;
}

defaultData_days_this_month = function() {
  const daysThisMonth = getDaysThisMonth();
  var defaultData = [];
  for (var i = 0; i < daysThisMonth; i++) {
    defaultData.push(0);
  }
  return defaultData;
}

// function html2image_download(elementId, outputFilename) {
//   var options = {
//     scrollY: -50,
//     scrollX: 0
//   };
//   html2canvas(document.getElementById(elementId), options).then(function(canvas) {
//     var a = document.createElement('a');
//     a.href = canvas.toDataURL("image/png");
//     a.download = outputFilename;
//     a.target = '_blank';
//     document.body.appendChild(a);
//     a.click();
//     document.body.removeChild(a);
//   });
// }
