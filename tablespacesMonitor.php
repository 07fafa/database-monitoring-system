<!DOCTYPE html>

<head>
    <title>Tablespaces Monitor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="shortcut icon" href="../DBA_Project/img/CyberianData.png">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.js"></script>
    <script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>
    <script type="text/javascript" src="../DBA_Project/js/tablespacesMonitor.js"></script>
</head>

<body>
    <div class="container">
        <div class="card-header">
            <h1>Tablespaces Monitor</h1>
            <div class="text-right">
                <a href="../DBA_Project/tablespacesMonitor.php">
                    <button type="button" class="btn btn-warning">Refresh</button>
                </a>
                <a href="../DBA_Project/menu.php">
                    <button type="button" class="btn btn-light">Back</button>
                </a>
            </div>
        </div>
        <div class="leftTablespaces">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th class="center">
                            <h5>Tablespaces Total Size (MB)</h5>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <canvas id="myChart1"></canvas>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="rightTablespaces">
            <table class="table table-dark table-striped">
                <thead>
                    <tr class="center">
                    </tr>
                </thead>

                <body>
                    <tr class="center">
                        <td>
                            <canvas id="myChart2"></canvas>
                        </td>
                    </tr>
                </body>
            </table>
            <table class="table table-dark table-striped">
                <thead>
                    <tr class="center">
                        <th>
                            Tablespace
                        </th>
                        <th>
                            HWM (MB)
                        </th>
                        <th>
                            Remain
                        </th>
                    </tr>
                </thead>
                <tbody class="container-tablespace">
                    <?php
                    $arrName = array();
                    $arrHWM = array();
                    $arrUsed = array();
                    $arrFree = array();
                    $arrDate = array();
                    $arrDays = array();
                    $arrGrowth = array();
                    $arrRemainingDays = array();

                    session_start();
                    $conn = oci_connect($_SESSION['username'], $_SESSION['password'], $_SESSION['db']);

                    //--------- Tablespace ------------
                    $sql = "SELECT Tablespace_name,
                        sum(bytes)/1024/1024 TOTAL_MB
                        FROM Dba_data_files
                        GROUP BY Tablespace_name
                        ORDER BY Tablespace_name ASC";

                    $stid = oci_parse($conn, $sql);
                    oci_execute($stid);

                    while ($row1 = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                        array_push($arrName, $row1["TABLESPACE_NAME"]);
                    }

                    //------------ HWM ------------
                    $sql = "SELECT Tablespace_name,
                    sum(bytes)/1024/1024 TOTAL_MB
                    FROM Dba_data_files
                    GROUP BY Tablespace_name
                    ORDER BY Tablespace_name ASC";

                    $stid = oci_parse($conn, $sql);
                    oci_execute($stid);

                    while ($row2 = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                        $auxHWM = ($row2["TOTAL_MB"] * 0.85);
                        array_push($arrHWM, $auxHWM);
                    }

                    //------------ USED ------------
                    $sql = "SELECT Tablespace_name,
                    sum(bytes)/1024/1024 used_mb
                    FROM Dba_extents
                    GROUP BY Tablespace_name
                    ORDER BY Tablespace_name ASC";

                    $stid = oci_parse($conn, $sql);
                    oci_execute($stid);

                    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                        array_push($arrUsed, $row["USED_MB"]);
                    }

                    //------------ FREE ------------
                    $sql = "SELECT tablespace_name,
                    sum(bytes)/1024/1024 FREE_MB
                    FROM dba_free_space
                    WHERE tablespace_name NOT LIKE 'TEMP%'
                    GROUP BY tablespace_name
                    ORDER BY Tablespace_name ASC";

                    $stid = oci_parse($conn, $sql);
                    oci_execute($stid);

                    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                        array_push($arrFree, $row["FREE_MB"]);
                    }

                    //------------ REMAINING DAYS ------------
                    $Datafile = '$Datafile';

                    $sql = "SELECT ddf.Tablespace_name, TO_CHAR(df.creation_time, 'YY-MM-DD HH24:MI:SS') CREATE_TABLESPACE,
                    SUM(df.bytes)/1024/1024 USED
                    FROM v$Datafile df
                    INNER JOIN  Dba_data_files ddf ON ddf.file_name = df.name
                    GROUP BY ddf.Tablespace_name, TO_CHAR(creation_time, 'YY-MM-DD HH24:MI:SS')
                    ORDER BY ddf.Tablespace_name ASC";

                    $stid = oci_parse($conn, $sql);
                    oci_execute($stid);

                    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                        array_push($arrDate, $row["CREATE_TABLESPACE"]);
                    }

                    //-------- DAYS THAT THE TABLESPACE HAS BEEN USED ------
                    for ($i = 0; $i < sizeof($arrDate); $i++) {
                        $createDateTablespace = new DateTime($arrDate[$i]);
                        $currentDate = new DateTime('now');

                        $interval = $createDateTablespace->diff($currentDate);
                        $days = $interval->days;

                        array_push($arrDays, $days);
                    }

                    //-------- DAILY GROWTH ------
                    for ($i = 0; $i < sizeof($arrUsed); $i++) {
                        $auxDailyGrowth = ($arrUsed[$i] / $arrDays[$i]);
                        array_push($arrGrowth, $auxDailyGrowth);
                    }

                    //-------- REMAINING DAYS ------
                    for ($i = 0; $i < sizeof($arrGrowth); $i++) {
                        $remainingDays = ($arrFree[$i] / $arrGrowth[$i]);
                        array_push($arrRemainingDays, round($remainingDays));
                    }

                    for ($i = 0; $i < sizeof($arrName); $i++) {
                        echo "<tr class='center'>\n";

                        echo "<td>";
                        echo $arrName[$i];
                        echo "</td>\n";
                        if ($arrUsed[$i] >= $arrHWM[$i]) {
                            echo "<td><font color='#f10628'>";
                            echo $arrHWM[$i];
                            echo "</td>\n";
                            echo "<td><font color='#f10628'>";
                            echo '0 days';
                            echo "</td>\n";
                        } else if ($arrRemainingDays[$i] < 15) {
                            echo "<td>";
                            echo $arrHWM[$i];
                            echo "</td>\n";
                            echo "<td><font color='#f10628'>";
                            echo $arrRemainingDays[$i] . ' days';
                            echo "</td>\n";
                        } else {
                            echo "<td>";
                            echo $arrHWM[$i];
                            echo "</td>\n";
                            echo "<td>";
                            echo $arrRemainingDays[$i] . ' days';
                            echo "</td>\n";
                        }

                        echo "</tr>\n";
                    }

                    oci_free_statement($stid);
                    oci_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
        <div>

        </div>
        <div class="container bottom">
            <div class="d-flex align-items-center justify-content-center copyright-header">
                <h6>Copyright&copy; 2022 - CyberianData Enterprise</h6>
            </div>
        </div>
    </div>
</body>

</html>