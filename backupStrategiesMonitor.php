<!DOCTYPE html>
<html lang="en">

<head>
    <title>Backup Strategies Monitor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/styleStrategies.css">
    <link rel="shortcut icon" href="../DBA_Project/img/CyberianData.png">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="card-header">
            <h1>Backup Strategies Monitor</h1>
        </div>
        <div class="table-strategy-margin-top row abs-center">
            <div class="col-md-6">
                <div class="d-flex justify-content-center align-items-center mt-5">
                    <div class="card">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item text-center">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Full</a>
                            </li>
                            <li class="nav-item text-center">
                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Pacial</a>
                            </li>
                        </ul>
                        <div class="tab-content abs-center text-center" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="form px-4 pt-5" action="#">
                                    <select class="col-sm-8 custom-select selectAltura" id="selectBD" name="selectBD">
                                        <option value='0' selected disabled>Select Database</option>
                                    </select>
                                    <div class="col-10">
                                        <button type="button" class="btn btn-success position-archive" disabled>Archive Mode: ON</button>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox"> Include Tablespaces
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox"> Include Control File
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox"> Include Redo Logs
                                        </label>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-warning">Ok</button>
                                        <a href="../DBA_Project/menu.php">
                                            <button class="btn btn-danger">Cancel</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="form px-4 pt-4" action="#">
                                    <select class="col-sm-8 custom-select selectAltura position-input" id="selectBD" name="selectBD">
                                        <option value='0' selected disabled>Select Database</option>
                                    </select>
                                    <select class="col-sm-8 custom-select selectAltura" id="selectTablespace" name="selectTablespace">
                                        <option value='0' selected disabled>Select Tablespace</option>
                                    </select>
                                    <div class="col-10">
                                        <button type="button" class="btn btn-success position-archive" disabled>Archive Mode: ON</button>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox"> Include Control File
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox"> Include Redo Logs
                                        </label>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-warning">Ok</button>
                                        <a href="../DBA_Project/menu.php">
                                            <button class="btn btn-danger">Cancel</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container bottom">
                <div class="d-flex align-items-center justify-content-center copyright-header">
                    <h6>Copyright&copy; 2022 - CyberianData Enterprise</h6>
                </div>
            </div>
        </div>
</body>

</html>