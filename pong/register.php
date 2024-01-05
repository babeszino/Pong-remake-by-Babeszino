<?php

ob_start();

require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Must fill this!";        
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt,"s", $param_username);

            $param_username = trim($_POST["username"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Username is already in use";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "There is something wrong. Please, try again";
            }

            mysqli_stmt_close($stmt);
        }
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Must fill this!";
    } elseif (strlen(trim($_POST["password"])) < 3) {
        $password_err = "Atleast 3 characters!";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = "Must fill this!";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);

        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "The passwords don't match";
        }
    }

    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if (mysqli_stmt_execute($stmt)) {
                header("location: index.php");
            } else {
                echo "Something went wrong. Please try again later";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}

ob_end_flush();

?>

<!DOCTYPE html>
<html lang = "en">

<head>
    <meta charset = "UTF-8">
    <title> Register </title>
    <style type = "text/css">
        body { font: 14px sans-serif; }
        .wrapper { width: 350px: padding: 20px; }
    </style>
    <link rel = "stylesheet" type = "text/css" href = "register.css">
</head>

<body>
    <div class = "wrapper">
        <h2> Register </h2>
        <p> <i> Please fill this form to sign up! </i> </p>
        <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <div class = "form-group" <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label> Username </label>
                <input type = "text" name = "username" class = "form-control" value = "<?php echo $username; ?>">
                <span class = "help-block"> <?php echo $username_err; ?> </span>
            </div>

            <div class = "form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label> Password </label>
                <input type = "password" name = "password" class = "form-control" value = "<?php echo $password; ?>">
                <span class = "help-block"> <?php echo $password_err; ?> </span>
            </div>

            <div class = "form-group" <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label> Confirm password </label>
                <input type = "password" name = "confirm_password" class = "form-control" value = "<?php echo $confirm_password; ?>">
                <span class = "help-block"> <?php echo $confirm_password_err; ?> </span>
            </div>

            <div class = "form-group">
                <input type = "submit" class = "btn btn-primary" value = "Sign up">
                <input type = "reset" class = "btn btn-default" value = "Reset">
            </div>

            <p> <i> Already have an account? </i> <a href="index.php"> Log in here </a> </p>

            <div class = "form-group">
                <a href = "leaderboard.php" class = "btn btn-leaderboard"> Go to Leaderboard </a>
            </div>
        </form>
    </div>
</body>
</html>