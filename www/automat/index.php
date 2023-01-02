<?php
require("classes/Artikal.php");
require("classes/DBUtils.php");
require("utilities/constants.php");

session_start();

function fill_table()
{
    global $utils;
    global $artikli;
    $table = $utils->select_artikli();

    foreach ($table as $row) {
        $artikal = new Artikal();
        $artikal->set_sifra($row[COL_ARTIKLI_SIFRA]);
        $artikal->set_naziv($row[COL_ARTIKLI_NAZIV]);
        $artikal->set_cena($row[COL_ARTIKLI_CENA]);
        $artikal->set_kolicina($row[COL_ARTIKLI_KOLICINA]);
        $artikli[] = $artikal;
    }
}

function ubaci_novac($trenutni)
{
    $novac = (int) $trenutni;


    if ($novac == 10 || $novac == 20 || $novac == 50 || $novac == 100 || $novac == 200) {
        $msg = date("Y-m-d H:i:s") . " Ubaceno: {$novac} DIN\n";

        if (isset($_COOKIE["istorija"])) {
            $istorija = $_COOKIE["istorija"];
            $istorija .= $msg;
            setcookie("istorija", $istorija, time() + 24 * 60 * 60);
        } else
            setcookie("istorija", $msg, time() + 24 * 60 * 60);

        $_SESSION["trenutni"] += $novac;
    } else
        echo "<script>alert(\"Ubacite novac u vrednostima (10, 20, 50, 100, 200)!\");</sctipt>";
}

function izaberi_artikal($izabran)
{
    global $artikli;
    $sifra = (int) $izabran;

    foreach ($artikli as $artikal) {
        if ($artikal->get_sifra() == $sifra) {
            $msg = date("Y-m-d H:i:s") . " Izabran: " . $artikal->get_naziv() . "\n";

            if (isset($_COOKIE["istorija"])) {
                $istorija = $_COOKIE["istorija"];
                $istorija .= $msg;
                setcookie("istorija", $istorija, time() + 24 * 60 * 60);
            } else
                setcookie("istorija", $msg, time() + 24 * 60 * 60);

            $_SESSION["izabrani"][] = $artikal;
        }
    }
}

function kupi()
{
    global $utils;
    $total = 0;
    $datum = date("Y-m-d H:i:s");

    $trenutni = $_SESSION["trenutni"];
    $izabrani = $_SESSION["izabrani"];

    $msg = "";
    $msg .= "Datum: {$datum}\n";
    $msg .= "Ubaceno: {$trenutni} DIN\n";

    foreach ($izabrani as $izabran) {
        $utils->update_artikal($izabran->get_sifra(), $izabran->get_kolicina() - 1);
        $total += $izabran->get_cena();
        $msg .= sprintf("Izabrano: %50s | %03d DIN\n", $izabran->get_naziv(), $izabran->get_cena());
    }

    $msg .= sprintf("Ukupno: %03d DIN\n", $total);

    $kusur = $trenutni - $total;
    $msg .= "Kusur: {$kusur} DIN = ";

    $dvadesetke = intdiv($kusur, 20);
    $kusur -= $dvadesetke * 20;
    $msg .= "{$dvadesetke} * 20 DIN";

    $desetke = intdiv($kusur, 10);
    $kusur -= $desetke * 10;
    $msg .= " + {$desetke} * 10 DIN";

    $petice = intdiv($kusur, 5);
    $kusur -= $petice * 5;
    $msg .= " + {$petice} * 5 DIN";

    $dvojke = intdiv($kusur, 2);
    $kusur -= $dvojke * 2;
    $msg .= " + {$dvojke} * 2 DIN";

    $jedinice = intdiv($kusur, 1);
    $kusur -= $jedinice * 1;
    $msg .= " + {$jedinice} * 1 DIN\n";
    $msg .= "----------------------------------------------------------------------\n";

    $filename = "racun " . date("Y-m-d") . " " . date("H-m-s") . ".txt";

    if (!file_exists($filename)) {
        if (!($racun = fopen($filename, "w")))
            return false;

        if (fwrite($racun, $msg) === FALSE)
            return false;
    }

    fclose($racun);
    return true;
}

$utils = new DBUtils();
$artikli = array();

fill_table();

if (isset($_SESSION["trenutni"]) && isset($_SESSION["izabrani"])) {
    if (isset($_POST["trenutni"])) {
        $trenutni = htmlspecialchars($_POST["trenutni"]);

        if (is_numeric($trenutni))
            ubaci_novac($trenutni);
        else
            echo "<script>alert(\"Pokusaj unosenja specijalnih karaktera\");</script>";
    }

    if (isset($_GET["izabran"])) {
        $izabran = htmlspecialchars($_GET["izabran"]);

        if (is_numeric($izabran))
            izaberi_artikal($izabran);
        else
            echo "<script>alert(\"Pokusaj unosenja specijalnih karaktera\");</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Automat</title>
</head>

<body>
    <header>
        <h1>Automat</h1>
        <a href="login.php">Prijava</a>
    </header>
    <main>
        <div id="main">
            <table border="1" style="text-align: center;">
                <?php
                if (isset($artikli)) {
                    for ($i = 0; $i < 6; $i++) {
                        echo "<tr>";

                        for ($j = 0; $j < 9; $j++)
                            echo $artikli[$i * 9 + $j]->get_html();

                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
        <div>
            <form action="" method="post">
                <p>Ubacite novac:</p>
                <p><input type="text" name="ubaci" required><input type="submit" value="Ubaci"></p>
            </form>
            <form action="" method="get">
                <p>Unesite sifru artikla:</p>
                <p><input type="text" name="unesi" required><input type="submit" value="Unesi"></p>
            </form>
            <form action="" method="post">
                <p><input type="submit" name="kupi" value="Kupi"></p>
            </form>
        </div>
    </main>
    <footer>
        <?php
        if (isset($_POST["kupi"])) {
            if (kupi()) {
                echo "Uspesna kupovina<br>";
                session_destroy();
            } else {
                echo "Neuspesna kupovina<br>";
                session_destroy();
            }

            echo "Istorija:";

            if (isset($_COOKIE["istorija"])) {
                $istorija = $_COOKIE["istorija"];
                echo $istorija;
            }
        }
        ?>
    </footer>
</body>

</html>