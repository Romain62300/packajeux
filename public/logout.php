<?php
session_start();
session_destroy();
header("Location: /Packajeux/public/index.php?logout=1");
exit;
