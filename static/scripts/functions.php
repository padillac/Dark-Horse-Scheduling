<?php

// Goes at the start of every class create/edit page to create an array of class periods from user input
function getDateTimeArray($startDate, $endDate, $everyOther){
  $date = $startDate;
  $end_date = $endDate;
  $dateTimeTriplets = array();
  if ($everyOther == "TRUE") {
    $everyOtherWeek = true;
  } else {$everyOtherWeek = false;}

  $all_weekdays_times = "";
  if ($everyOtherWeek) {
    $all_weekdays_times = "EO;";
  }
  $weekdaysAdded = array();
  $datesAdded = array();
  while (strtotime($date) <= strtotime($end_date)) {
    $dayOfWeek = date('l', strtotime($date));
    if (in_array($dayOfWeek, $_POST) and (!$everyOtherWeek or !in_array(date('Y-m-d', strtotime("-1 week" . $date)), $datesAdded))) {
      $startTime =  $_POST[strtolower($dayOfWeek).'-start-time'];
      $endTime = $_POST[strtolower($dayOfWeek).'-end-time'];
      $dateTimeTriplets[$date] = array($startTime, $endTime);
      $datesAdded[] = $date;
      if (!in_array($dayOfWeek, $weekdaysAdded)){
        $all_weekdays_times .= $dayOfWeek . "," . $startTime . "," . $endTime . ";";
        $weekdaysAdded[] = $dayOfWeek;
      }
    }
    //looper
    $date = date ('Y-m-d', strtotime("+1 day", strtotime($date)));
  }
  return [$dateTimeTriplets, $all_weekdays_times];
}







// Converts user selection of horses, clients, staff, and volunteers to arrays of respective database ids.
function convertSelectionsToDatabaseIDs($db_connection){
  $horseIDList = array();
  foreach ($_POST['horses'] as $key => $value) {
    $value = pg_escape_string($value);
    $id = pg_fetch_row(pg_query($db_connection, "SELECT id FROM horses WHERE name LIKE '{$value}' AND (archived IS NULL OR archived = '');"))[0];
    $horseIDList[] = $id;
  }

  $clientIDList = array();
  foreach ($_POST['clients'] as $key => $value) {
    $value = pg_escape_string($value);
    $id = pg_fetch_row(pg_query($db_connection, "SELECT id FROM clients WHERE name LIKE '{$value}' AND (archived IS NULL OR archived = '');"))[0];
    $clientIDList[] = $id;
  }

  $staffIDList = array();
  foreach ($_POST['staff'] as $key => $value) {
    $value = pg_escape_string($value);
    $id = pg_fetch_row(pg_query($db_connection, "SELECT id FROM workers WHERE name LIKE '{$value}' AND (archived IS NULL OR archived = '');"))[0];
    $staffIDList[] = $id;
  }


  $volunteerIDList = array();
  foreach ($_POST['volunteers'] as $key => $value) {
    $id = pg_fetch_row(pg_query($db_connection, "SELECT id FROM workers WHERE name LIKE '{$value}' AND (archived IS NULL OR archived = '');"))[0];
    $volunteerIDList[] = $id;
  }
  return [$horseIDList, $clientIDList, $staffIDList, $volunteerIDList];
}







