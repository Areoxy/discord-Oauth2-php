<?php

require __DIR__ . "/functions.php";

# Starting the session
session_start();

# Closing the session and deleting all values associated with the session
session_destroy();

# Redirecting the user back to login page
redirect("../index.php");

?>
