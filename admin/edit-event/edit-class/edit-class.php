<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="/static/main.css">
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/static/scripts/initialization.php"; ?>
  <title>Admin | Edit Class</title>
</head>

<body>

  <header>
    <h1>Edit Class</h1>
    <nav>
      <a href="../"><button id="back-button">Back</button></a>
      <a href="/"><button id="home-button">Home</button></a>
    </nav>
  </header>

  <?php

    $classCode = $_POST['class-code'];

    if ($_POST['DELETE']) { //DELETE CLASS IF DELETE IS REQUESTED
      $query = "DELETE FROM classes WHERE class_code = '{$classCode}' AND (archived IS NULL OR archived = '');";
      $result = pg_query($db_connection, $query);
      if ($result) {
        echo "<h3 class='main-content-header'>Success</h3";
      } else {
        echo "<h3 class='main-content-header'>An error occurred.</h3><p class='main-content-header'>Please try again, ensure that all data is correctly formatted.</p>";
      }
      return;
    }


    if ($_POST['archive']) { //ARCHIVE CLASS IF REQUESTED
      $query = "UPDATE classes SET archived = 'TRUE' WHERE class_code = '{$classCode}';";
      $result = pg_query($db_connection, $query);
      if ($result) {
        echo "<h3 class='main-content-header'>Success</h3";
      } else {
        echo "<h3 class='main-content-header'>An error occurred.</h3><p class='main-content-header'>Please try again, ensure that all data is correctly formatted.</p>";
      }
      return;
    }




    //GET TODAYS' DATE AND ONLY MODIFY CLASSES AFTER TODAYS DATE
    $todaysDate = date('Y-m-d');


    //ARCHIVE ALL ROWS OF SELECTED CLASS SO THEY CAN BE REPLACED WITH THE NEW ONES
    $getClassIDsQuery = "SELECT id FROM classes WHERE class_code = '{$classCode}' AND date_of_class >= '{$todaysDate}' AND (archived IS NULL OR archived = '');";
    $oldClassIDSQLObject = pg_fetch_all(pg_query($db_connection, $getClassIDsQuery));
    if ($oldClassIDSQLObject) {
      foreach ($oldClassIDSQLObject as $row => $data) {
        pg_query($db_connection, "UPDATE classes SET archived = 'true' WHERE classes.id = {$data['id']};");
      }
    }



    //Get Date/Time array of class times after today's date.
    $dateData = getDateTimeArray($todaysDate, $_POST['end-date'], $_POST['every-other-week']);
    $dateTimeTriplets = $dateData[0];
    $all_weekdays_times = $dateData[1];



    //Convert other user selections to database ids
    $convertedData = convertSelectionsToDatabaseIDs($db_connection);





    //Check for double-booking
    $abort = checkForConflicts($dateTimeTriplets, $convertedData);
    if ($abort) {
      //RESTORE OLD CLASS DATA SINCE NO CHANGES ARE BEING MADE
      if ($oldClassIDSQLObject) {
        foreach ($oldClassIDSQLObject as $row => $data) {
          pg_query($db_connection, "UPDATE classes SET archived = null WHERE classes.id = {$data['id']};");
        }
      }
      //serialize post in case user wants to override
      $postString = base64_encode(serialize($_POST));

      echo "<h3 class='main-content-header'> No changes to the class have been made. It is safe to leave this page. To edit the class, please <button onclick='window.history.back();' style='width: 90pt;'>revert</button> your changes and try again.</h3>";

      echo "<h3 class='main-content-header'>Override:</h3><p class='main-content-header'><button form='override-form' type='submit' style='width: 110pt;'>OVERRIDE</button> conflicts if you are sure.</p>";
      echo "<form id='override-form' method='post' action='edit-class-override.php'><input name='override-post' value='{$postString}' style='visibility: hidden;'></form>";

      return;
    }



    //Convert class data to SQL-syntax arrays and escape the strings
    $SQLData = prepClassDataForSQL($convertedData);

    $horseIDList = $SQLData[0];
    $clientIDList = $SQLData[1];
    $staffJSON = $SQLData[2];
    $volunteerJSON = $SQLData[3];
    $tackList = $SQLData[4];
    $padList = $SQLData[5];
    $tackNotes = $SQLData[6];
    $clientEquipmentNotes = $SQLData[7];
    $displayTitle = $SQLData[8];




    //Create SQL query
    $query = "INSERT INTO classes (class_code, class_type, display_title, date_of_class, start_time, end_time, all_weekdays_times, arena, horses, tacks, tack_notes, client_equipment_notes, pads, clients, attendance, staff, volunteers) VALUES";
    foreach ($dateTimeTriplets as $date => $timeArray) {
      $query = $query . "('{$classCode}', '{$_POST['class-type']}', '{$displayTitle}', '{$date}', '{$timeArray[0]}', '{$timeArray[1]}', '$all_weekdays_times', '{$_POST['arena']}', '{$horseIDList}', '{$tackList}', '{$tackNotes}', '{$clientEquipmentNotes}', '{$padList}', '{$clientIDList}', '{$clientIDList}', '{$staffJSON}', '{$volunteerJSON}'),";
    }

    $query = chop($query, ",") . ";";


    //Modify database
    $result = pg_query($db_connection, $query);
    if ($result) {
      //DELETE OLD CLASS DATA TO BE REPLACED WITH NEW DATA
      if ($oldClassIDSQLObject) {
        foreach ($oldClassIDSQLObject as $row => $data) {
          pg_query($db_connection, "DELETE FROM classes WHERE classes.id = {$data['id']};");
        }
      }
      echo "<h3 class='main-content-header'>Success</h3";
    } else {
      //UNARCHIVE OLD CLASS DATA IF ERROR OCCURRED
      if ($oldClassIDSQLObject) {
        foreach ($oldClassIDSQLObject as $row => $data) {
          pg_query($db_connection, "UPDATE classes SET archived = null WHERE classes.id = {$data['id']};");
        }
      }
      echo "<h3 class='main-content-header'>An error occurred.</h3><p class='main-content-header'>Please try again, ensure that all data is correctly formatted.</p>";
    }
  ?>



</body>

</html>
