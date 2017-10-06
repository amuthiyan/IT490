<?php
//get the read_url function
require_once('api_pull.php.inc');

//get list of sets
$set_list = readURL("http://yugiohprices.com/api/card_sets");
//echo var_dump($set_list);
$set_count = 0;

//get list of each card name and print tag
$card_list = [];

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
    $name = $card['name'];

    $info = $card['numbers'];
    $tag = $info[0]['print_tag'];

    //echo $tag.PHP_EOL;

    $card_list[$tag] = $name;
  }
  $set_count += 1;
  echo $set_count." ";

}
var_dump($card_list);

?>
