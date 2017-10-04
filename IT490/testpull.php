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
//get list of sets
$set_list = readURL("http://yugiohprices.com/api/card_sets");
//echo var_dump($set_list);
$set_count = 0;

foreach($set_list as $set)
{
  //count the total amount of
  $set_num = count($set_list);

  //echo $set_num.PHP_EOL;
  //set url to get data from
  $url = "http://yugiohprices.com/api/set_data/$set";

  //get the data for a single set
  $card_set = readURL($url);

  //extract only the info about cards from the set
  $card_set = $card_set["data"]["cards"];

  //print name of every cards
  foreach($card_set as $card)
  {
    //$name = $card['name'];
    $name = $card['numbers'];
    //echo $name.PHP_EOL;
    var_dump($name);
  }
  $set_count += 1;
  //echo $set_count.PHP_EOL;
  //echo $set_count.PHP_EOL;
}




//echo var_dump($cards);
?>
