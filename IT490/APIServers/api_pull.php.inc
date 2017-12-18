<?php
/*
Function to pull data from an API with a specific URL endpoint.
Parameters:
url - A valid API url
Returns:
The data read from the site in array format.
*/
function readURL($url)
{
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  $response = json_decode(curl_exec($ch),TRUE);
  curl_close($ch);
  return $response;
}


?>
