<?php
require("classes/Artikal.php");
require("utilities/DBUtils.php");

session_start();
date_default_timezone_set("Europe/Belgrade");

// Inicijalizuje vrednosti u sesiji
if (!isset($_SESSION["ubaceno"]))
    $_SESSION["ubaceno"] = 0;

if (!isset($_SESSION["izabrano"]))
    $_SESSION["izabrano"] = array();

// Kreira novi alat za rad sa bazom
$utils = new DBUtils();
// Inicijalizuje niz artikala
$artikli = array();

// Popunjava niz podacima iz baze podataka
function fill_array()
{
    global $utils;
    global $artikli;
    $table = $utils->select_artikli();

    // Za svaki red kreira klasu i ubacuje je u niz artikala
    foreach ($table as $row) {
        $artikal = new Artikal();
        $artikal->set_sifra($row[COL_ARTIKLI_SIFRA]);
        $artikal->set_naziv($row[COL_ARTIKLI_NAZIV]);
        $artikal->set_cena($row[COL_ARTIKLI_CENA]);
        $artikal->set_kolicina($row[COL_ARTIKLI_KOLICINA]);
        $artikli[] = $artikal;
    }
}

function alert($msg)
{
    echo "<script>";
    echo "alert(\"{$msg}\");";
    echo "</sctipt>";
}

// Postavlja vrednost kolacica
function set_cookie($msg)
{
    // Dodaje poruku na trenutni kolacic, inace obavesnava korisnika o gresci
    if (isset($_COOKIE["istorija"])) {
        $istorija = "{$_COOKIE["istorija"]}{$msg}";
        setcookie("istorija", $istorija, time() + 24 * 60 * 60);
    } else
        setcookie("istorija", $msg, time() + 24 * 60 * 60);
}

// Ubacuje novac u automat
function ubaci_novac()
{
    $datum = date("Y-m-d H:i:s");
    $novac = htmlspecialchars($_POST["novac"]);

    // Cuva ubacen novac u sesiju ako je string broj, inace obavesnava korisnika o gresci
    if (preg_match("/^\d+$/", $novac)) {
        $novac = (int) $novac;

        // Cuva novac u sesiju ako je njena vrednost 10, 20, 50, 100 ili 200, inace obavesnava korisnika o gresci
        if (in_array($novac, array(10, 20, 50, 100, 200))) {
            $_SESSION["ubaceno"] += $novac;
            set_cookie("{$datum} Ubačeno: {$novac} DIN\n");
        } else
            alert("Novac mora biti u vrednostima 10, 20, 50, 100 ili 200!");
    } else
        alert("Uneta vrednost mora biti broj!");
}

// Bira artikal sa unetom sifrom
function izaberi_artikal()
{
    global $artikli;
    $datum = date("Y-m-d H:i:s");
    $sifra = htmlspecialchars($_GET["sifra"]);

    // Cuva izabran artikal u sesiju ako je string broj, inace obavesnava korisnika o gresci
    if (preg_match("/^\d+$/", $sifra)) {
        $sifra = (int) $sifra;
        $izabran = null;

        // Pronalazi artikal sa tom sifrom
        foreach ($artikli as $artikal) {
            // Bira artikal sa istom sifrom
            if ($artikal->get_sifra() == $sifra)
                $izabran = $artikal;
        }

        array_push($_SESSION["izabrano"], $izabran);
        set_cookie("{$datum} Izabrano: {$izabran->get_naziv()}\n");
    } else
        alert("Uneta vrednost mora biti broj!");
}

// Placa izabrane artikle
function plati()
{
    global $utils;
    global $izabrano;

    // Menja kolicinu svakog izabranog artikla
    foreach ($izabrano as $izabran)
        $utils->update_artikal($izabran->get_sifra(), $izabran->get_kolicina() - 1);

    return print_racun();
}

