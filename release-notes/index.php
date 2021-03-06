<?php
include("../version.php");
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="CM Calculator - Compound interest calculator & strategy planner.">

    <link rel="icon" type="image/png" href="btc.png">
    <title>CM Calculator Release Notes - v<?php echo get_version(); ?></title>
  </head>

  <body>
    <h1 style="margin-left: 10px;">CM Calculator - Release Notes</h1>
    <div style="background-color: #e3e259; border: 2px solid #909090; padding: 15px; font-size: 12pt; flex: 1 1 auto;
    margin: 10px; box-shadow: 0 1px 1px 0 rgba(60, 64, 67, 0.08), 0 1px 3px 1px rgba(60, 64, 67, 0.16);
    border-radius: 3px;">
          <h2>Free Alpha Version</h2>
          <i>
            <b>All premium features are free in the alpha development phase!</b>
          </i>
          <br />
            After the alpha phase (once all features are finished), premium features will be available through monthly/annual packages

          <br />
          <br />

          <b>Forever free features:</b>
          <ul>
            <li><b>Single Investment Calculator</b> - Quickly analyse the yield of a single investment.</li>
            <li><b>Basic Dashboard</b> - overview of your investment portfolio, including limited analysis</li>
            <li><b>Transactions</b> - Keep a record of all your deposits, reinvestments and withdrawals</li>
            <li>Telegram community - Help & support</li>
          </ul>
          <br />
          <b>Premium features:</b> (free during alpha phase)
          <ul>
              <li><b>Pro Dashboard</b> - overview of your portfolio, with intelligent strategic analysis </li>
              <li><b>Blockchain explorer integration</b> - track wallets, and link TXid's to your deposits & withdrawals for easy reference.</li>
              <li><b>Strategy Planner</b> - Use projections from your <b>Transactions</b> & make bespoke strategies to achieve your goals.</li>
              <li><b>Alerts</b> - Never miss out on strategic moments! Alert notifications will help you stick to your <b>Strategy</b></li>
              <li><b>Reports</b> - Bespoke analysis available to you at all times. Optionally delivered via email.</li>
          </ul>
    </div>
    <div class="releases">
      <div class="release latest">
        <div class="version">0.2.2a<div style="font-size: 10pt;font-style: normal;">- latest</div></div>
        <div class="items features">
          <div class="title">New Features</div>
          <div class="item">Added ETH to calculator and currency conversions</div>
          <div class="item">Added XRP to currency conversions</div>
        </div>
      </div>
        <div class="release">
          <div class="version">0.2.1a<div style="font-size: 10pt;font-style: normal;"></div></div>
          <div class="items features">
            <div class="title">New Features</div>
            <div class="item">Single Investment Calculator: Download results to image (user requested feature)</div>
          </div>
        </div>
      <div class="release">
        <div class="version">0.2.0a<div style="font-size: 10pt;font-style: normal;"></div></div>
        <div class="items features">
          <div class="title">New Features</div>
          <div class="item">Save as PNG image</div>
          <div class="item">Module: "Dashboard"</div>
          <div class="item">Module: "Calculators"</div>
        </div>
      </div>

      <div class="release">
        <div class="version">0.1.3a<div style="font-size: 10pt;font-style: normal;"></div></div>
        <div class="items bugfixes">
          <div class="title">Bug Fixes</div>
          <div class="item">Registration form: not able to click "Register"</div>
        </div>
      </div>

      <div class="release">
        <div class="version">0.1.2a</div>
        <div class="items features">
          <div class="title">New Features</div>
          <div class="item">Forgot Password</div>
        </div>
      </div>

      <div class="release">
        <div class="version">0.1.1a</div>
        <div class="items features">
          <div class="title">New Features</div>
          <div class="item">Release Notes</div>
        </div>
        <div class="items updates">
          <div class="title">Changes</div>
          <div class="item">Background color: slightly darker in default/light mode</div>
          <div class="item">Donate button: copies BTC wallet to clipboard</div>
        </div>
      </div>

      <div class="release">
        <div class="version">0.1.0a</div>
        <div class="items bugfixes">
          <div class="title">Bug Fixes</div>
          <div class="item">Files are no longer cached after updates</div>
        </div>
      </div>
    </div>
  </body>
  <style>
  * {
    font-family: "Calibri", "Times New Roman";
  }
  body {
    background-color:#cfcfcf;
  }

  .release:not(:first-of-type) {
    filter:saturate(0.25);
  }

  .release:hover{
    filter:none;
  }

  .release {
    display:flex;
    background-color:#fcf5cf;
    margin: 10px;
    padding: 15px;
    box-shadow: 0 1px 1px 0 rgba(60, 64, 67, 0.08), 0 1px 3px 1px rgba(60, 64, 67, 0.16);
    border-radius: 8px;
    border: 1px solid #eeeeee;
  }

  .latest.release {
    border: 2px solid hsl(118, 80%, 43%);
    box-shadow: 0 1px 1px 0 rgba(43, 255, 0, 0.08), 0 1px 3px 3px rgba(43, 255, 0, 0.16);
  }

  .version {
    font-size: 20pt;
    margin: 6px;
    font-style: italic;
  }
  .latest .version {
    font-weight:bold;
    color:hsl(118, 80%, 43%);
  }

  .items {
    font-size: 14pt;
    margin: 10px;
    padding: 0 0 2px 0;
    border-radius: 3px;
    box-shadow: 0 1px 1px 0 rgba(60, 64, 67, 0.08), 0 1px 3px 1px rgba(60, 64, 67, 0.16);

  }
  .items.features {
    background-color: hsl(120, 71%, 92%);
  }
  .items.features .title {
    background-color: hsl(120, 71%, 72%);
  }
  .items.features .title:after{
    content:" 🎁";
    font-size:10pt;
  }
  .items.updates {
      background-color: hsl(169, 71%, 92%);
  }
  .items.updates .title {
    background-color: hsl(169, 71%, 72%)
  }
  .items.updates .title:after{
    content:" 🔨";
    font-size:10pt;
  }
  .items.bugfixes {
  background-color: hsl(36, 71%, 92%);
  }
  .items.bugfixes .title {
    background-color: hsl(36, 71%, 72%);
  }
  .items.bugfixes .title:after{
    content:" 🐞";
    font-size:10pt;
  }

  .item {
    font-size: 11pt;
    margin: 5px 10px;
  }
  .item::before{
    content:"• "
  }


  .title {
    font-size:12pt;
    font-weight:bold;
    display:block;
    padding:5px 15px;
    border-radius:5px;
  }

  </style>
</html>
