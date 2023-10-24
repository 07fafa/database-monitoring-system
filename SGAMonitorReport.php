<!DOCTYPE html>
<html>

<head>
    <title>HWM Exceeded Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="shortcut icon" href="../DBA_Project/img/CyberianData.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="card-header">
            <h1>
                HWM Exceeded Report
            </h1>
        </div>
    </div>
    <div class="container">
        <table id="tableUsers" class="table table-dark table-striped">
            <thead>
                <tr class="center">
                    <th>Date/Time</th>
                </tr>
            </thead>
            <?php
            $sqlarea = '$sqlarea';

            session_start();
            $conn = oci_connect($_SESSION['username'], $_SESSION['password'], $_SESSION['db']);

            if (isset($_POST['alert'])) {
                $alert = $_POST['alert'];

                $sql1 = "SELECT to_char(to_date(vs.first_load_time,
                'YYYY-MM-DD/HH24:MI:SS'),'MM/DD/YY  HH24:MI:SS') date_time
                FROM v$sqlarea vs, dba_users au   
                WHERE to_char(to_date(vs.first_load_time,
                'YYYY-MM-DD/HH24:MI:SS'),'MM/DD/YY HH24:MI:SS') = '$alert'
                AND (au.user_id(+)=vs.parsing_user_id)
                AND (executions >= 1)
                AND au.account_status = 'OPEN'
                GROUP BY au.username, to_char(to_date(vs.first_load_time, 'YYYY-MM-DD/HH24:MI:SS'),'MM/DD/YY  HH24:MI:SS')";

                $stid = oci_parse($conn, $sql1);
                oci_execute($stid);
                while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    echo "<tbody>\n";
                    echo "<tr class='center'>\n";
                    echo "<td>";
                    echo $row["DATE_TIME"];
                    echo "</td>";
                    echo "</tr>\n";
                    echo "</tbody>\n";
                }
            }
            ?>
            <thead>
                <tr class="center">
                    <th>User</th>
                    <th>Sentence SQL</th>
                </tr>
            </thead>
            <tbody class="tbodySGAMonitorReport">
                <?php
                if (isset($_POST['alert'])) {
                    $alert = $_POST['alert'];

                    $sql2 = "SELECT au.username, vs.sql_text
                    FROM v$sqlarea vs, dba_users au   
                    WHERE to_char(to_date(vs.first_load_time,
                    'YYYY-MM-DD/HH24:MI:SS'),'MM/DD/YY HH24:MI:SS') = '$alert'
                    AND (au.user_id(+)=vs.parsing_user_id)
                    AND (executions >= 1)
                    AND au.account_status = 'OPEN'
                    ORDER BY first_load_time DESC";

                    $stid = oci_parse($conn, $sql2);
                    oci_execute($stid);
                    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                        echo "<tr>\n";
                        echo "<td class='center'>";
                        echo $row["USERNAME"];
                        echo "</td>";
                        echo "<td><font color='#f10628'>";
                        echo $row["SQL_TEXT"];
                        echo "</td>";
                        echo "</tr>\n";
                    }
                    oci_free_statement($stid);
                    oci_close($conn);
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex align-items-center justify-content-center copyright-header">
        <h6>Copyright&copy; 2022 - CyberianData Enterprise</h6>
    </div>
</body>

</html>