<?php
session_start();
session_destroy();
header("Location: /gamepack/public/index.php?logout=1");
exit;
