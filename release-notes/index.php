<?php
include("../version.php");
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="CM Calculator - The unofficial compound interest calculator, ledger & planner for Continental Miners.">

    <link rel="icon" type="image/png" href="btc.png">
    <title>CM Calculator Release Notes - v<?php echo get_version(); ?></title>
  </head>

  <body>
    <h1 style="margin-left: 10px;">CM Calculator - Release Notes</h1>
    <div class="releases">
      <div class="release latest">
        <div class="version">0.1.3<div style="font-size: 10pt;font-style: normal;">- latest</div></div>
        <div class="items bugfixes">
          <div class="title">Bug Fixes</div>
          <div class="item">Registration form: not able to click "Register"</div>
        </div>
      </div>

      <div class="release">
        <div class="version">0.1.2</div>
        <div class="items features">
          <div class="title">New Features</div>
          <div class="item">Forgot Password</div>
        </div>
      </div>

      <div class="release">
        <div class="version">0.1.1</div>
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
        <div class="version">0.1.0</div>
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
    background-color: hsl(169, 71%, 92%);
  }
  .items.features .title {
    background-color: hsl(169, 71%, 72%)
  }
  .items.features .title:after{
    content:" 🎁";
    font-size:10pt;
  }
  .items.updates {
    background-color: hsl(36, 71%, 92%);
  }
  .items.updates .title {
    background-color: hsl(36, 71%, 72%);
  }
  .items.updates .title:after{
    content:" 🔨";
    font-size:10pt;
  }
  .items.bugfixes {
    background-color: hsl(120, 71%, 92%);
  }
  .items.bugfixes .title {
    background-color: hsl(120, 71%, 72%);
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
