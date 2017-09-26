<?php
// Create the error logging function
function LogMsg($e)
{
  $logmsg = array();
  $logmsg['time'] = date("h:i:sa");
  $logmsg['date'] = date("Y-m-d");
  $logmsg['day'] = date("l");
  $logmsg['text'] = $e;

  //log the message
  $msg = implode("-",$logmsg);
  error_log($msg,3,"./logfile.log");
}
LogMsg('test')
?>
