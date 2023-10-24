<?php
session_start();
$conn = oci_connect($_SESSION['username'], $_SESSION['password'], $_SESSION['db']);

$sql = 'alter system switch logfile';
$stid = oci_parse($conn, $sql);
oci_execute($stid);

oci_free_statement($stid);
oci_close($conn);

header("location:redoLogsMonitor.php");
