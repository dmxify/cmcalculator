<?php
$is_admin = false;
if (isset($_SESSION['user']) && isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'] == 1){
  $is_admin = true;
}

?>


<style>

.chart-container {
  position: relative;
  /* height: 45vh; */
  min-height: 200px;
  width: 45vw;
}

</style>

<!-- Begin: module_dashboard -->
<div class="module tab-selected" id="module_dashboard" style="display:none;">
  <div class="container">
    <div class="title center">
      <div class="icon-small icon-left icon-tachometer" style="margin-right:7px;margin-bottom:3px;"></div>
      <div class="title-text">
        Dashboard
      </div>
      <div class="tooltip-trigger icon icon-small icon-right icon-info_sign" title="Click for info" onclick="showTooltip('Dashboard','An intelligent overview of your account')">
      </div>
    </div>
<?php if($is_admin) { ?>
      Registered Users:<br />

      <br />------------------------------------<br /><br />
      Total [x]<br />
      Free vs Premium [x]<br />
      New this week [x]<br />
      New this month [x]<br />
      Country Denomination [x]<br />
      Logins this week [x]<br />
      Logins this month [x]<br />
      Time since last login [x]<br />
      <br />------------------------------------<br /><br />

<?php } else { ?>

You need to be logged in to view your dashboard

<?php } ?>

  </div>



  <div class="chart-container container">
    <canvas id="chart_admin_users_freepremium"></canvas>
  </div>

  <div class="chart-container container">
    <canvas id="chart_admin_users_perday"></canvas>
  </div>

</div>
<!-- End: module_dashboard -->

<script>
var chart_admin_users_freepremium = new Chart('chart_admin_users_freepremium', {
    type: 'doughnut',
    data: {
        labels: ['Premium','Free'],
        datasets: [{
            data: [12, 19],
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
          text: 'Account Types'
        },
				animation: {
					animateScale: true,
					animateRotate: true
				},
        circumference: Math.PI,
        rotation:-Math.PI
    }
});
</script>

<script>

var data_usersPerDay = {
			labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
			datasets: [{
				label: 'Premium',
				backgroundColor: 'rgba(75, 192, 192, 0.2)',
				borderColor: 'rgba(75, 192, 192, 1)',
				borderWidth: 1,
				data: [0,1,2,4,5,7,8]
			},{
				label: 'Free',
				backgroundColor: 'rgba(255, 99, 132, 0.2)',
				borderColor: 'rgba(255, 99, 132, 1)',
				borderWidth: 1,
				data: [5,6,8,10,15,21,23]
			}]
		};

var chart_admin_users_perday = new Chart('chart_admin_users_perday', {
  type: 'bar',
  data:data_usersPerDay,
  options: {
    responsive: true,
    maintainAspectRatio: false,
    title: {
      display: true,
      text: 'Active Accounts'
    },
    animation: {
      animateScale: true,
      animateRotate: true
    }
  }
});

window.dashboard = {
  charts: [{
    id:"chart_users-total-count",
    chartType:"number"
  },{
    id:"chart_users_new-this-week",
    chartType:"number"
  },{
    id:"chart_users_new-last-week",
    chartType:"number"
  },{
    id:"chart_users_new-this-month",
    chartType:"number"
  },{
    id:"chart_users_free-vs-premium",
    chartType:"pie"
  },{
    id:"chart_users_per-country",
    chartType:"pie"
  },{
    id:"chart_users_new-last-month",
    chartType:"number"
  },{
    id:"chart_user_logins-per-day",
    chartType:"bar"
  },{
    id:"chart_num-users_time-since-login",
    chartType:"bar"
  }
]
};
</script>
