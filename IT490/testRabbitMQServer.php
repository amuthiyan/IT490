#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('login.php.inc');

function doLogin($username,$password)
{
    // lookup username in database
    // check password
    $login = new loginDB();
    $login_status = $login->validateLogin($username,$password);
    echo "tried to login".PHP_EOL;
    return $login_status;
    //return true;
    //return false if not valid
}

function doRegister($username,$password)
{
  $register = new loginDB();
  $register_status = $register->registerUser($username,$password);
  echo "tried to register".PHP_EOL;
  return $register_status;
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
    case "register":
      return doRegister($request['username'],$request['password']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");
$server->process_requests('requestProcessor');
exit();
?>
