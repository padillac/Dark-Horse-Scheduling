<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="/static/main.css">
  <?php include $_SERVER['DOCUMENT_ROOT'] . "/static/scripts/connectdb.php";?>
  <title>Admin | Edit Misc. Object</title>
</head>

<body>

  <header>
    <h1>Edit Object</h1>
    <nav>
      <a href="/"><button id="home-button">Home</button></a>
    </nav>
  </header>

  <?php

    $query = "UPDATE pg_enum SET enumlabel = '{$_POST['new-object-name']}' WHERE enumlabel = '{$_POST['selected-object']}' AND enumtypid = (SELECT oid FROM pg_type WHERE typname = '{$_POST['object-type']}');";
    $result = pg_query($db_connection, $query);
    if ($result) {
      echo "<h3 class='main-content-header'>Success</h3";
    } else {
      echo "<h3 class='main-content-header>An error occured.</h3><p class='main-content-header'>Please try again, ensure that all data is correctly formatted.</p>";
    }
  ?>


</body>

</html>
