<?php


# Start Session

session_start();

# Define Variables

$GLOBALS['base_url'] = "https://discord.com/api";
$GLOBALS['client_id'] = "yourClientID";
$GLOBALS['client_secret'] = "YourClientSecret";
$GLOBALS['client_token'] = "YourBotToken";
$GLOBALS['redirect_url'] = "https://myDomainName.de/login.php";


# #A function to initialize and store access token in SESSION to be used for other requests

function init($code) 
{
    $url = $GLOBALS['base_url'] . "/oauth2/token";
    $data = array(
        'client_id' => $GLOBALS['client_id'],
        'client_secret' => $GLOBALS['client_secret'],
        'grant_type' => "authorization_code",
        'code' => $code,
        'redirect_uri' => $GLOBALS['redirect_url']

    );
    $headers = array(
        "Content-Type: application/x-www-form-urlencoded"
     );
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    $_SESSION['access_token'] = $results['access_token'];
}


# A function to get and store user information in SESSION

function get_user($email_scope = True) 
{
    $url = $GLOBALS['base_url'] . "/users/@me";
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $_SESSION['access_token']
    );
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    # Fetching user information
    $_SESSION['user'] = $results;
    $_SESSION['user_name'] = $results['username'];
    $_SESSION['discriminator'] = $results['discriminator'];
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['user_avatar'] = $results['avatar'];
    # Fetching email if true (Requires the email scope)
    if ($email == True) {
        $_SESSION['email'] = $results['email'];
    }
}

# A function to get and store user guilds in SESSION (Requires the guilds scope)

function get_guilds() 
{
    $url = $GLOBALS['base_url'] . "/users/@me/guilds";
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $_SESSION['access_token']
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}

# A function to get users connections (Requires the connections scope)

function get_connections() 
{
    $url = $GLOBALS['base_url'] . "/users/@me/connections";
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $_SESSION['access_token']
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;

}

# A function to add user to a guild
# Note : The bot has to be a member of the server with CREATE_INSTANT_INVITE permission.
#        The bot mussn't be online it must only be on your guild
#   
function join_guild($guild_id) 
{
    $data = json_encode(array("access_token" => $_SESSION['access_token']));
    $url = $GLOBALS['base_url'] . "/guilds/$guildid/members/" . $_SESSION['user_id'];
    $headers = array('Content-Type: application/json', 'Authorization: Bot ' . $GLOBALS['bot_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}


# A function to check user's avatar type
function is_animated($avatar)
{
	$ext = substr($avatar, 0, 2);
	if ($ext == "a_")
	{
		return ".gif";
	}
	else
	{
		return ".png";
	}
}

?>
