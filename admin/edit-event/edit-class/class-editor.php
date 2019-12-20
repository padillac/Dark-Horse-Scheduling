<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="/static/main.css">
<link href="https://fonts.googleapis.com/css?family=Nunito:700&display=swap" rel="stylesheet">
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/static/scripts/initialization.php"; ?>
  <title>Admin | Edit Class</title>
</head>

<body>

  <header>
    <h1>Edit Class</h1>
    <nav> <a href="../../"><button id="back-button">Back</button></a>
      <a href="/"><button id="home-button">Home</button></a>
    </nav>
  </header>



  <h3 class='main-content-header'>{$_POST['selected-class']}</h3>"

    <?php 
      $selectedClassCode = explode(': ', explode('; ', $_POST['selected-class'])[4])[1];


      //Get class IDs
      $getClassIDsQuery = "SELECT id FROM classes WHERE class_code = '{$selectedClassCode}' AND (archived IS NULL OR archived = '');";
      $classIDSQLObject = pg_fetch_all(pg_query($db_connection, $getClassIDsQuery));
      $classIDList = array();
      foreach ($classIDSQLObject as $row => $data) {
        $classIDList[] = $data['id'];
      }

      //Get data from the next occurring class so that display information is accurate to edits already made
      $todaysDate = date('Y-m-d');
      $classIDList = to_pg_array($classIDList);
      $classDataQuery = "SELECT * FROM classes WHERE classes.id = ANY('{$classIDList}') AND classes.date_of_class >= '{$todaysDate}';";
      $classData = pg_fetch_row(pg_query($db_connection, $classDataQuery), 0, PGSQL_ASSOC);

      //If all class dates have past, do something???
      // Automatically archive class?



      //get start and end dates for entire class range
      $startDate = pg_fetch_array(pg_query($db_connection, "SELECT MIN (date_of_class) AS start_date FROM classes WHERE class_code = '{$selectedClassCode}' AND (archived IS NULL OR archived = '');"), 0, 1)['start_date'];
      $endDate = pg_fetch_array(pg_query($db_connection, "SELECT MAX (date_of_class) AS end_date FROM classes WHERE class_code = '{$selectedClassCode}' AND (archived IS NULL OR archived = '');"), 0, 1)['end_date'];


      $weekdaysBlocks = explode(";", $classData['all_weekdays_times']);
      if ($weekdaysBlocks[0] == "EO") {
        $everyOtherWeek = true;
        $everyOtherWeekCheckbox = "checked";
      } else {$everyOtherWeek = false; $everyOtherWeekCheckbox = "";}
      $allWeekdaysTimesList = array();
      foreach ($weekdaysBlocks as $weekdayString) {
        if ($weekdayString == "") {continue;}
        if ($weekdayString == "EO") {continue;}
        $weekdayTriple = explode(",", $weekdayString);
        $allWeekdaysTimesList[$weekdayTriple[0]] = array($weekdayTriple[1], $weekdayTriple[2]);
      }

      $checkboxList = array("Monday" => "", "Tuesday" => "", "Wednesday" => "", "Thursday" => "", "Friday" => "", "Saturday" => "", "Sunday" => "", );
      foreach ($allWeekdaysTimesList as $day => $times) {
        $checkboxList[$day] = "checked";
      }
      ?>


      <form id="class-form" autocomplete="off" action="edit-class.php" method="post" class="standard-form">

        <input type="text" name="class-code" value="{$selectedClassCode}" style="visibility: hidden;">

        <p>Class Type:</p>
        <input type="text" name="old-class-type" value="{$classData['class_type']}" style="visibility: hidden;">
        <input type="text" name="class-type" list="class-type-list" value="{$classData['class_type']}" onclick="select()" required>
          

        <p>Display Title:</p>
        <input type="text" name="display-title" value="{$classData['display_title']}" onclick="select();" required>


        <p>Dates:</p>
        <p style="font-size: 12pt; margin-top: 0; margin-bottom: 12px;">Every other week: <input type="checkbox" name="every-other-week" value="TRUE" {$everyOtherWeekCheckbox}></p>
        <div style="max-width: 500px;">
          <label for="start-date">Start date:</label>
          <input type="date" id="start-date" name="start-date" value="{$startDate}" placeholder="from" required>
          <label for="end-date">End date:</label>
          <input type="date" id="end-date" name="end-date" value="{$endDate}" placeholder="to" required>
        </div>

        <div style="max-width: 430px;">
          <!-- MONDAY-->
          <label for="monday-checkbox">Monday: </label>
          <input type="checkbox" id="monday-checkbox" name="monday-checkbox" value="Monday" {$checkboxList['Monday']}>
          <label for="monday-start-time">from:</label>
          <input type="time" id="monday-start-time" name="monday-start-time" value="{$allWeekdaysTimesList['Monday'][0]}">
          <label for="monday-end-time">to:</label>
          <input type="time" id="monday-end-time" name="monday-end-time" value="{$allWeekdaysTimesList['Monday'][1]}">
          <!-- TUESDAY-->
          <label for="tuesday-checkbox">Tuesday: </label>
          <input type="checkbox" id="tuesday-checkbox" name="tuesday-checkbox" value="Tuesday" {$checkboxList['Tuesday']}>
          <label for="tuesday-start-time">from:</label>
          <input type="time" id="tuesday-start-time" name="tuesday-start-time" value="{$allWeekdaysTimesList['Tuesday'][0]}">
          <label for="tuesday-end-time">to:</label>
          <input type="time" id="tuesday-end-time" name="tuesday-end-time" value="{$allWeekdaysTimesList['Tuesday'][1]}">
          <!-- WEDNESDAY-->
          <label for="wednesday-checkbox">Wednesday: </label>
          <input type="checkbox" id="wednesday-checkbox" name="wednesday-checkbox" value="Wednesday" {$checkboxList['Wednesday']}>
          <label for="wednesday-start-time">from:</label>
          <input type="time" id="wednesday-start-time" name="wednesday-start-time" value="{$allWeekdaysTimesList['Wednesday'][0]}">
          <label for="wednesday-end-time">to:</label>
          <input type="time" id="wednesday-end-time" name="wednesday-end-time" value="{$allWeekdaysTimesList['Wednesday'][1]}">
          <!-- THURSDAY-->
          <label for="thursday-checkbox">Thursday: </label>
          <input type="checkbox" id="thursday-checkbox" name="thursday-checkbox" value="Thursday" {$checkboxList['Thursday']}>
          <label for="thursday-start-time">from:</label>
          <input type="time" id="thursday-start-time" name="thursday-start-time" value="{$allWeekdaysTimesList['Thursday'][0]}">
          <label for="thursday-end-time">to:</label>
          <input type="time" id="thursday-end-time" name="thursday-end-time" value="{$allWeekdaysTimesList['Thursday'][1]}">
          <!-- FRIDAY-->
          <label for="friday-checkbox">Friday: </label>
          <input type="checkbox" id="friday-checkbox" name="friday-checkbox" value="Friday" {$checkboxList['Friday']}>
          <label for="friday-start-time">from:</label>
          <input type="time" id="friday-start-time" name="friday-start-time" value="{$allWeekdaysTimesList['Friday'][0]}">
          <label for="friday-end-time">to:</label>
          <input type="time" id="friday-end-time" name="friday-end-time" value="{$allWeekdaysTimesList['Friday'][1]}">
          <!-- SATURDAY-->
          <label for="saturday-checkbox">Saturday: </label>
          <input type="checkbox" id="saturday-checkbox" name="saturday-checkbox" value="Saturday" {$checkboxList['Saturday']}>
          <label for="saturday-start-time">from:</label>
          <input type="time" id="saturday-start-time" name="saturday-start-time" value="{$allWeekdaysTimesList['Saturday'][0]}">
          <label for="saturday-end-time">to:</label>
          <input type="time" id="saturday-end-time" name="saturday-end-time" value="{$allWeekdaysTimesList['Saturday'][1]}">
          <!-- SUNDAY-->
          <label for="sunday-checkbox">Sunday: </label>
          <input type="checkbox" id="sunday-checkbox" name="sunday-checkbox" value="Sunday" {$checkboxList['Sunday']}>
          <label for="sunday-start-time">from:</label>
          <input type="time" id="sunday-start-time" name="sunday-start-time" value="{$allWeekdaysTimesList['Sunday'][0]}">
          <label for="sunday-end-time">to:</label>
          <input type="time" id="sunday-end-time" name="sunday-end-time" value="{$allWeekdaysTimesList['Sunday'][1]}">
        </div>

        <p>Arena:</p>
        <input type="text" name="arena" list="arena-list" value="{$classData['arena']}" onclick="select();">
          


        <div>
          <div id="staff-section">
            <p>Staff:</p>

              

        <?php   
            $staffData = json_decode($classData['staff']);
            foreach ($staffData as $role => $staffID) {
                $staffName = pg_fetch_array(pg_query($db_connection, "SELECT name FROM workers WHERE workers.id = {$staffID};"), 0, 1)['name'];
                $staffName = htmlspecialchars($staffName, ENT_QUOTES);
                //PRINT STAFF FIELDS FOR EACH NAME
            }
        ?>

          <label>Role: </label>
          <input form="class-form" type="text" name="staff-roles[]" list="staff-role-list" value="{$role}" onclick="select();">
          <br>
          <label>Staff Member: </label>
          <input form="class-form" type="text" name="staff[]" list="staff-list" value="{$staffName}" onclick="select();">

        }

          </div>
          <br>
          <button type="button" id="add-staff-button" onclick="newStaffFunction();">Add Additional Staff Member</button>
        </div>



        <p style='font-size: 12pt; color: var(--dark-red)'>Archive: <input type="checkbox" name="archive" value="TRUE"> Saves class in database but removes from all schedules and menus</p>

        <div>
          <p style='font-size: 12pt; color: var(--dark-red)'>Delete Class?
          <input type="checkbox" id="delete-checkbox" name="DELETE" value="TRUE">
          WARNING: this will permanently delete all record of the class</p>
        </div>

       
   




      <div>
        <div id="client-section">
            <p>Client(s):</p>
            <?php
                $oldClientIDListPGArray = "{";
                $clientIDList = explode(',', ltrim(rtrim($classData['clients'], "}"), '{'));
                foreach ($clientIDList as $id) {
                    $clientName = pg_fetch_array(pg_query($db_connection, "SELECT name FROM clients WHERE clients.id = {$id}") , 0, 1)['name'];
                    $oldClientIDListPGArray .= $id .',';
                    }
                $oldClientIDListPGArray = rtrim($oldClientIDListPGArray, ',') . "}";
            ?>
 
        </div>
        <input form='class-form' type="text" name="old-client-id-list" value="{$oldClientIDListPGArray}" style="visibility: hidden; height: 1px;">
        <button type="button" id="add-client-button" onclick="newClientFunction();">Add Additional Client</button>
      </div>


      <div>
      <div id="horse-section">
        <p>Horse(s):</p>
        <?php
            $horseIDList = explode(',', ltrim(rtrim($classData['horses'], "}"), '{'));
            foreach ($horseIDList as $id) {
                $horseName = pg_fetch_array(pg_query($db_connection, "SELECT name FROM horses WHERE id = {$id} AND (archived IS NULL OR archived = '');") , 0, 1)['name'];
            }
        ?>
      
        </div>
        <br>
        <button type="button" id="add-horse-button" onclick="newHorseFunction();">Add Additional Horse</button>
        </div>

        <div>
        <div id="tack-section">
          <p>Tack(s):</p>
            <?php
                $tackList = explode(',', ltrim(rtrim($classData['tacks'], "}"), '{'));
                foreach ($tackList as $name) {
                $name = ltrim(rtrim($name, '\"'), '\"');
                }
            ?>

        
          </div>
          <br>
          <button type="button" id="add-tack-button" onclick="newTackFunction();">Add Additional Tack</button>
          </div>


          <div>
          <div id="pad-section">
            <p>Pad(s):</p>
                <?php
                $padList = explode(',', ltrim(rtrim($classData['pads'], "}"), '{'));
                foreach ($padList as $key => $name) {
                    $padList[$key] = rtrim(ltrim($name, "\""), "\"");
                }
                ?>

            </div>
            <br>
            <button type="button" id="add-pad-button" onclick="newPadFunction();">Add Additional Pad</button>
            </div>



            <div>
              <div id="tack-notes-section">
                <p>Tack Note(s):</p>
                <?php
                  $tackNotesData = explode(',', ltrim(rtrim($classData['tack_notes'], "}"), '{'));
                  if ($tackNotesData) {
                    foreach ($tackNotesData as $note) {
                      $note = ltrim(rtrim($note, '"'), '"');
                      echo "<input form='class-form' type='text' name='tack-notes[]' value='{$note}' onclick='select();'>";
                    }
                  } else {
                    echo "<input form='class-form' type='text' name='tack-notes[]' value='' onclick='select();'>";
                  }
                  ?>
              </div>
              <br>
              <button type="button" id="add-tack-notes-button" onclick="newTackNotesFunction();">Add Additional Tack Note</button>
            </div>

            <div>
              <div id="client-equipment-section">
                <p>Client Equipment:</p>
                <?php
                  $clientEquipmentNotesData = explode(',', ltrim(rtrim($classData['client_equipment_notes'], "}"), '{'));
                  if ($clientEquipmentNotesData) {
                    foreach ($clientEquipmentNotesData as $note) {
                      $note = ltrim(rtrim($note, '"'), '"');
                      echo "<input form='class-form' type='text' name='client-equipment-notes[]' value='{$note}' onclick='select();'>";
                    }
                  } else {
                    echo "<input form='class-form' type='text' name='client-equipment-notes[]' value='' onclick='select();'>";
                  }
                ?>
              </div>
              <br>
              <button type="button" id="add-client-equipment-notes-button" onclick="newClientEquipmentNotesFunction();">Add Client Equipment Note</button>
            </div>




        <div>
        <div id="volunteer-role-section">
          <p>Volunteer Role(s):</p>
        <?php
        $volunteerData = json_decode($classData['volunteers']);
        foreach ($volunteerData as $role => $volunteerID) {
          $volunteerName = pg_fetch_array(pg_query($db_connection, "SELECT name FROM workers WHERE workers.id = {$volunteerID};"), 0, 1)['name'];

          echo <<<EOT
          <input form="class-form" type="text" name="volunteer-roles[]" list="volunteer-role-list" value="{$role}" onclick="select();">

EOT;
        }
        ?>

          </div>
          <br>
          <button type="button" id="add-volunteer-button" onclick="newVolunteerFunction();">Add Additional Volunteer</button>
          </div>


          <div>
          <div id="volunteer-section">
            <p>Volunteer(s):</p>
            <?php

            foreach ($volunteerData as $role => $volunteerID) {
                $volunteerName = pg_fetch_array(pg_query($db_connection, "SELECT name FROM workers WHERE workers.id = {$volunteerID} AND (archived IS NULL OR archived = '');") , 0, 1)['name'];
                echo <<<EOT
                <input form='class-form' type="text" name="volunteers[]" list="volunteer-list" value="{$volunteerName}" onclick="select();">
EOT;
            }
            ?>

            </div>
            <br>
            </div>

      </div>










    <!-- DATA LISTS -->

    <datalist id="class-type-list">
    <?php
        $query = "SELECT unnest(enum_range(NULL::CLASS_TYPE))::text EXCEPT SELECT name FROM archived_enums;";
        $result = pg_query($db_connection, $query);
        $classTypeNames = pg_fetch_all_columns($result);
        foreach ($classTypeNames as $key => $value) {
        $value = htmlspecialchars($value, ENT_QUOTES);
        echo "<option value='{$value}'>";
        }
    ?>
    </datalist>

    <datalist id="staff-role-list">
    <?php
        $query = "SELECT unnest(enum_range(NULL::STAFF_CLASS_ROLE))::text EXCEPT SELECT name FROM archived_enums;";
        $result = pg_query($db_connection, $query);
        $classTypeNames = pg_fetch_all_columns($result);
        foreach ($classTypeNames as $key => $value) {
        $value = htmlspecialchars($value, ENT_QUOTES);
        echo "<option value='$value'>";
        }
    ?>
    </datalist>

    <datalist id="staff-list">
    <?php
        $query = "SELECT name FROM workers WHERE staff = TRUE AND (archived IS NULL OR archived = '');";
        $result = pg_query($db_connection, $query);
        $staffNames = pg_fetch_all_columns($result);
        foreach ($staffNames as $key => $name) {
        $name = htmlspecialchars($name, ENT_QUOTES);
        echo "<option value='$name'>";
        }
    ?>
    </datalist>
    
    <datalist id="client-list">
    <?php
        $query = "SELECT name FROM clients WHERE (archived IS NULL OR archived = '');";
        $result = pg_query($db_connection, $query);
        $clientNames = pg_fetch_all_columns($result);
        foreach ($clientNames as $key => $value) {
        $value = htmlspecialchars($value, ENT_QUOTES);
        echo "<option value='{$value}'>";
        }
    ?>
    </datalist>

    <datalist id="horse-list">
    <?php
        $query = "SELECT name FROM horses WHERE (archived IS NULL OR archived = '');";
        $result = pg_query($db_connection, $query);
        $horseNames = pg_fetch_all_columns($result);
        foreach ($horseNames as $key => $value) {
        $value = htmlspecialchars($value, ENT_QUOTES);
        echo "<option value='$value'>";
        }
    ?>
    </datalist>

    <datalist id="tack-list">
    <?php
        $query = "SELECT unnest(enum_range(NULL::TACK))::text EXCEPT SELECT name FROM archived_enums;";
        $result = pg_query($db_connection, $query);
        $tackNames = pg_fetch_all_columns($result);
        foreach ($tackNames as $key => $value) {
        $value = htmlspecialchars($value, ENT_QUOTES);
        echo "<option value='$value'>";
        }
    ?>
    </datalist>

    <datalist id="pad-list">
    <?php
        $query = "SELECT unnest(enum_range(NULL::PAD))::text EXCEPT SELECT name FROM archived_enums;";
        $result = pg_query($db_connection, $query);
        $padNames = pg_fetch_all_columns($result);
        foreach ($padNames as $key => $value) {
        $value = htmlspecialchars($value, ENT_QUOTES);
        echo "<option value='$value'>";
        }
    ?>
    </datalist>

    <datalist id="volunteer-role-list">
    <?php
        $query = "SELECT unnest(enum_range(NULL::VOLUNTEER_CLASS_ROLE))::text EXCEPT SELECT name FROM archived_enums;";
        $result = pg_query($db_connection, $query);
        $roleNames = pg_fetch_all_columns($result);
        foreach ($roleNames as $key => $value) {
        $value = htmlspecialchars($value, ENT_QUOTES);
        echo "<option value='$value'>";
        }
    ?>
    </datalist>

    <datalist id="volunteer-list">
    <?php
        $query = "SELECT name FROM workers WHERE (archived IS NULL OR archived = '');";
        $result = pg_query($db_connection, $query);
        $workerNames = pg_fetch_all_columns($result);
        foreach ($workerNames as $key => $value) {
        $value = htmlspecialchars($value, ENT_QUOTES);
        echo "<option value='$value'>";
        }
    ?>
    </datalist>








