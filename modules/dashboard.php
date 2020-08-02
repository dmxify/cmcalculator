<?php
$is_admin = false;
if (isset($_SESSION['user']) && isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'] == 1) {
    $is_admin = true;
}

?>


<style>

.dashboard-controls {
  user-select: none;
  zoom:0.8;
}

.dashboard-controls-header {
  display: inline-block;
  padding: 5px;
  margin-right: 15px;
  border-radius: 10px;
  width: 120px;
  text-align:center;
}

.dark .dashboard-controls hr {
  border-color: #6b6b6b;
}

.chart-container {
  position: relative;
  /* height: 45vh; */
  min-height: 200px;
  width: 40vw;
}

.chart-big-number .value {
  font-size:160px;
  text-align:center;
  margin-top:20px;
}

</style>

<script>

window.dashboard = {
  charts: [{
      elementId:"chart_users_total-count",
      resource: "users_total-count",
      chartType: "number",
      enabled: true
    },{
      elementId:"chart_users_new-this-week",
      resource: "users_new-this-week",
      title: "New Users This Week",
      chartType: "bar",
      legend:{
        display: false
      },
      labels: ['6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday', 'Today'],
      map: {
        label:'New users',
        keyField:'days',
        valField:'count'
      },
      defaultData: [0,0,0,0,0,0,0],
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgba(75, 192, 192, 1)',
      enabled: true
    },{
      elementId:"chart_users_new-last-week",
      resource: "users_new-last-week",
      title: "New Users Last Week",
      chartType: "bar",
      legend:{
        display: false
      },
      labels: ['13 days ago', '12 days ago', '11 days ago', '10 days ago', '9 days ago', '8 days ago', '7 days ago'],
      map: {
        label:'New users',
        keyField:'days',
        valField:'count'
      },
      defaultData: [0,0,0,0,0,0,0],
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgba(75, 192, 192, 1)',
      enabled: true
    },{
      elementId:"chart_users_new-this-month",
      resource: "users_new-this-month",
      chartType: "number",
      enabled: false
    },{
      elementId:"chart_users_new-last-month",
      resource: "users_new-last-month",
      chartType: "number",
      enabled: false
    },{
      elementId:"chart_users_verified-vs-unverified",
      resource: "users_verified-vs-unverified",
      title: "Account Verification",
      chartType: "doughnut",
      labels: ['Verified','Unverified'],
      map: {
        keyField:'is_email_verified',
        valField:'count'
      },
      enabled: true
    },{
      elementId:"chart_users_free-vs-premium",
      resource: "users_free-vs-premium",
      title: "Account Types",
      chartType: "doughnut",
      labels: ['Premium','Free'],
      map: {
        keyField:'is_premium',
        valField:'count'
      },
      enabled: true
    },{
      elementId:"chart_users_per-country",
      resource: "users_per-country",
      chartType: "pie",
      enabled: false
    },{
      elementId:"chart_users_logins-per-day",
      resource: "users_logins-per-day",
      chartType: "bar",
      enabled: false
    },{
      elementId:"chart_users_count-vs-time-since-login",
      resource: "users_count-vs-time-since-login",
      chartType: "bar",
      enabled: false
    }
  ]
};

function generateArray(length,val){
  var array = [];
  for (var i = 0; i < length; i++) {
    array.push(val);
  }
  return array;
}

</script>
<!-- Begin: module_dashboard -->
<div class="module" id="module_dashboard" style="display:none;">
  <div class="container chart-container">
    <div class="title center">
      <div class="icon-small icon-left icon-tachometer" style="margin-right:7px;margin-bottom:3px;"></div>
      <div class="title-text">
        Dashboard
      </div>
      <div class="tooltip-trigger icon icon-small icon-right icon-info_sign" title="Click for info" onclick="showTooltip('Dashboard','An intelligent overview of your account')">
      </div>
    </div>