// Stampa racun
function print_racun()
{
    global $ubaceno;
    global $izabrano;

    $total = 0;

    $datum = date("Y-m-d h:i:s");
    $msg = "Datum: {$datum}\n";

    $msg .= "Ubačeno: {$ubaceno} DIN\n";

    // Stavlja izabrane artikle u racun
    foreach ($izabrano as $izabran) {
        $total += $izabran->get_cena();

        $naziv = $izabran->get_naziv();
        $cena = $izabran->get_cena();
        $msg .= sprintf("Izabrano: %-50s | %03d DIN\n", $naziv, $cena);
    }

    $msg .= sprintf("Ukupno: %03d DIN\n", $total);

    $kusur = $ubaceno - $total;

    if ($kusur <= 80) {
        $msg .= "Kusur: {$kusur} DIN = ";

        $dvadesetke = intdiv($kusur, 20);
        $kusur -= $dvadesetke * 20;
        $desetke = intdiv($kusur, 10);
        $kusur -= $desetke * 10;
        $petice = intdiv($kusur, 5);
        $kusur -= $petice * 5;
        $dvojke = intdiv($kusur, 2);
        $kusur -= $dvojke * 2;
        $jedinice = intdiv($kusur, 1);
        $kusur -= $jedinice * 1;
        $msg .= sprintf("%d * 20 DIN + %d * 10 DIN + %d * 5 DIN + %d * 2 DIN + %d * 1 DIN\n", $dvadesetke, $desetke, $petice, $dvojke, $jedinice);
    } else
        $msg .= "Ne vraća kusur!";

    $filename = "racun " . date("Y-m-d") . " " . date("h-i-s") . ".txt";

    // Upisuje poruku u novi fajl
    if (!file_exists($filename)) {
        $racun = fopen($filename, "w");

        // Vraca false ako ako dodje do greske tokom upisa
        if (fwrite($racun, $msg) === FALSE)
            return false;

        fclose($racun);
        return true;
    }
}

function generate_table()
{
    global $artikli;

    // Za svaki red
    for ($i = 0; $i < 6; $i++) {
        echo "<tr>";

        // Za svaku kolonu nesto
        for ($j = 0; $j < 9; $j++) {
            $artikal = $artikli[$i * 9 + $j];
            $filename = str_replace(" ", "-", $artikal->get_naziv()) . ".jpg";
            echo "<td colspan=\"2\"><img src=\"images/{$filename}\"></td>";
        }

        echo "</tr>";
        echo "<tr>";

        // Za svaku kolonu nesto
        for ($j = 0; $j < 9; $j++) {
            $artikal = $artikli[$i * 9 + $j];
            echo "<td>{$artikal->get_sifra()}</td>";
            echo "<td>{$artikal->get_cena()} DIN</td>";
        }

        echo "</tr>";
    }
}

// Popunjava niz podacima iz baze podataka
fill_array();

if (!isset($_POST["plati"])) {
    // Ubacuje novac u automat ako postoji vrednost na kljucu "novac"
    if (isset($_POST["novac"]))
        ubaci_novac();

    // Bira artikal sa automata ako postoji vrednost na kljucu "sifra"
    if (isset($_GET["sifra"]))
        izaberi_artikal();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>AUTOMAT</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <div id="header">
        <h1>AUTOMAT</h1>
        <a href="login.php">Prijava</a>
    </div>
    <div id="main">
        <table>
            <?php
            if (isset($artikli))
                generate_table()
            ?>
        </table>
        <div id="forms">
            <form action="index.php" method="post">
                <label>Ubacite novac u vrednostima 10, 20, 50, 100 ili 200:</label>
                <br>
                <input type="text" name="novac" required>
                <input type="submit" value="Ubaci">
            </form>
            <br>
            <form action="index.php" method="get">
                <label>Unesite šifru artikla:</label>
                <br>
                <input type="text" name="sifra" required>
                <input type="submit" value="Unesi">
            </form>
            <br>
            <form action="index.php" method="post">
                <input type="submit" name="plati" value="Kupi">
            </form>
        </div>
    </div>
    <div id="footer">
        <?php
        if (isset($_POST["plati"])) {
            $izabrano = $_SESSION["izabrano"];
            $ubaceno = $_SESSION["ubaceno"];

            if (plati()) {
                echo "Uspesna kupovina<br>";
                session_destroy();
            } else {
                echo "Neuspesna kupovina<br>";
                session_destroy();
            }
        }
        ?>
    </div>
</body>

</html>