<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="/static/main.css">
<link href="https://fonts.googleapis.com/css?family=Nunito:700&display=swap" rel="stylesheet">
  <?php include $_SERVER['DOCUMENT_ROOT']."/static/scripts/initialization.php"; ?>
  <title>Admin | System Emails</title>
</head>

<body>

  <header>
    <h1>Configure System Emails</h1>
    <nav> <a href="../"><button id="back-button">Back</button></a>
      <a href="/"><button id="home-button">Home</button></a>
    </nav>
  </header>

  <div class="main-content-div">

    <?php
      $volunteerCoordinatorEmail = pg_escape_string($_POST['volunteer_coordinator_email']);
      $staffCoordinatorEmail = pg_escape_string($_POST['staff_coordinator_email']);

      $volunteerCoordinatorEmailQuery = "UPDATE misc_data SET value = '{$volunteerCoordinatorEmail}' WHERE key LIKE 'volunteer_coordinator_email';";
      $staffCoordinatorEmailQuery = "UPDATE misc_data SET value = '{$staffCoordinatorEmail}' WHERE key LIKE 'staff_coordinator_email';";

      $result1 = pg_query($db_connection, $volunteerCoordinatorEmailQuery);
      $result2 = pg_query($db_connection, $staffCoordinatorEmailQuery);

      if ($result1 && $result2) {
        echo "<h3 class='main-content-header'>Success</h3>";
      } else {
        echo "<h3 class='main-content-header'>An error occurred.</h3><p class='main-content-header'>Please try again, ensure that all data is correctly formatted.</p>";
      }

    ?>



  </div>


</body>

</html>
