function showTooltip(title, text) {
  document.querySelector('body').classList.add('blur');
  document.querySelector('.tooltip-background-blur').classList.add('show');
  document.querySelector('.tooltip').classList.add('show');
  document.querySelector('.tooltip-overlay').classList.add('show');
  document.querySelector('.tooltip-title').innerHTML = title;
  document.querySelector('.tooltip-text').innerHTML = text;
}

function hideTooltip() {
  document.querySelector('body').classList.remove('blur');
  document.querySelector('.tooltip-background-blur').classList.remove('show');
  document.querySelector('.tooltip').classList.remove('show');
  document.querySelector('.tooltip-overlay').classList.remove('show');
}