<footer>
    <script type="text/javascript">

    function newStaffFunction() {
        newFormSection = document.createElement('div');
        newFormSection.setAttribute('class', 'form-section');
        var staffSection = document.getElementById('staff-section');
        staffSection.appendChild(newFormSection);
        //add role selector
        newFormElement = document.createElement('div');
        newFormElement.setAttribute('class', 'form-element');
        newFormSection.appendChild(newFormElement);
        newLabel = document.createElement('label');
        newLabel.innerHTML = "Role: ";
        newFormElement.appendChild(newLabel);
        newInput = document.createElement('input');
        newInput.setAttribute('type', 'text');
        newInput.setAttribute('name', 'staff-roles[]');
        newInput.setAttribute('list', 'staff-role-list');
        newInput.setAttribute('value', '');
        newInput.setAttribute('onclick', 'select()');
        newInput.setAttribute('form', 'class-form');
        newFormElement.appendChild(newInput);
        //Add name selector
        newFormElement2 = document.createElement('div');
        newFormElement2.setAttribute('class', 'form-element');
        newFormSection.appendChild(newFormElement2);
        newLabel2 = document.createElement('label');
        newLabel2.innerHTML = "Staff Member: ";
        newFormElement2.appendChild(newLabel2);
        newInput2 = document.createElement('input');
        newInput2.setAttribute('type', 'text');
        newInput2.setAttribute('name', 'staff[]');
        newInput2.setAttribute('list', 'staff-list');
        newInput2.setAttribute('value', '');
        newInput2.setAttribute('onclick', 'select()');
        newInput2.setAttribute('form', 'class-form');
        newFormElement2.appendChild(newInput2);
      };

    



    function newClientHorseSection() {
        newSection = document.createElement('div');
        newSection.setAttribute('class', 'client-horse-form-section');
        parent = document.getElementById('client-horse-section');
        parent.appendChild(newSection);
        //Add client
        newClient(newSection);
        //Add Horse
        newHorse(newSection);
        //Add Tack
        newTack(newSection);
        //Add Pad
        newPad(newSection);
        //Add Tack Notes
        newTackNotes(newSection);
        //Add Equipment Notes
        newEquipmentNotes(newSection);
    };


    function newClient(section) {
        newFormElement = document.createElement('div');
        newFormElement.setAttribute('class', 'form-element');
        newInput = document.createElement('input');
        newFormElement.appendChild(newInput);
        newInput.setAttribute('type', 'text');
        newInput.setAttribute('name', 'clients[]');
        newInput.setAttribute('list', 'client-list');
        newInput.setAttribute('value', '');
        newInput.setAttribute('onclick', 'select()');
        newInput.setAttribute('form', 'class-form');
        section.appendChild(newFormElement);
    };

    function newHorse(section) {
        newFormElement = document.createElement('div');
        newFormElement.setAttribute('class', 'form-element');
        newInput = document.createElement('input');
        newFormElement.appendChild(newInput);
        newInput.setAttribute('type', 'text');
        newInput.setAttribute('name', 'horses[]');
        newInput.setAttribute('list', 'horse-list');
        newInput.setAttribute('value', '');
        newInput.setAttribute('onclick', 'select()');
        newInput.setAttribute('form', 'class-form');
        section.appendChild(newFormElement);
    };

    function newTack(section) {
        newFormElement = document.createElement('div');
        newFormElement.setAttribute('class', 'form-element');
        newInput = document.createElement('input');
        newFormElement.appendChild(newInput);
        newInput.setAttribute('type', 'text');
        newInput.setAttribute('name', 'tacks[]');
        newInput.setAttribute('list', 'tack-list');
        newInput.setAttribute('value', '');
        newInput.setAttribute('onclick', 'select()');
        newInput.setAttribute('form', 'class-form');
        section.appendChild(newFormElement);
    };

    function newPad(section) {
        newFormElement = document.createElement('div');
        newFormElement.setAttribute('class', 'form-element');
        newInput = document.createElement('input');
        newFormElement.appendChild(newInput);
        newInput.setAttribute('type', 'text');
        newInput.setAttribute('name', 'pads[]');
        newInput.setAttribute('list', 'pad-list');
        newInput.setAttribute('value', '');
        newInput.setAttribute('onclick', 'select()');
        newInput.setAttribute('form', 'class-form');
        section.appendChild(newFormElement);
    };
    function newTackNotes(section) {
        newFormElement = document.createElement('div');
        newFormElement.setAttribute('class', 'form-element');
        newInput = document.createElement('input');
        newFormElement.appendChild(newInput);
        newInput.setAttribute('type', 'text');
        newInput.setAttribute('name', 'tack-notes[]');
        newInput.setAttribute('value', '');
        newInput.setAttribute('onclick', 'select()');
        newInput.setAttribute('form', 'class-form');
        section.appendChild(newFormElement);
    };
    function newEquipmentNotes(section) {
        newFormElement = document.createElement('div');
        newFormElement.setAttribute('class', 'form-element');
        newInput = document.createElement('input');
        newFormElement.appendChild(newInput);
        newInput.setAttribute('type', 'text');
        newInput.setAttribute('name', 'client-equipment-notes[]');
        newInput.setAttribute('value', '');
        newInput.setAttribute('onclick', 'select()');
        newInput.setAttribute('form', 'class-form');
        section.appendChild(newFormElement);
    };






    function newVolunteerFunction() {
        newFormSection = document.createElement('div');
        newFormSection.setAttribute('class', 'form-section');
        //Add role selector
        newFormElement = document.createElement('div');
        newFormSection.appendChild(newFormElement);
        newFormElement.setAttribute('class', 'form-element');
        newInput = document.createElement('input');
        newFormElement.appendChild(newInput);
        newInput.setAttribute('type', 'text');
        newInput.setAttribute('name', 'volunteer-roles[]');
        newInput.setAttribute('list', 'volunteer-role-list');
        newInput.setAttribute('value', '');
        newInput.setAttribute('onclick', 'select()');
        newInput.setAttribute('form', 'class-form');
        //Add name selector
        newFormElement2 = document.createElement('div');
        newFormSection.appendChild(newFormElement2);
        newFormElement2.setAttribute('class', 'form-element');
        newInput2 = document.createElement('input');
        newFormElement2.appendChild(newInput2);
        newInput2.setAttribute('type', 'text');
        newInput2.setAttribute('name', 'volunteers[]');
        newInput2.setAttribute('list', 'volunteer-list');
        newInput2.setAttribute('value', '');
        newInput2.setAttribute('onclick', 'select()');
        newInput2.setAttribute('form', 'class-form');
        var volunteerSection = document.getElementById('volunteer-section');
        volunteerSection.appendChild(newFormSection);
    };






    // VALIDATE START AND END DATE SELECTIONS
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd = '0'+dd
    }

    if(mm<10) {
        mm = '0'+mm
    }

    today = yyyy + '-' + mm + '-' + dd;

    var startDateSelector = document.getElementById('start-date');
    var endDateSelector = document.getElementById('end-date');
    startDateSelector.onchange = function() {
        if (this.value < today) {
        alert("Please select a valid start date \u2014 cannot start in the past!");
        this.value = "";
        } else if (this.value > endDateSelector.value) {
        alert("Check your dates \u2014 end date cannot be before the start date!");
        this.value = "";
        endDateSelector.value = "";
        }
    };

    endDateSelector.onchange = function() {
        if (this.value < startDateSelector.value) {
        alert("Please select a valid end date \u2014 cannot end before the start date!");
        this.value = "";
        }
    };

    </script>
</footer>






</body>

</html>