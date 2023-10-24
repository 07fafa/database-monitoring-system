<!DOCTYPE html>
<html>

<head>
    <title>User Monitor</title>
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
            <h1>User Monitor</h1>
            <div class="text-right">
                <form name="formSelectUser" method="post" action="#">
                    <select class="custom-select selectAltura" id="selectUser" name="selectUser">
                        <?php
                        $sqlarea = '$sqlarea';
                        $num = 0;

                        session_start();
                        $conn = oci_connect($_SESSION['username'], $_SESSION['password'], $_SESSION['db']);

                        $sql = "SELECT au.username
                        FROM v$sqlarea vs, dba_users au   
                        WHERE (au.user_id(+)=vs.parsing_user_id)
                        AND (executions >= 1)
                        AND au.account_status = 'OPEN'
                        GROUP BY au.username
                        ORDER BY au.username ASC";

                        $stid = oci_parse($conn, $sql);
                        oci_execute($stid);

                        echo "<option value='$num' selected disabled>Filter by user</option>";
                        echo "<option value='$num'>All Users</option>";

                        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                            $username = $row["USERNAME"];
                            echo "<option value='$username'>";
                            echo $username;
                            echo "</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" class="btn btn-warning" value="Search" />
                    <a href="../DBA_Project/menu.php">
                        <button type="button" class="btn btn-light">Back</button>
                    </a>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <table id="tableUsers" class="table table-dark table-striped">
            <thead>
                <tr class="center">
                    <th>User</th>
                    <th>Sentence SQL</th>
                    <th>Date/Hour</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_POST['selectUser'])) {

                    $selectedUser = $_POST['selectUser'];
                    if ($selectedUser == 0) {
                        header("location:userMonitor.php");
                    }

                    $sql = "SELECT au.username, vs.sql_text, 
                    to_char(to_date(vs.first_load_time,
                    'YYYY-MM-DD/HH24:MI:SS'),'MM/DD/YY  HH24:MI:SS') first_load_time
                    FROM v$sqlarea vs, dba_users au   
                    WHERE (au.user_id(+)=vs.parsing_user_id)
                    AND (executions >= 1)
                    AND au.account_status = 'OPEN'
                    AND au.username = '$selectedUser'
                    ORDER BY first_load_time DESC";
                } else {

                    $sql = "SELECT au.username, vs.sql_text, 
                    to_char(to_date(vs.first_load_time,
                    'YYYY-MM-DD/HH24:MI:SS'),'MM/DD/YY  HH24:MI:SS') first_load_time
                    FROM v$sqlarea vs, dba_users au   
                    WHERE (au.user_id(+)=vs.parsing_user_id)
                    AND (executions >= 1)
                    AND au.account_status = 'OPEN'
                    ORDER BY first_load_time DESC";
                }

                $stid = oci_parse($conn, $sql);
                oci_execute($stid);
                while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    echo "<tr class='center'>\n";

                    echo "<td>";
                    echo $row["USERNAME"];
                    echo "</td>\n";
                    echo "<td>";
                    echo $row["SQL_TEXT"];
                    echo "</td>\n";
                    echo "<td>";
                    echo $row["FIRST_LOAD_TIME"];
                    echo "</td>\n";

                    echo "</tr>\n";
                }

                oci_free_statement($stid);
                oci_close($conn);
                ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex align-items-center justify-content-center copyright-header">
        <h6>Copyright&copy; 2022 - CyberianData Enterprise</h6>
    </div>
</body>

</html>