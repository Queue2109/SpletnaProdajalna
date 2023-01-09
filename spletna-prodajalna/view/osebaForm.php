<?php 
require_once("index.php");
session_start();
?>

<!DOCTYPE html>

<meta charset="UTF-8" />
<title>Add entry</title>

<p>
[<a href="<?= BASE_URL . $_SESSION["user"] ?>">Profil</a> |
<a href="<?= BASE_URL . "logout" ?>">Odjava</a>]</p>

<h1>Uredi</h1>

<?= $form ?>
<?= $deleteForm ?>