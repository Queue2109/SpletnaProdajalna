<?php 
require_once("index.php");
session_start();
?>
<!doctype html>
<meta charset="UTF-8" />
<title>Add entry</title>

<a href="<?= BASE_URL . "instruments" ?>">Vsi instrumenti</a> |
<a href="<?= BASE_URL . "login" ?>">Prijava</a>

<?= $form ?>