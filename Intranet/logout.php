<?php
session_start();
session_destroy();
header("Location: loginti.php");
exit();
?>