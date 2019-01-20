<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="/static/main.css">
  <?php include $_SERVER['DOCUMENT_ROOT']."/static/scripts/initialization.php"; ?>
  <title>View Volunteer Hours</title>
</head>

<body>

  <header>
    <h1>Volunteer Hours</h1>
    <nav> <a href="../"><button id="back-button">Back</button></a>
      <a href="/"><button id="home-button">Home</button></a>
    </nav>
  </header>

  <?php

    $volunteerID = pg_fetch_array(pg_query($db_connection, "SELECT id FROM workers WHERE name = '{$_POST['volunteer']}' AND (archived IS NULL OR archived = '');"), 0, 1)['id'];

    $query = <<<EOT
    SELECT * FROM volunteer_hours
    WHERE volunteer = '{$volunteerID}' AND
    '{$_POST['start-date']}' <= date_of_hours AND
    '{$_POST['end-date']}' >= date_of_hours
    ;
EOT;

    $hourData = pg_fetch_array(pg_query($db_connection, $query));
    if (!$hourData) {
      echo "<h3 class='main-content-header>No data.</h3><p class='main-content-header'>There are no hour entries for this time period.</p>";
      return;
    }

    var_dump($hourData);


  ?>


</body>

</html>
