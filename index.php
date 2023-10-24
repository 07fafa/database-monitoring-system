<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="shortcut icon" href="../DBA_Project/img/CyberianData.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="../DBA_Project/js/jquery-1.3.2.min.js"></script>
</head>

<body>
    <div class="container-login">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">
                    <h3>Cyberian Data</h3>
                    <div class="d-flex justify-content-end social_icon">
                        <span>
                            <img src="../DBA_Project/img/CyberianData.png" width="100" height="100">
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="connect.php" method="post">
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Username" name="username">
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control" placeholder="Password" name="password">
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-bars" aria-hidden="true"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="DB Name" name="db">
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-asterisk" aria-hidden="true"></i></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="IP Address" name="ip">
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-bullseye" aria-hidden="true"></i></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Port" name="port">
                        </div>
                        <div class="form-group text-center">
                            <input type="submit" value="Login" class="btn login_btn">
                        </div>
                    </form>
                </div>
            </div>
            <div class="container bottom">
                <div class="d-flex align-items-center justify-content-center copyright-header">
                    <h6>Copyright&copy; 2022 - CyberianData Enterprise</h6>
                </div>
            </div>
        </div>
    </div>
</body>

</html>