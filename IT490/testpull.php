<?php
//get data from url
function readData($url)
{
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  $response = json_decode(curl_exec($ch),TRUE);
  curl_close($ch);
  return $response;
}
//get list of sets
$set_list = readData("http://yugiohprices.com/api/card_sets");
//echo var_dump($set_list);
foreach($set_list as $set)
{
  //get the data for a single set
  $card_set = readData("http://yugiohprices.com/api/set_data/$set");

  //extract only the info about cards from the set
  $card_set = $card_set["data"]["cards"];

  //print name of every cards
  foreach($card_set as $card)
  {
    $name = $card['name'];
    echo $name.PHP_EOL;
  }
}




//echo var_dump($cards);
?>
