<?php
require("classes/Artikal.php");
require("classes/DBUtils.php");
require("utilities/constants.php");

$utils = new DBUtils();
$artikli = array();

function insert_file_into_table()
{
    global $utils;
    $filename = $_FILES["filename"]["name"];

    if (file_exists($filename)) {
        $handle = fopen($filename, "r");
        $data = fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
            $utils->insert_artikal($data[0], $data[1], $data[2], $data[3]);

        fclose($handle);
    }
}

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

if (isset($_POST["submit"]))
    insert_file_into_table();

fill_table();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Podešavanja</title>
</head>

<body>
    <header>
        <h1>Podešavanja</h1>
    </header>
    <main>
        <h2>Unos artikala</h2>
        <form enctype="multipart/form-data" action="" method="post">
            <p><input type="file" name="filename"></p>
            <p><input type="submit" name="submit" value="Unesi"></p>
        </form>
        <h2>Prikaz artikala</h2>
        <table>
            <tr>
                <th>SIFRA</th>
                <th>NAZIV</th>
                <th>CENA</th>
                <th>KOLICINA</th>
            </tr>
            <?php

            if (isset($artikli)) {
                foreach ($artikli as $artikal) {
                    echo "<tr>";
                    echo "<td>{$artikal->get_sifra()}</td>";
                    echo "<td>{$artikal->get_naziv()}</td>";
                    echo "<td>{$artikal->get_cena()}</td>";
                    echo "<td>{$artikal->get_kolicina()}</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </main>
</body>

</html>