<?php

# Enabling error display
error_reporting(E_ALL);
ini_set('display_errors', 1);

# Including all the required scripts for demo
require __DIR__ . "/discord.php";

# Initializing all the required values for the script to work
$code = $_GET['code'];
init($code);

# Fetching user details 
get_user($email = True);

# Fetching user guilds
$_SESSION['guilds'] = get_guilds();

# Fetching user connections 
$_SESSION['connections'] = get_connections();


# Redirecting to home page once all data has been fetched
header('Location: ../index.php');