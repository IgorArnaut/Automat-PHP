<?php
class DBUtils
{
    private $conn;

    public function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=automat";
        $user = "root";
        $pass = "";
        $this->conn = new PDO($dsn, $user, $pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insert_artikal($sifra, $naziv, $cena, $kolicina)
    {
        $sql = "INSERT INTO " . TABLE_ARTIKLI . " VALUES (:sifra, :naziv, :cena, :kolicina)";

        try {
            $st = $this->conn->prepare($sql);
            $st->bindValue(":sifra", $sifra, PDO::PARAM_INT);
            $st->bindValue(":naziv", $naziv, PDO::PARAM_STR);
            $st->bindValue(":cena", $cena, PDO::PARAM_INT);
            $st->bindValue(":kolicina", $kolicina, PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function update_artikal($sifra, $kolicina)
    {
        $sql = "UPDATE " . TABLE_ARTIKLI . " SET " . COL_ARTIKLI_KOLICINA . " = :kolicina WHERE " . COL_ARTIKLI_SIFRA . " = :sifra";

        try {
            $st = $this->conn->prepare($sql);
            $st->bindValue(":kolicina", $kolicina, PDO::PARAM_INT);
            $st->bindValue(":sifra", $sifra, PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function select_artikli()
    {
        $sql = "SELECT * FROM " . TABLE_ARTIKLI . " ORDER BY " . COL_ARTIKLI_SIFRA;
        $rows = $this->conn->query($sql)->fetchAll(PDO::FETCH_BOTH);
        return $rows;
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}