//Runs conflict checking for a class, makes sure all selections are available.
function checkForConflicts($dateTimeTriplets, $convertedData, $skipHorse = false){
  //Expand convertedData into named variables
  $horseIDList = $convertedData[0];
  $clientIDList = $convertedData[1];
  $staffIDList = $convertedData[2];
  $volunteerIDList = $convertedData[3];
  //Initialize checkAvailability function
  include $_SERVER['DOCUMENT_ROOT']."/static/scripts/checkAvailability.php";
  //initialize check horse use by week function
  include $_SERVER['DOCUMENT_ROOT']."/static/scripts/getHorseUsesByDateRange.php";

  foreach ($dateTimeTriplets as $date => $timeArray) {
    if ($_POST['arena'] != "") {
      $result = checkAvailability($_POST['arena'], 'arena', $date, $timeArray[0], $timeArray[1]);
      if ($result) {
        $time1 = date('g:ia', strtotime($result[0]));
        $time2 = date('g:ia', strtotime($result[1]));
        echo "<h3 class='main-content-header' style='font-size: 25pt; color: var(--dark-red)'>CONFLICT: {$_POST['arena']} has another event on {$date} from {$time1} to {$time2}.</h3>";
        $conflict = true;
      }
    }
    if ($horseIDList != array()) {
      foreach ($horseIDList as $key => $horseID) {
        $result = checkAvailability($horseID, 'horses', $date, $timeArray[0], $timeArray[1], $skipHorse);
        if ($result) {
          $time1 = date('g:ia', strtotime($result[0]));
          $time2 = date('g:ia', strtotime($result[1]));
          if (is_array($result)) {
            echo "<h3 class='main-content-header' style='font-size: 25pt; color: var(--dark-red)'>CONFLICT: {$_POST['horses'][$key]} has another event on {$date} from {$time1} to {$time2}.</h3>";
          } else {
            echo "<br><h3 class='main-content-header' style='font-size: 25pt; color: var(--dark-red);'>{$result}</p>";
          }
          $conflict = true;
        }
      }
    }
    if ($_POST['tacks'] != array()) {
      foreach ($_POST['tacks'] as $key => $tackName) {
        $result = checkAvailability($tackName, 'tack', $date, $timeArray[0], $timeArray[1]);
        if ($result) {
          $time1 = date('g:ia', strtotime($result[0]));
          $time2 = date('g:ia', strtotime($result[1]));
          echo "<h3 class='main-content-header' style='font-size: 25pt; color: var(--dark-red)'>CONFLICT: {$tackName} has another event on {$date} from {$time1} to {$time2}.</h3>";
          $conflict = true;
        }
      }
    }
    if ($_POST['pads'] != array()) {
      foreach ($_POST['pads'] as $key => $padName) {
        $result = checkAvailability($padName, 'pad', $date, $timeArray[0], $timeArray[1]);
        if ($result) {
          $time1 = date('g:ia', strtotime($result[0]));
          $time2 = date('g:ia', strtotime($result[1]));
          echo "<h3 class='main-content-header' style='font-size: 25pt; color: var(--dark-red)'>CONFLICT: {$padName} has another event on {$date} from {$time1} to {$time2}.</h3>";
          $conflict = true;
        }
      }
    }
    if ($staffIDList != array()) {
      foreach ($staffIDList as $key => $staffID) {
        $result = checkAvailability($staffID, 'workers', $date, $timeArray[0], $timeArray[1]);
        if ($result) {
          $time1 = date('g:ia', strtotime($result[0]));
          $time2 = date('g:ia', strtotime($result[1]));
          echo "<h3 class='main-content-header' style='font-size: 25pt; color: var(--dark-red)'>CONFLICT: {$_POST['staff'][$key]} has another event on {$date} from {$time1} to {$time2}.</h3>";
          $conflict = true;
        }
      }
    }
    if ($volunteerIDList != array()) {
      foreach ($volunteerIDList as $key => $volunteerID) {
        $result = checkAvailability($volunteerID, 'workers', $date, $timeArray[0], $timeArray[1]);
        if ($result) {
          $time1 = date('g:ia', strtotime($result[0]));
          $time2 = date('g:ia', strtotime($result[1]));
          echo "<h3 class='main-content-header' style='font-size: 25pt; color: var(--dark-red)'>CONFLICT: {$_POST['leaders'][$key]} has another event on {$date} from {$time1} to {$time2}.</h3>";
          $conflict = true;
        }
      }
    }
    if ($clientIDList != array()) {
      foreach ($clientIDList as $key => $clientID) {
        $result = checkAvailability($clientID, 'clients', $date, $timeArray[0], $timeArray[1]);
        if ($result) {
          $time1 = date('g:ia', strtotime($result[0]));
          $time2 = date('g:ia', strtotime($result[1]));
          echo "<h3 class='main-content-header' style='font-size: 25pt; color: var(--dark-red)'>CONFLICT: {$_POST['clients'][$key]} has another event on {$date} from {$time1} to {$time2}.</h3>";
          $conflict = true;
        }
      }
    }
    if ($conflict) {
      return true;
    }
  }
}