<?php if ($is_admin) { ?>
    <div class="dashboard-controls">
      <br />
      <div>
        <label for="dashboard-controls">
          Registered Users:
        </label>
        <div id="dashboard-controls" class="dashboard-controls-header">
          Enable Chart
        </div>
      </div>
      <hr />
      <label for="chbx_chart_users_total-count">Total</label>
      <input id="chbx_chart_users_total-count" data-chart="chart_users_total-count" checked="checked" type="checkbox" onchange="chbx_dashboard_onchange(this)"/>

      <hr />

      <label for="chbx_chart_users_new-this-week">New this week</label>
      <input id="chbx_chart_users_new-this-week" data-chart="chart_users_new-this-week" checked="checked" type="checkbox" onchange="chbx_dashboard_onchange(this)"/>
      <br />

      <label for="chbx_chart_users_new-last-week">New last week</label>
      <input id="chbx_chart_users_new-last-week" data-chart="chart_users_new-last-week" checked="checked" type="checkbox" onchange="chbx_dashboard_onchange(this)"/>
      <br />

      <label for="chbx_chart_users_new-this-month">New this month</label>
      <input id="chbx_chart_users_new-this-month" data-chart="chart_users_new-this-month" type="checkbox" onchange="chbx_dashboard_onchange(this)"/>
      <br />

      <label for="chbx_chart_users_new-last-month">New last month</label>
      <input id="chbx_chart_users_new-last-month" data-chart="chart_users_new-last-month" type="checkbox" onchange="chbx_dashboard_onchange(this)"/>

      <hr />
      <label for="chbx_chart_users_verified-vs-unverified">Verified vs unverified</label>
      <input id="chbx_chart_users_verified-vs-unverified" data-chart="chart_users_verified-vs-unverified" checked="checked" type="checkbox" onchange="chbx_dashboard_onchange(this)"/>
      <br />

      <label for="chbx_chart_users_free-vs-premium">Free vs Premium</label>
      <input id="chbx_chart_users_free-vs-premium" data-chart="chart_users_free-vs-premium" type="checkbox" checked="checked" onchange="chbx_dashboard_onchange(this)"/>
      <br />

      <label for="chbx_chart_users_per-country">Per country</label>
      <input id="chbx_chart_users_per-country" data-chart="chart_users_per-country" type="checkbox" onchange="chbx_dashboard_onchange(this)"/>

      <hr />

      <label for="chbx_chart_users_logins-per-day">Logins per day</label>
      <input id="chbx_chart_users_logins-per-day" data-chart="chart_users_logins-per-day" type="checkbox" onchange="chbx_dashboard_onchange(this)"/>
      <br />

      <label for="chbx_chart_users_count-vs-time-since-login">Time since last login</label>
      <input id="chbx_chart_users_count-vs-time-since-login" data-chart="chart_users_count-vs-time-since-login" type="checkbox" onchange="chbx_dashboard_onchange(this)"/>
    </div>

<?php } else { ?>

You need to be logged in to view your dashboard

<?php } ?>

  </div>
    <div class="chart-container container hidden" data-chart="chart_users_total-count">
      <div class="chart-big-number" id="chart_users_total-count">
        <div class="title center">
          Total Registered Users:
        </div>
        <div data-chart-value="chart_users_total-count" class="value">
        </div>
      </div>
    </div>

    <div class="chart-container container hidden" data-chart="chart_users_new-this-week">
      <canvas id="chart_users_new-this-week"></canvas>
    </div>
    <div class="chart-container container hidden" data-chart="chart_users_new-last-week">
      <canvas id="chart_users_new-last-week"></canvas>
    </div>

    <div class="chart-container container hidden" data-chart="chart_users_free-vs-premium">
      <canvas id="chart_users_free-vs-premium"></canvas>
    </div>
    <div class="chart-container container hidden" data-chart="chart_users_verified-vs-unverified">
      <canvas id="chart_users_verified-vs-unverified"></canvas>
    </div>
    <div class="chart-container container hidden" data-chart="chart_users_perday">
      <canvas id="chart_users_perday"></canvas>
    </div>

</div>
<!-- End: module_dashboard -->

<script>

(function() {
  charts_init();

})();

async function charts_init(){
  // loop charts, if chart is enabled, fetch data
  for (var i = 0; i < window.dashboard.charts.length; i++) {
    let chart = window.dashboard.charts[i];
    if(chart.enabled){
      // get data
      var json = await api_fetch_json(chart.resource);
      // data is fetched. what do we do with it?
      if(json.error){
        continue;
      }


      // get chart type and format data accordingly.
      if(chart.hasOwnProperty('chartType')){
        var chartType = chart.chartType;
        switch(chartType){
          case 'number':
            // update value
            document.querySelector("[data-chart-value='" + chart.elementId + "']").innerHTML = json[0].count;
          break;
          case 'doughnut':
            chart_doughnut_init(chart, json);
            // chart_loadData(chart, json);
          break;
          case 'pie':
            chart_pie_init(chart, json);
            // chart_loadData(chart, json);
          break;
          case 'bar':
            chart_bar_init(chart, json);
          break;
          default:
          break;
        } //end switch

        //render container
        document.querySelector(".container[data-chart='" + chart.elementId + "']").classList.toggle("hidden");
      } //end if
    } //end if
  } //end for
} //end function



async function chart_render(chart){
}

function chart_loadData(chart, data){
  console.log(chart);
  console.log(data);
}

function chart_doughnut_init(chart, json){
  var data = [];
  for (var i = 0; i < json.length; i++) {
    data.push(json[i][chart.map.valField])
  }

  chart.cjs = new Chart(chart.elementId, {
    type: chart.chartType,
    data: {
      labels: chart.labels,
      datasets: [{
          data: data,
          backgroundColor: [
              'rgba(75, 192, 192, 0.2)',
              'rgba(255, 99, 132, 0.2)'
          ],
          borderColor: [
              'rgba(75, 192, 192, 1)',
              'rgba(255, 99, 132, 1)'
          ],
          borderWidth: 1
      }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
					position: 'top',
				},
        title: {
          display: true,
          text: chart.title
        },
				animation: {
					animateScale: true,
					animateRotate: true
				},
        circumference: Math.PI,
        rotation:-Math.PI
    }
  });
}


function chart_bar_init(chart, json){
  var data = [];
  if(chart.hasOwnProperty('defaultData')){
    data = chart.defaultData;
  }

  for (var i = 0; i < json.length; i++) {
    data[json[i][chart.map.keyField]] = json[i][chart.map.valField];
  }

  chart.cjs = new Chart(chart.elementId, {
    type: chart.chartType,
    data: {
      labels: chart.labels,
      datasets: [{
          label: chart.map.label,
          data: data,
          backgroundColor: generateArray(chart.labels.length,chart.backgroundColor),
          borderColor: generateArray(chart.labels.length,chart.borderColor),
          borderWidth: 1
      }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
					position: 'top',
				},
        title: {
          display: true,
          text: chart.title
        },
        legend: chart.legend?chart.legend:{},
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function(value) {if (value % 1 === 0) {return value;}}
            }
          }]
        },
				animation: {
					animateScale: true,
					animateRotate: true
				}
    }
  });
}

function chbx_dashboard_onchange(el){
  // if()
  document.querySelector(".container[data-chart='" + el.dataset.chart + "']").classList.toggle("hidden");
  console.log(el.checked);
}
</script>
