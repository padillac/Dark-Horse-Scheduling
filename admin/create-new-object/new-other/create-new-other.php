<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="/static/main.css">
<link href="https://fonts.googleapis.com/css?family=Nunito:700&display=swap" rel="stylesheet">
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/static/scripts/initialization.php"; ?>
  <title>Admin | New Misc. Object</title>
</head>

<body>

  <header>
    <h1>New Object</h1>
    <nav>
      <a href="index.php"><button>Create Another</button></a>
      <a href="../"><button id="back-button">Back</button></a>
      <a href="/"><button id="home-button">Home</button></a>
    </nav>
  </header>

  <?php
    $archivedObjects = pg_fetch_all_columns(pg_query($db_connection, "SELECT name FROM archived_enums;"), 0);
    $objectName = pg_escape_string(trim($_POST['new-object-name']));
    if (in_array(trim($_POST['new-object-name']), $archivedObjects)) {
      $query = "DELETE FROM archived_enums WHERE name = '{$objectName}';";
    } else {
      $query = "ALTER TYPE {$_POST['object-type']} ADD VALUE '{$objectName}';";
    }

    $result = pg_query($db_connection, $query);

    if ($result) {
      echo "<h3 class='main-content-header'>Success</h3";
    } else {
      echo "<h3 class='main-content-header'>An error occurred.</h3><p class='main-content-header'>Please try again, ensure that all data is correctly formatted.</p>";
    }
  ?>


</body>

</html>
