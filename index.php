<?php

@include 'db_config.php';

session_start();

if (isset($_POST['login-submit'])) {

    $user_id = pg_escape_string($conn, $_POST['login-user-id']);
    $pass = md5($_POST['login-password']);
    $error = array();

    if (empty($user_id)) {
        $error[] = "User ID is required.";
    }

    if (empty($pass)) {
        $error[] = "Password is required.";
    }

    $select = " SELECT * FROM user_tbl WHERE user_id = '$user_id' AND password = '$pass' ";

    $result = pg_query($conn, $select);

    if (pg_num_rows($result) > 0) {

        $row = pg_fetch_assoc($result);

        if ($row['user_type'] == 'instructor') {

            $_SESSION['instructor_name'] = $row['user_id'];
            header('location:index_admin.php');
            exit;
        } elseif ($row['user_type'] == 'student') {

            $_SESSION['student_name'] = $row['user_id'];
            header('location:index_user.php');
            exit;
        }
    } else {
        $error[] = 'Incorrect email or password!';
    }
} elseif (isset($_POST['register-submit'])) {

    $user_id = pg_escape_string($conn, $_POST['reg-user-id']);
    $email = pg_escape_string($conn, $_POST['reg-email']);
    $pass = md5($_POST['reg-password']);
    $cpass = md5($_POST['reg-password-confirm']);
    $user_type = $_POST['user_type'];

    $error = array();

    if (empty($user_id)) {
        $error[] = "User ID is required.";
    }

    if (empty($email)) {
        $error[] = "Email is required.";
    }

    if (empty($pass)) {
        $error[] = "Password is required.";
    }

    if (empty($cpass)) {
        $error[] = "Confirm Password is required.";
    }

    $select = " SELECT * FROM user_tbl WHERE user_id = '$user_id'";

    $result = pg_query($conn, $select);

    if (pg_num_rows($result) > 0) {

        $error[] = 'User ID already exists!';
    } else {

        if ($pass != $cpass) {
            $error[] = 'Passwords do not match.';
        } else {
            $insert = "INSERT INTO user_tbl(user_id, email, password, user_type) VALUES('$user_id','$email','$pass','$user_type')";
            pg_query($conn, $insert);
            $error[] = 'Successful! please log in.';
            echo '<script type="text/JavaScript"> 
            document.querySelector("#register-form").classList.toggle("visually-hidden");
            document.querySelector("#login-form").classList.toggle("visually-hidden");
            </script>';
        }
    }
}

if (isset($error) && count($error) > 0) {
    echo '<div class="alert alert-dark error" role="alert">';
    foreach ($error as $errorMsg) {
        echo '<div class="error-msg">' . $errorMsg . '</div>';
    }
    echo '</div>';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/icon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="assets/icon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Login | OJT RECORDS</title>
    <style>
        body {
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .btn-ghost {
            background-color: transparent !important;
        }

        .error {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        .col-8 img {
            width: 100%;
            height: 100dvh;
            object-fit: cover;
        }

        form {
            width: 500px;
        }
    </style>
</head>

<body>
    <div class="container-fluid m-0 p-0">
        <div class="row">
            <div class="col-8 text-white position-relative">
                <img src="./assets/bg.png" alt="hey">
                <div class="position-absolute top-50 start-50 translate-middle text-center">
                    <h1 class="display-1">OJT RECORDS</h1>
                    <p class="lead">View OJT records in one place.</p>
                </div>
            </div>

            <div class="col-4 d-flex justify-content-center align-items-center">
                <div>
                    <form id="login-form" method="post">
                        <h1 class="display-3 text-center mb-3">Login</h1>
                        <div class="mb-3">
                            <label for="login-user-id" class="form-label">User ID</label>
                            <input type="text" class="form-control" id="login-user-id" name="login-user-id" required>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="login-password" name="login-password" required>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <button type="button" class="btn btn-ghost" id="register-btn">Register</button>
                            <button type="submit" class="btn btn-secondary" name="login-submit">Login</button>
                        </div>
                    </form>

                    <form class="mx-3 visually-hidden" id="register-form" method="post">
                        <h1 class="display-3 text-center mb-3">Register</h1>
                        <div class="mb-3">
                            <label for="reg-user-id" class="form-label">User ID</label>
                            <input type="text" class="form-control" id="reg-user-id" name="reg-user-id" autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label for="reg-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="reg-email" name="reg-email" aria-describedby="emailHelp" autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label for="reg-password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="reg-password" name="reg-password" required>
                        </div>
                        <div class="mb-3">
                            <label for="reg-password-confirm" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="reg-password-confirm" name="reg-password-confirm" required>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" name="user_type" aria-label="Default select example">
                                <option selected value="student">Student</option>
                                <option value="instructor">Instructor</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-ghost" id="login-btn">Login</button>
                            <button type="submit" class="btn btn-secondary" name="register-submit">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./js/index.js" defer></script>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>