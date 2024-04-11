<?php
require("constants.php");

class DBUtils
{
    private $conn;

    // Creates a new database connection
    public function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=vendingmachine";
        $user = DB_USERNAME;
        $pass = DB_PASSWORD;
        $this->conn = new PDO($dsn, $user, $pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Inserts a new item into the database and throws an exception if it fails
    public function insert_item($id, $name, $price, $amount, $image)
    {
        $sql = "INSERT INTO " . TABLE_ITEMS . " VALUES (:id, :name, :price, :amount, :image)";

        try {
            $st = $this->conn->prepare($sql);
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->bindValue(":name", $name, PDO::PARAM_STR);
            $st->bindValue(":price", $price, PDO::PARAM_INT);
            $st->bindValue(":amount", $amount, PDO::PARAM_INT);
            $st->bindValue(":image", $image, PDO::PARAM_STR);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Returns the list of all items
    public function select_items()
    {
        $sql = "SELECT * FROM " . TABLE_ITEMS . " ORDER BY " . COL_ITEMS_ID;
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_BOTH);
    }

    // Checks if an item exists in the database
    public function check_items($id)
    {
        $sql = "SELECT COUNT(*) FROM " . TABLE_ITEMS . " WHERE " . COL_ITEMS_ID . " = :id";

        try {
            $st = $this->conn->prepare($sql);
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchColumn() == 1;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Reduces the amount of an item and throws an exception if it fails
    public function update_item($id, $amount)
    {
        $sql = "UPDATE " . TABLE_ITEMS . " SET " . COL_ITEMS_AMOUNT . " = :amount WHERE " . COL_ITEMS_ID . " = :id";

        try {
            $st = $this->conn->prepare($sql);
            $st->bindValue(":amount", $amount, PDO::PARAM_INT);
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Closes the database
    public function __destruct()
    {
        $this->conn = null;
    }
}
