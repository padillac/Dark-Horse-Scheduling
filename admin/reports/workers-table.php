  <?php
    //initialize target table name
    $tableName = "workers";
    //Connect to database
    include $_SERVER['DOCUMENT_ROOT']."/static/scripts/initialization.php";

    //delete tempfiles from previous reports
    if (file_exists("/tmp/DHStempfile.csv")) {
      unlink("/tmp/DHStempfile.csv");
    }

    //Get table columns for CSV file
    $metadata = array();
    $metadata[0] = pg_fetch_all_columns(pg_query($db_connection, "SELECT column_name FROM information_schema.columns WHERE table_schema = 'public' AND table_name = '{$tableName}';"));

    //Get table data
    $result = pg_copy_to($db_connection, "{$tableName}", "%", "");
    foreach ($result as $key => $dataString) {
      $result[$key] = explode('%', trim($dataString));
    }

    $data = array_merge($metadata, $result);

    //Write data to temporary CSV file on the server
    $tempfile = fopen('/tmp/DHStempfile.csv', 'w');

    foreach ($data as $line) {
      fputcsv($tempfile, $line);
    }

    fclose($tempfile);



    //Send file to client browser
    $filename = "/tmp/DHStempfile.csv";

    if(file_exists($filename)){

        //Get file type and set it as Content Type
        header('Content-Type: application/csv');

        $date = date('Y-m-d');
        //Use Content-Disposition: attachment to specify the filename
        header("Content-Disposition: attachment; filename={$tableName}-table-{$date}.csv");

        //No cache
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        //Define file size
        header('Content-Length: ' . filesize($filename));

        ob_clean();
        flush();
        readfile($filename);
        exit;
    }

  ?>
