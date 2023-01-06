<?php
require("constants.php");

class DBUtils
{
    private $conn;

    // Kreira novu konekciju na bazu podataka sa svojim izuzecima
    public function __construct()
    {
        // Adresa baze podataka, korisnicko ime i lozinka
        $dsn = "mysql:host=localhost;dbname=automat";
        $user = DB_USERNAME;
        $pass = DB_PASSWORD;
        // Kreira konekciju na bazu na adresi i postavlja atribute
        $this->conn = new PDO($dsn, $user, $pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Ubacuje novi red u tabelu sa unetim vrednostima
    public function insert_artikal($sifra, $naziv, $cena, $kolicina)
    {
        // Kreira novi upit
        $sql = "INSERT INTO " . TABLE_ARTIKLI . " VALUES (:sifra, :naziv, :cena, :kolicina)";

        try {
            // Priprema novu komandu za taj upit i postavlja vrednosti odgovarajucim parametrima
            $st = $this->conn->prepare($sql);
            $st->bindValue(":sifra", $sifra, PDO::PARAM_INT);
            $st->bindValue(":naziv", $naziv, PDO::PARAM_STR);
            $st->bindValue(":cena", $cena, PDO::PARAM_INT);
            $st->bindValue(":kolicina", $kolicina, PDO::PARAM_INT);
            // Izvrsava tu komandu
            $st->execute();
            return true;
        } catch (PDOException $e) {
            // Stampa poruku ako dodje do greske
            echo $e->getMessage();
            return false;
        }
    }

    // Pribavlja sve redove u tabeli po redosledu
    public function select_artikli()
    {
        // Kreira novi upit
        $sql = "SELECT * FROM " . TABLE_ARTIKLI . " ORDER BY " . COL_ARTIKLI_SIFRA;
        // Vraca sve redove iz tabele na osnovu tog upita
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_BOTH);
    }

    public function check_artikal($sifra)
    {
        // Kreira novi upit
        $sql = "SELECT COUNT(*) FROM " . TABLE_ARTIKLI . " WHERE " . COL_ARTIKLI_SIFRA . " = :sifra";

        try {
            // Priprema novu komandu za taj upit i postavlja vrednosti odgovarajucim parametrima
            $st = $this->conn->prepare($sql);
            $st->bindValue(":sifra", $sifra, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchColumn() == 1;
        } catch (PDOException $e) {
            // Stampa poruku ako dodje do greske
            echo $e->getMessage();
            return false;
        }
    }

    // Menja kolicinu artikla koji ima unetu sifru i stampa poruku ako dodje do greske
    public function update_artikal($sifra, $kolicina)
    {
        // Kreira novi upit
        $sql = "UPDATE " . TABLE_ARTIKLI . " SET " . COL_ARTIKLI_KOLICINA . " = :kolicina WHERE " . COL_ARTIKLI_SIFRA . " = :sifra";

        try {
            // Priprema novu komandu za taj upit i postavlja vrednosti odgovarajucim parametrima
            $st = $this->conn->prepare($sql);
            $st->bindValue(":kolicina", $kolicina, PDO::PARAM_INT);
            $st->bindValue(":sifra", $sifra, PDO::PARAM_INT);
            // Izvrsava tu komandu
            $st->execute();
            return true;
        } catch (PDOException $e) {
            // Stampa poruku ako dodje do greske
            echo $e->getMessage();
            return false;
        }
    }

    // Unistava trenutnu konekciju
    public function __destruct()
    {
        $this->conn = null;
    }
}
