<?php

foreach ($allClasses as $key => $specificClass) {

  $getClientsQuery = <<<EOT
    SELECT id, clients.name FROM clients WHERE
    clients.id = ANY('{$allClasses[$key]['clients']}')
    ;
EOT;
  $getAttendanceQuery = <<<EOT
    SELECT clients.name FROM clients WHERE
    clients.id = ANY('{$allClasses[$key]['attendance']}')
    ;
EOT;
    if ($specificClass['horses']) {
      $getHorsesQuery = <<<EOT
        SELECT id, name FROM horses WHERE
        id = ANY('{$allClasses[$key]['horses']}') AND
        (archived IS NULL OR archived = '')
        ;
EOT;
    }

  $clients = pg_fetch_all(pg_query($db_connection, $getClientsQuery));
  $attendance = pg_fetch_all_columns(pg_query($db_connection, $getAttendanceQuery));
  $horses = pg_fetch_all(pg_query($db_connection, $getHorsesQuery));


  $clientOrder = explode(',', rtrim(ltrim($allClasses[$key]['clients'], '{'), '}'));
  $horseOrder = explode(',', rtrim(ltrim($allClasses[$key]['horses'], '{'), '}'));

  $allClasses[$key]['clients'] = array();
  foreach ($clientOrder as $id) {
    foreach ($clients as $clientData) {
      if ($clientData['id'] == $id) {
        $allClasses[$key]['clients'][] = $clientData['name'];
      }
    }
  }

  $allClasses[$key]['attendance'] = $attendance;


  $allClasses[$key]['horses'] = array();
  foreach ($horseOrder as $id) {
    foreach ($horses as $horseData) {
      if ($horseData['id'] == $id) {
        $allClasses[$key]['horses'][] = $horseData['name'];
      }
    }
  }



  $rawArray = explode(",", ltrim(rtrim($allClasses[$key]['staff'], '}'), '{'));
  $allClasses[$key]['staff'] = array();
  foreach ($rawArray as $roleIDString) {
    $roleIDString = trim($roleIDString);
    $role = rtrim(ltrim(explode(':', $roleIDString)[0], '"'), '"');
    $staffID = trim(explode(':', $roleIDString)[1]);
    $allClasses[$key]['staff'][$role] = pg_fetch_array(pg_query($db_connection, "SELECT name FROM workers WHERE id = {$staffID} ;"))['name'];
  }

  $rawArray = explode(",", ltrim(rtrim($allClasses[$key]['volunteers'], '}'), '{'));
  $allClasses[$key]['volunteers'] = array();
  foreach ($rawArray as $roleIDString) {
    $roleIDString = trim($roleIDString);
    $role = rtrim(ltrim(explode(':', $roleIDString)[0], '"'), '"');
    $volunteerID = trim(explode(':', $roleIDString)[1]);
    $allClasses[$key]['volunteers'][$role] = pg_fetch_array(pg_query($db_connection, "SELECT name FROM workers WHERE id = {$volunteerID} ;"))['name'];
  }


  $allClasses[$key]['tacks'] = explode(',', rtrim(ltrim($allClasses[$key]['tacks'], '{'), '}'));
  $allClasses[$key]['pads'] = explode(',', rtrim(ltrim($allClasses[$key]['pads'], '{'), '}'));
  $allClasses[$key]['tack_notes'] = explode(',', rtrim(ltrim($allClasses[$key]['tack_notes'], '{'), '}'));
  $allClasses[$key]['client_equipment_notes'] = explode(',', rtrim(ltrim($allClasses[$key]['client_equipment_notes'], '{'), '}'));


}

?>
