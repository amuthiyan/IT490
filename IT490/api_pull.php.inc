<?php
//get data from url
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

//echo var_dump($cards);


function readImage($url,$name)
{
  $image = imagecreatefromjpeg($url);
  //imagejpeg($image,"card_images/$name.jpg");
  return $image;

}
?>
