<!DOCTYPE html>
<html>

<head>
  <title>Authentication Error</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="shortcut icon" href="../DBA_Project/img/CyberianData.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>
  <?php
  # Datos login
  $user = $_POST['username'];
  $password = $_POST['password'];
  $db_name = $_POST['db'];
  session_start();
  $_SESSION['username'] = $user;
  $_SESSION['password'] = $password;
  $_SESSION['db'] = $db_name;

  # Inicializar la conexión a Oracle
  $conn = oci_connect($user, $password, $db_name);

  if (!$conn) {
  ?>
    <div class="text-center">
      <h1 class="bad">AUTHENTICATION ERROR</h1>
      <a href="../DBA_Project/index.php">
        <button type="button" class="btn btn-danger">Back to login</button>
      </a>
    </div>
  <?php
  } else {
    $query = "connect $user/$password";
    $stid = oci_parse($conn, $query);
    oci_execute($stid, OCI_DEFAULT);
    header("location:menu.php");
  }

  # Cerrar la conexión con Oracle
  oci_free_statement($stid);
  oci_close($conn);
  ?>
</body>

</html>