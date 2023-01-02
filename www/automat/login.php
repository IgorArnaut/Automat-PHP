<?php
// Nakon prijave otvara stranicu za podesavanje automata
function login()
{
    $username = $_POST["user"];
    $password = $_POST["pass"];

    if ($username == "admin" && $password == "admin")
        header("Location: " . "settings.php");
}

// Poziva login ako su vrednosti "user" i "pass" u POST nizu
if (isset($_POST["user"]) && isset($_POST["pass"]))
    login();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Prijava</title>
</head>

<body>
    <h1>Prijava</h1>

    <form action="" method="post">
        <p>Korisničko ime <input type="text" name="user"></p>
        <p>Lozinka <input type="password" name="pass"></p>
        <p><input type="submit" value="Prijavi se"></p>
    </form>
</body>

</html>