// Goes at the end of every class create/edit page to prepare the data for the sql query
function prepClassDataForSQL($convertedData) {
  //Expand convertedData into named variables
  $horseIDList = $convertedData[0];
  $clientIDList = $convertedData[1];
  $staffIDList = $convertedData[2];
  $volunteerIDList = $convertedData[3];

  //Convert to sql syntax
  $clientIDList = pg_escape_string(to_pg_array($clientIDList));
  $horseIDList = pg_escape_string(to_pg_array($horseIDList));
  $tackList = pg_escape_string(to_pg_array($_POST['tacks']));
  $padList = pg_escape_string(to_pg_array($_POST['pads']));
  $tackNotes = pg_escape_string(to_pg_array($_POST['tack-notes']));
  $clientEquipmentNotes = pg_escape_string(to_pg_array($_POST['client-equipment-notes']));


  $staffJSON = "{";
  foreach ($staffIDList as $key => $staffID) {
    if ($staffID == 1) {continue;}
    $staffJSON .= "\"{$_POST['staff-roles'][$key]}\": {$staffID},";
  }
  $staffJSON = pg_escape_string(rtrim($staffJSON, ',') . "}");

  $volunteerJSON = "{";
  foreach ($volunteerIDList as $key => $volunteerID) {
    if ($volunteerID == 1) {continue;}
    $volunteerJSON .= "\"{$_POST['volunteer-roles'][$key]}\": {$volunteerID},";
  }
  $volunteerJSON = pg_escape_string(rtrim($volunteerJSON, ',') . "}");


  $displayTitle = pg_escape_string(trim($_POST['display-title']));

  //Bundle and return values
  return [$horseIDList, $clientIDList, $staffJSON, $volunteerJSON, $tackList, $padList, $tackNotes, $clientEquipmentNotes, $displayTitle];
}


// Preps just the tack and equipment notes fields for postgres
function prepTackNotesForSQL() {
  //Convert to sql syntax
  $tackNotes = pg_escape_string(to_pg_array($_POST['tack-notes']));
  $clientEquipmentNotes = pg_escape_string(to_pg_array($_POST['client-equipment-notes']));


  
  //Bundle and return values
  return [$tackNotes, $clientEquipmentNotes];
}





// Function to generate unique class code for creating and editing classes
function generateClassCode($db_connection) {
  $classCode = pg_fetch_row(pg_query($db_connection, "SELECT MAX(class_code) FROM classes;"), 0)[0];
  if ($classCode) {
    $classCode++;
  } else {
    $classCode = 1;
  }
  return $classCode;
}


// Function to generate unique shift code for creating and editing volunteer shifts
function generateShiftCode($db_connection) {
  $shiftCode = pg_fetch_row(pg_query($db_connection, "SELECT MAX(shift_code) FROM (SELECT shift_code FROM horse_care_shifts UNION SELECT shift_code FROM office_shifts) AS shift_code;"), 0)[0];
  if ($shiftCode) {
    $shiftCode++;
  } else {
    $shiftCode = 1;
  }
  return $shiftCode;
}





// Function to convert from PHP array to PG array
function to_pg_array($set) {
  settype($set, 'array'); // can be called with a scalar or array
  $result = array();
  foreach ($set as $t) {
      if (is_array($t)) {
          $result[] = to_pg_array($t);
      } else {
          $t = str_replace('"', '\\"', $t); // escape double quote
          if (! is_numeric($t)) // quote only non-numeric values
              $t = '"' . $t . '"';
          $result[] = $t;
      }
  }
  return '{' . implode(",", $result) . '}'; // format
}



// Function to convert stdclass Objects to PHP Arrays
function convert_object_to_array($data) {

    if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        return array_map(__FUNCTION__, $data);
    }
    else {
        return $data;
    }
}






?>
