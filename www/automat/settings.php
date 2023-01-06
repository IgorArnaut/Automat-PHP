<?php
require("classes/Artikal.php");
require("utilities/DBUtils.php");

// Kreira novi alat za rad sa bazom
$utils = new DBUtils();
// Inicijalizuje niz artikala
$artikli = array();

// Ubacuje podatke sa fajla u bazu podataka
function insert_file_into_db()
{
    global $utils;
    $filename = $_FILES["filename"]["name"];

    // Ubacuje redove .csv fajla u bazu podataka
    if (file_exists($filename)) {
        $handle = fopen($filename, "r");
        $data = fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Ubacuje red u bazu ako ne postoji
            if (!$utils->check_artikal($data[0]))
                $utils->insert_artikal($data[0], $data[1], $data[2], $data[3]);
            else
                // Inace menja kolicinu artikla
                $utils->update_artikal($data[0], $data[3]);
        }

        fclose($handle);
    }
}

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
        array_push($artikli, $artikal);
    }
}

// Ubacuje podatke u bazu podataka ako je fajl ubacen
if (isset($_POST["submit"]))
    insert_file_into_db();

// Popunjava niz podacima iz baze podataka
fill_array();

// Unistava kolacice na dugme "destroy"
if (isset($_POST["destroy"])) {
    setcookie("istorija", "", time() - 3600);
    unset($_COOKIE["istorija"]);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>PODEŠAVANJA</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <div id="header">
        <h1>PODEŠAVANJA</h1>
        <a href="index.php">AUTOMAT</a>
    </div>
    <div id="main">
        <form enctype="multipart/form-data" action="" method="post">
            <h2>Unos artikala</h2>
            <label>Ubacite fajl:</label>
            <input type="file" name="filename">
            <br>
            <input type="submit" name="submit" value="Unesi">
        </form>

        <table>
            <h2>Prikaz artikala</h2>
            <tr>
                <th>ŠIFRA</th>
                <th>NAZIV</th>
                <th>CENA</th>
                <th>KOLIČINA</th>
            </tr>

            <?php
            if (isset($artikli)) {
                $page = 1;

                if (isset($_GET["page"])) {
                    $page = $_GET["page"];

                    // Stampa sve redove na tabeli
                    for ($i = ($page - 1) * 9; $i < ($page - 1) * 9 + 9; $i++) {
                        echo "<tr>";
                        echo "<td>{$artikli[$i]->get_sifra()}</td>";
                        echo "<td>{$artikli[$i]->get_naziv()}</td>";
                        echo "<td>{$artikli[$i]->get_cena()}</td>";
                        echo "<td>{$artikli[$i]->get_kolicina()}</td>";
                        echo "</tr>";
                    }
                } else {
                    // Stampa sve redove na tabeli
                    for ($i = 0; $i < 9; $i++) {
                        echo "<tr>";
                        echo "<td>{$artikli[$i]->get_sifra()}</td>";
                        echo "<td>{$artikli[$i]->get_naziv()}</td>";
                        echo "<td>{$artikli[$i]->get_cena()}</td>";
                        echo "<td>{$artikli[$i]->get_kolicina()}</td>";
                        echo "</tr>";
                    }
                }
            }
            ?>
        </table>

        <br>
        <?php
        // Prikazuje dugme za prethodnu stranicu
        if ($page > 1)
            echo "<a href=\"?page=" . ($page - 1) . "\"><button>Prethodna</button></a>";

        // Prikazuje dugme za sledecu stranicu
        if ($page < 6)
            echo "<a href=\"?page=" . ($page + 1) . "\"><button>Sledeća</button></a>";
        ?>

        <form action="" method="post">
            <h2>Istorijat</h2>
            <?php
            // Stampa istoriju sa kolacica
            if (isset($_COOKIE["istorija"])) {
                $istorija = $_COOKIE["istorija"];
                echo nl2br($istorija);
            }
            ?>

            <br>
            <input type="submit" name="destroy" value="Unisti kolačiće">
        </form>
    </div>
</body>

</html>