<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
session_start();

include("./database.php");
include("./views/header.php");

try {
    $row = select_one("select value from config where name = \"lang_path\"");
    $lang_path = $row["value"];
    include($lang_path);
} catch (PDOException $e) {
    die($e);
}

if (!isset($_SESSION['dir']))
    $_SESSION['dir'] = 'upload/' . bin2hex(random_bytes(16));

$dir = $_SESSION['dir'];

if (!file_exists($dir))
    mkdir($dir);

if (isset($_POST['comment'])) {
    try {
        $file_path = "";
        if (!empty($_POST["url"])) {
            $content = file_get_contents($_POST["url"]);
            var_dump(file_get_contents($_POST["url"]));
            $file_path = $dir . "/" . md5($content);
            file_put_contents($file_path, $content);
        }
        insert_one(
            "INSERT INTO comments(display_name, comment, image) VALUES (?, ?, ?)",
            "Anonymous",
            $_POST['comment'],
            $file_path
        );
    } catch (PDOException $e) {
        die($e);
    }
}
include("./views/comments.php");
?>

<?php include("./views/footer.php"); ?>
