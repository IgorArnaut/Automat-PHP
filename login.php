<?php
// Nakon prijave otvara stranicu za podesavanje automata
function login()
{
    $username = $_POST["user"];
    $password = $_POST["pass"];

    // Redirektuje ka stranici za podesavanja, inace obavestava korisnika o gresci
    if ($username == "admin" && $password == "admin")
        header("Location: " . "settings.php");
    else if ($username == "admin" && $password != "admin")
        echo "<script>alert(\"Pogresna lozinka!\");</script>";
    else
        echo "<script>alert(\"Pogresno korisnicko ime!\");</script>";
}

// Poziva login ako su postoje vrednosti u kljucevima "user" i "pass"
if (isset($_POST["user"]) && isset($_POST["pass"]))
    login();
?>

<!DOCTYPE html>
<html>

<head>
    <title>PRIJAVA</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <h1>PRIJAVA</h1>

    <form action="" method="post">
        <p>Korisničko ime <input type="text" name="user" required></p>
        <p>Lozinka <input type="password" name="pass" required></p>
        <p><input type="submit" value="Prijavi se"></p>
    </form>
</body>

</html>