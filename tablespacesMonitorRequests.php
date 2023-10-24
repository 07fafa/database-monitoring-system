<?php
$arrayNames = array();
$arrayTotalSize = array();

session_start();
$conn = oci_connect($_SESSION['username'], $_SESSION['password'], $_SESSION['db']);

function getTablespacesName($conn)
{
    $names = array();

    $sql = "SELECT Tablespace_name,
    sum(bytes)/1024/1024 TOTAL_MB
    FROM Dba_data_files
    GROUP BY Tablespace_name
    ORDER BY Tablespace_name ASC";

    $stid = oci_parse($conn, $sql);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($names, $row["TABLESPACE_NAME"]);
    }
    return $names;
}

function getMaxSize($conn)
{
    $totalSize = array();

    $sql = "SELECT Tablespace_name,
    sum(bytes)/1024/1024 TOTAL_MB
    FROM Dba_data_files
    GROUP BY Tablespace_name
    ORDER BY Tablespace_name ASC";

    $stid = oci_parse($conn, $sql);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($totalSize, $row["TOTAL_MB"]);
    }
    return $totalSize;
}

function getUsedSize($conn)
{
    $usedSize = array();

    $sql = "SELECT Tablespace_name,
    sum(bytes)/1024/1024 used_mb
    FROM Dba_extents
    GROUP BY Tablespace_name
    ORDER BY Tablespace_name ASC";

    $stid = oci_parse($conn, $sql);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($usedSize, $row["USED_MB"]);
    }
    return $usedSize;
}

function getHWM($conn)
{
    $hwm = array();

    $sql = "SELECT Tablespace_name,
    sum(bytes)/1024/1024 TOTAL_MB
    FROM Dba_data_files
    GROUP BY Tablespace_name
    ORDER BY Tablespace_name ASC";

    $stid = oci_parse($conn, $sql);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($hwm, ($row["TOTAL_MB"] * 0.85));
    }
    return $hwm;
}

function getFreeSize($conn)
{
    $free = array();

    $sql = "SELECT tablespace_name,
    sum(bytes)/1024/1024 FREE_MB
    FROM dba_free_space
    WHERE tablespace_name NOT LIKE 'TEMP%'
    GROUP BY tablespace_name
    ORDER BY Tablespace_name ASC";

    $stid = oci_parse($conn, $sql);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        array_push($free, $row["FREE_MB"]);
    }
    return $free;
}

$response = [
    "names" => getTablespacesName($conn),
    "maxSize" => getMaxSize($conn),
    "usedSize" => getUsedSize($conn),
    "hwm" => getHWM($conn),
    "freeSize" => getFreeSize($conn),
];

echo json_encode($response);
