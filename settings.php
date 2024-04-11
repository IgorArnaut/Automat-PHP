<?php
require("classes/Item.php");
require("utilities/DBUtils.php");

// Inserts rows into the database
function insert_rows($handle, $data)
{
    global $utils;

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if (!$utils->check_items($data[0]))
            $utils->insert_item($data[0], $data[1], $data[2], $data[3], $data[4]);
        else
            $utils->update_item($data[0], $data[3]);
    }
}

// Inserts file data into the database
function insert_file_into_db()
{
    if (isset($_POST["submit"])) {
        $filename = $_FILES["filename"]["name"];

        if (file_exists($filename)) {
            $handle = fopen($filename, "r");
            $data = fgetcsv($handle, 1000, ",");
            insert_rows($handle, $data);
            fclose($handle);
        }
    }
}

// Inserts items into the array
function insert_items($table)
{
    global $items;

    foreach ($table as $row) {
        $item = new Item(
            $row[COL_ITEMS_ID],
            $row[COL_ITEMS_NAME],
            $row[COL_ITEMS_PRICE],
            $row[COL_ITEMS_AMOUNT],
            $row[COL_ITEMS_IMAGE]
        );
        array_push($items, $item);
    }
}

// Fills the array with database data
function fill_array()
{
    global $utils;
    $table = $utils->select_items();
    insert_items($table);
}

// Initializes the settings page
function init()
{
    insert_file_into_db();
    fill_array();
}

// Shows history
function show_history()
{
    if (isset($_COOKIE["history"])) {
        $history = $_COOKIE["history"];
        echo nl2br($history);
    }
}

// Deletes history
function delete_history()
{
    if (isset($_POST["destroy"])) {
        setcookie("History", "", time() - 3600);
        unset($_COOKIE["History"]);
    }
}

// Displays a row
function display_row($i)
{
    global $items;
    echo "<tr>";
    echo "<td>{$items[$i]->get_id()}</td>";
    echo "<td>{$items[$i]->get_name()}</td>";
    echo "<td>{$items[$i]->get_price()}</td>";
    echo "<td>{$items[$i]->get_amount()}</td>";
    echo "</tr>";
}

// Display rows
function display_rows()
{
    global $page;

    if (isset($items)) {
        $page = 1;

        if (isset($_GET["page"])) {
            $page = $_GET["page"];

            for ($i = ($page - 1) * 9; $i < ($page - 1) * 9 + 9; $i++)
                display_row($i);
        } else {
            for ($i = 0; $i < 9; $i++)
                display_row($i);
        }
    }
}

// Displays buttons
function display_buttons()
{
    global $page;
    $previous = $page - 1;
    $next = $page + 1;
    $previous_button = "<a href=\"?page=$previous\"><button>Previous</button></a>";
    $next_button = "<a href=\"?page=$next\"><button>Next</button></a>";

    if ($page > 1)
        echo $previous_button;

    if ($page < 6)
        echo $next_button;
}

$utils = new DBUtils();
$items = array();
$page = 0;
init();
delete_history();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Settings</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <div id="header">
        <h1>Settings</h1>
        <a href="index.php">Vending Machine</a>
    </div>
    <div id="main">
        <div>
            <form enctype="multipart/form-data" action="" method="post">
                <h2>Insert items</h2>
                <p>Upload a file: <input type="file" name="filename"></p>
                <p><input type="submit" name="submit" value="Upload"></p>
            </form>
        </div>

        <div>
            <table>
                <h2>Show items</h2>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
                <?php display_rows(); ?>
            </table>
            <p><?php display_buttons(); ?></p>
        </div>

        <div>
            <form action="" method="post">
                <h2>History</h2>
                <p><?php show_history() ?></p>
                <p><input type="submit" name="destroy" value="Delete cookies"></p>
            </form>
        </div>
    </div>
</body>

</html>