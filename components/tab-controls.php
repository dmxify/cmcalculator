<?php
?>

<style>

.tab-selected {
  display: flex !important;
}

.tab-control-area {
  border-top-left-radius: 7px;
  border-top-right-radius: 7px;
  display:flex;
  flex-wrap: wrap;
  justify-content: center;


}
.tab-control-area .tab-control {
  display:flex;
  padding: 5px;
  align-content: space-around;
  align-items: center;
  flex-wrap: nowrap;
  justify-content: center;
  margin:2px 5px;
  border-radius:3px;
  padding: 6px;
  margin-left:1px;
  border: 1px solid #009fad;
  background-color: #c6eff6;
  color: #000000;
  cursor:pointer;
  user-select: none;
  font-weight:bold;
}

.dark .tab-control-area .tab-control {
  background-color: #a7d4dc;
    color: #262626;
}

.tab-control-area .tab-control.selected {
  filter:brightness(1.15);
  /* font-weight:bold; */
}
.tab-control-area .tab-control:not(.selected):hover {
  filter:brightness(1.1);
}

.tab-control-area .tab-control .icon {
  margin-right:7px;
}

</style>
<div style="display:flex; justify-content:center; margin:4px;">
  <div class="tab-control-area">
    <div class="tab-control" onclick="tab_onClick(this)" data-tab="module_dashboard">
      <div class="icon icon-small icon-left icon-tachometer"></div>
      Dashboard
    </div>
    <div class="tab-control" onclick="tab_onClick(this)" data-tab="module_transactions">
      <div class="icon icon-small icon-left icon-money"></div>
      Transactions
    </div>
    <div class="tab-control" onclick="tab_onClick(this)" data-tab="module_strategy-planner">
        <div class="icon icon-small icon-left icon-flask"></div>
      Strategy Planner
    </div>
    <div class="tab-control" onclick="tab_onClick(this)" data-tab="module_alerts">
        <div class="icon icon-small icon-left icon-shield_warning"></div>
      Alerts
    </div>
    <div class="tab-control tag-new selected" onclick="tab_onClick(this)" data-tab="module_calculators">
      <div class="icon icon-small icon-left icon-layout_window"></div>
      Calculators
    </div>
  </div>
</div>

<script>

function tab_onClick(el){
  // deselect previously selected tab control.
  var selectedTabControl = document.querySelector(".tab-control-area .tab-control.selected");
  if(selectedTabControl){
    selectedTabControl.classList.remove("selected");
  }

  // hide previously selected tab:
  var selectedTab = document.querySelector(".module.tab-selected");
  if(selectedTab){
    selectedTab.classList.remove("tab-selected");
  }


  el.classList.add('selected');
  document.getElementById(el.dataset.tab).classList.add("tab-selected");
}

  // (function() {
  //   module_dashboard
  // })();

</script>
