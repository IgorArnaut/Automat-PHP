<?php
// Checks if user credentials are valid
function check_user($username, $password)
{
    if ($username == "admin" && $password == "admin")
        header("Location: " . "settings.php");
    else if ($username == "admin" && $password != "admin")
        echo "<script>alert(\"Wrong password!\");</script>";
    else
        echo "<script>alert(\"Wrong username!\");</script>";
}

// Redirects to settings page after logging in
function login()
{
    if (isset($_POST["user"]) && isset($_POST["pass"])) {
        $username = $_POST["user"];
        $password = $_POST["pass"];
        check_user($username, $password);
    }
}

login();
?>

<!DOCTYPE html>
<html>

<head>
    <title>LOG IN</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <h1>LOG IN</h1>

    <form action="" method="post">
        <p>Username <input type="text" name="user" required></p>
        <p>Password <input type="password" name="pass" required></p>
        <p><input type="submit" value="Prijavi se"></p>
    </form>
</body>

</html>