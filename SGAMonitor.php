<!DOCTYPE html>
<html lang="en">

<head>
    <title>SGA Memory Monitor</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="shortcut icon" href="../DBA_Project/img/CyberianData.png">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script type="text/javascript" src="../DBA_Project/js/SGAMonitor.js"></script>
</head>

<body>
    <div class="container">
        <div class="card-header">
            <h1>SGA Memory Monitor</h1>
            <div class="text-right">
                <a href="../DBA_Project/menu.php">
                    <button type="button" class="btn btn-light">Back</button>
                </a>
            </div>
        </div>
        <div class="leftSGA">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th class="center">
                            <h5>Real-Time Memory Usage</h5>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <canvas id="myChart"></canvas>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="rightSGA">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th class="center">
                            <h5>Alert ¡HWM Exceeded!</h5>
                        </th>
                    </tr>
                </thead>

                <body>
                    <tr>
                        <td id="formAlert"></td>
                    </tr>
                </body>
            </table>
        </div>
        <div class="container bottom">
            <div class="d-flex align-items-center justify-content-center copyright-header">
                <h6>Copyright&copy; 2022 - CyberianData Enterprise</h6>
            </div>
        </div>
    </div>
</body>

</html>