<!DOCTYPE html>
<html lang="en">

<head>
    <title>Redo Logs Monitor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="shortcut icon" href="../DBA_Project/img/CyberianData.png">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="card-header">
            <h1>Redo Logs Monitor</h1>
            <div class="left">
                <?php
                $ARCHIVED_LOG = '$ARCHIVED_LOG';

                session_start();
                $conn = oci_connect($_SESSION['username'], $_SESSION['password'], $_SESSION['db']);

                $sql = "SELECT status from v$ARCHIVED_LOG
                ORDER BY status ASC
                FETCH FIRST 1 ROWS ONLY";

                $stid = oci_parse($conn, $sql);
                oci_execute($stid);

                while ($Row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $status = $Row["STATUS"];
                }

                //$status = "B";

                if ($status == "A") {
                    echo '<button type="button" class="btn btn-success" disabled>Archive Mode: ON</button>';
                } else {
                    echo '<button type="button" class="btn btn-danger" disabled>Archive Mode: OFF</button>';
                }
                ?>
            </div>
            <div class="right text-right">
                <form action="redoLogsMonitorRequest.php" method="post">
                    <input type="submit" class="btn btn-warning" Value="Switch" />
                    <a href="../DBA_Project/menu.php">
                        <button type="button" class="btn btn-light">Back</button>
                    </a>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-dark table-striped">
            <thead>
                <tr class="center">
                    <th>Group</th>
                    <th>Status</th>
                    <th>Multiplexed</th>
                    <th>Block Size</th>
                    <th>Rate of Change</th>
                </tr>
            </thead>

            <tbody class="container-redolog">
                <?php
                $arrGroups = array();

                //-------- GROUP - CURRENT ---------
                $group = 'GROUP#';
                $log = '$log';

                $sql = "SELECT STATUS, $group FROM v$log WHERE STATUS = 'CURRENT'";

                $stid = oci_parse($conn, $sql);
                oci_execute($stid);

                while ($Row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $groupCurrent = $Row["GROUP#"];
                }

                //-------- GROUP - MEMBERS ---------
                $logfile = '$logfile';

                $sql = "SELECT $group FROM v$logfile 
                ORDER BY $group ASC";

                $stid = oci_parse($conn, $sql);
                oci_execute($stid);

                while ($Row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    array_push($arrGroups, $Row["GROUP#"]);
                }

                //-------- ALL INFO ---------
                $sql = 'SELECT * FROM v$log';

                $stid = oci_parse($conn, $sql);
                oci_execute($stid);

                while ($Row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    $date1  = new DateTime("22-10-28");
                    $date2 = new DateTime("22-10-29");
                    $diff = $date1->diff($date2);

                    echo "<tr class='center'>\n";

                    if ($Row["STATUS"] == "CURRENT") {
                        echo "<td>";
                        echo $Row["GROUP#"];
                        echo "</td>\n";
                        echo "<td><font color='#f10628'>";
                        echo '<img src ="../DBA_Project/img/DBR.png" width="150px" height="150px">';
                        echo "</td>\n";
                        echo "<td>";
                        $cant = 0;
                        for ($i = 0; $i < sizeof($arrGroups); $i++) {
                            if ($Row["STATUS"] == "CURRENT" && $arrGroups[$i] == $Row["GROUP#"]) {
                                $cant++;
                            }
                        }
                        $cant -= 1;
                        if ($cant == 0) {
                            echo "<p>NO</p>\n";
                            echo '<img src ="../DBA_Project/img/DBEN.png" width="100px" height="100px">';
                        } else {
                            echo "<p>YES | TOTAL: $cant</p>\n";
                            echo '<img src ="../DBA_Project/img/DBER.png" width="100px" height="100px">';
                        }
                        echo "</td>\n";
                        echo "<td>";
                        echo $Row["BLOCKSIZE"];
                        echo "</td>\n";
                        echo "<td>";
                        echo $diff->days;
                        echo "</td>\n";
                    } else {
                        echo "<td>";
                        echo $Row["GROUP#"];
                        echo "</td>\n";
                        echo "<td><font color='#f10628'>";
                        echo '<img src ="../DBA_Project/img/DBA.png" width="150px" height="150px">';
                        echo "</td>\n";
                        echo "<td>";
                        $cant = 0;
                        for ($i = 0; $i < sizeof($arrGroups); $i++) {
                            if ($arrGroups[$i] == $Row["GROUP#"]) {
                                $cant++;
                            }
                        }
                        $cant -= 1;
                        if ($cant == 0) {
                            echo "<p>NO</p>\n";
                            echo '<img src ="../DBA_Project/img/DBEN.png" width="100px" height="100px">';
                        } else {
                            echo "<p>YES | TOTAL: $cant</p>\n";
                            echo '<img src ="../DBA_Project/img/DBEA.png" width="100px" height="100px">';
                        }
                        echo "</td>\n";
                        echo "<td>";
                        echo $Row["BLOCKSIZE"];
                        echo "</td>\n";
                        echo "<td>";
                        echo $diff->days;
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
    <!-- <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>GROUP#</th>
                    <th>MEMBER</th>
                </tr>
                <!-- ?php 
                $sql = 'SELECT * FROM v$logfile';

                    $stid = oci_parse($conn, $sql);
                    oci_execute($stid);
                $sql = 'SELECT * FROM v$logfile';

                $stid = oci_parse($conn, $sql);
                oci_execute($stid);

                while ($Row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {

                    echo "<tr>\n";
                    echo "<td>";
                    echo $Row["GROUP#"];
                    echo "</td>\n";
                    echo "<td>";
                    echo $Row["MEMBER"];
                    echo "</td>\n";
                    echo "</tr>\n";
                }

                
                ?>
            </thead>
        </table> -->
    <div class="d-flex align-items-center justify-content-center copyright-header">
        <h6>Copyright&copy; 2022 - CyberianData Enterprise</h6>
    </div>
</body>

</html>