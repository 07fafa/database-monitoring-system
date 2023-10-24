<?php
session_start();
$conn = oci_connect($_SESSION['username'], $_SESSION['password'], $_SESSION['db']);

function getHour($conn)
{
    $sqlHour = "SELECT TO_CHAR(SYSDATE, 'HH24:MI:SS') TIME_NOW FROM DUAL";

    $stid = oci_parse($conn, $sqlHour);
    oci_execute($stid);

    $row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS);
    $data = $row["TIME_NOW"];

    return $data;
}

function getUsedSGASize($conn)
{
    $sgastat = '$sgastat';
    $sqlUsedSGA = "SELECT round(used.bytes /1024/1024) USED_MB
        FROM (SELECT sum(bytes) bytes
        FROM v$sgastat
        WHERE name != 'free memory') used";

    $stid = oci_parse($conn, $sqlUsedSGA);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $data = $row["USED_MB"];
    }
    return $data;
}

function getMaxSGASize($conn)
{
    $sgainfo = '$sgainfo';
    $sqlMaxSGA = "SELECT SUM (round(bytes/1024/1024)) MAX_SGA_SIZE_IN_MB 
        FROM V$sgainfo WHERE NAME='Maximum SGA Size'";

    $stid = oci_parse($conn, $sqlMaxSGA);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $data = $row["MAX_SGA_SIZE_IN_MB"];
    }
    return $data;
}

function getHWM($conn)
{
    $HWM = getMaxSGASize($conn) * 0.85;
    return $HWM;
}

function getDateNow($conn)
{
    $sqlDate = "SELECT TO_CHAR (SYSDATE, 'MM/DD/YY') as DATE_NOW FROM DUAL";

    $stid = oci_parse($conn, $sqlDate);
    oci_execute($stid);

    $row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS);
    $data = $row["DATE_NOW"];

    return $data;
}

function getFreeMemory($conn)
{
    $sgastat = '$sgastat';
    $sqlFreeMem = "SELECT round(free.bytes /1024/1024 ,2) free_mb
    FROM (SELECT sum(bytes) bytes
    FROM v$sgastat
    WHERE name = 'free memory') free, 
    (SELECT sum(bytes) bytes
    FROM v$sgastat) tot";

    $stid = oci_parse($conn, $sqlFreeMem);
    oci_execute($stid);

    $row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS);
    $data = $row["FREE_MB"];

    return $data;
}

$date = getDateNow($conn);
$hour = getHour($conn);
$max = getMaxSGASize($conn);
$free = getFreeMemory($conn);
$hwm = getHWM($conn);
$used = getUsedSGASize($conn);
$random = rand(900, 1470);

if ($random >= $hwm) {
    $fi = fopen("./reports/reportHWM.txt", "a");

    fwrite($fi, "Date: ");
    fwrite($fi, $date);
    fwrite($fi, " | ");
    fwrite($fi, "Time: ");
    fwrite($fi, $hour);
    fwrite($fi, " | ");
    fwrite($fi, "Total_Size: ");
    fwrite($fi, $max);
    fwrite($fi, " | ");
    fwrite($fi, "Free_Size: ");
    fwrite($fi, $free);
    fwrite($fi, " | ");
    fwrite($fi, "HWM: ");
    fwrite($fi, $hwm);
    fwrite($fi, " | ");
    fwrite($fi, "Exceeded: ");
    fwrite($fi, "YES");
    fwrite($fi, "\r\n");

    fclose($fi);
} else {
    $fi = fopen("./reports/reportHWM.txt", "a");

    fwrite($fi, "Date: ");
    fwrite($fi, $date);
    fwrite($fi, " | ");
    fwrite($fi, "Time: ");
    fwrite($fi, $hour);
    fwrite($fi, " | ");
    fwrite($fi, "Total_Size: ");
    fwrite($fi, $max);
    fwrite($fi, " | ");
    fwrite($fi, "Free_Size: ");
    fwrite($fi, $free);
    fwrite($fi, " | ");
    fwrite($fi, "HWM: ");
    fwrite($fi, $hwm);
    fwrite($fi, " | ");
    fwrite($fi, "Exceeded: ");
    fwrite($fi, "NO");
    fwrite($fi, "\r\n");

    fclose($fi);
}

// Valores con PHP extraidos de la base de datos.
$response = [
    "hours" => $hour,
    "usedMemory" => $used,
    "hwm" => $hwm,
    "date" => $date,
    "totalSize" => $max,
    "freeMem" => $free,
    "random" => $random,
];

echo json_encode($response);
