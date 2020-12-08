<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link href="https://fonts.googleapis.com/css?family=Nunito:700&display=swap" rel="stylesheet">
  <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/static/TimeSlider.js"></script>
  <link rel="stylesheet" href="/static/main.css">
  <link rel="stylesheet" href="/static/added.css">
  <?php include $_SERVER['DOCUMENT_ROOT']."/static/scripts/initialization.php"; ?> 
  <title>Full time</title>
 
</head>

<body>

  <header>
    <h1>Full-time</h1>
    <nav> <a href="../"><button id="back-button">Back</button></a>
      <a href="/"><button id="home-button">Home</button></a>
    </nav>
  </header>
  
  <div class="top_grid">
    <form class="form-inline" action="full-time.php" autocomplete="off">
      <label for="Name" >Name:</label>
      <input type="Name" id="Name" placeholder="Enter name" name="name">
      <label>Date:</label>
      <input type="date" name="date-of-hours" value="<?php echo date('Y-m-d'); ?>" required>

    </form>
  </div>

  <div id="time-range">
    <p>Time Range:
      <span class="slider-time">9:00 AM</span> -
      <span class="slider-time2">5:00 PM</span>
    </p>
    <div class="sliders_step1">
        <div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"aria-disabled="false">
          <div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 37.5%; width: 33.3333%;"></div>
          <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.5%;">
            ::after
          </a>
          <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.5%;">
            ::after
          </a>
        </div>
    </div>
  </div>



</body>

</html>