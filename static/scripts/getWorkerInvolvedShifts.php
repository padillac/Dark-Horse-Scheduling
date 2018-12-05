<?php
  //Get id of worker being requested in $QUERY_NAME
  $queryID = pg_fetch_array(pg_query($db_connection, "SELECT id FROM workers WHERE workers.name = '{$QUERY_NAME}';"), 0, 1)['id'];

  $horseCareQuery = <<<EOT
  SELECT * FROM horse_care_shifts WHERE
  leader = {$queryID} OR
  {$queryID} = ANY(volunteers)
  ;
EOT;

  $officeShiftQuery = <<<EOT
  SELECT * FROM office_shifts WHERE
  leader = {$queryID} OR
  {$queryID} = ANY(volunteers)
  ;
EOT;

  $allHorseCareShifts = pg_fetch_all(pg_query($db_connection, $horseCareQuery));
  $allOfficeShifts = pg_fetch_all(pg_query($db_connection, $officeShiftQuery));

  if ($allHorseCareShifts) {
    foreach ($allHorseCareShifts as $key => $specificHorseCareShift) {
      $getVolunteersQuery = <<<EOT
        SELECT name FROM workers WHERE
        workers.id = ANY('{$allHorseCareShifts[$key]['volunteers']}')
        ;
EOT;

      $volunteers = pg_fetch_all_columns(pg_query($db_connection, $getVolunteersQuery));

      $allHorseCareShifts[$key]['volunteers'] = $volunteers;
    }
  }
  if ($allOfficeShifts) {
    foreach ($allOfficeShifts as $key => $specificOfficeShift) {
      $getVolunteersQuery = <<<EOT
        SELECT name FROM workers WHERE
        workers.id = ANY('{$allOfficeShifts[$key]['volunteers']}')
        ;
EOT;

      $volunteers = pg_fetch_all_columns(pg_query($db_connection, $getVolunteersQuery));

      $allOfficeShifts[$key]['volunteers'] = $volunteers;
    }
  }

  //$allHorseCareShifts
  //$allOfficeShifts
  //These two dictionaries are now available

?>
