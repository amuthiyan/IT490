#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('login.php.inc');
require_once('logscript.php');

function doLogin($username,$password)
{
    // lookup username in database
    // check password
    $login = new loginDB();
    LogMsg("tried to login");
    echo "tried to login".PHP_EOL;
    $login_status = $login->validateLogin($username,$password);
    if($login_status)
    {
      LogMsg("Login Successful");
    }
    else
    {
      LogMsg("Login Failed");
    }
    echo $login_status.PHP_EOL;
    return $login_status;
    //return true;
    //return false if not valid
}

function doRegister($username,$password)
{
  $register = new loginDB();
  LogMsg("tried to register");
  echo "tried to register".PHP_EOL;
  $register_status = $register->registerUser($username,$password);
  if($register_status)
  {
    LogMsg("Registration Successful");
  }
  else
  {
    LogMsg("Registration Failed");
  }
  echo $register_status.PHP_EOL;
  return $register_status;
}

function requestProcessor($request)
{
  LogMsg("received request");
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    LogMsg("ERROR: unsupported message type");
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
