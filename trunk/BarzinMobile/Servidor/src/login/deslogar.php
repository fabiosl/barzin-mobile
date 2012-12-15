<?php
session_name("barzin");
session_start();
session_destroy();

header("Location: formlogin.php");
?>