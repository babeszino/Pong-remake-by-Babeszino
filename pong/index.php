<?php
require_once "config.php";
ob_start();
session_start();

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(empty(trim($_POST["username"]))) {
        $username_err = "Your username";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Your password";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;
    
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
    
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
    
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
    
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
    
                            header("location: /pong/jatek.html");
                        } else {
                            $password_err = "Password is incorrect";
                        }
                    }
                } else {
                    $username_err = "Username is incorrect";
                }
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
    <title> Login </title>
    <link rel = "stylesheet" type = "text/css" href = "login.css">
</head>

<body>
    <div class = "wrapper">
        <h2> <b> Log in </b> </h2>
        <p> <i> Please fill out the following brackets to log in! </i></p>
        <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <div class = "form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label> Username </label>
                <input type = "text" name = "username" class = "form-controll" value = "<?php echo $username; ?>">
                <span class = "help-block"><?php echo $username_err; ?> </span>
            </div>

            <div class = "form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label> Password </label>
                <input type = "password" name = "password" class = "form-controll">
                <span class = "help-block"><?php echo $password_err; ?> </span>
            </div>

            <div class = "form-group">
                <input type = "submit" class = "btn btn-primary" value = "Login">
            </div>

            <p> <i> No account yet? </i> <a href = "register.php"> Sign up now! </a></p>
        </form>
        
        <div class = "form-group">
            <a href = "leaderboard.php" class = "btn btn-leaderboard"> Go to Leaderboard </a>
        </div>

        <div class = "form-group">
            <a href = "github cucc" class = "btn btn-github"> GitHub repository // source code </a>
        </div>
    </div>
</body>

</html>