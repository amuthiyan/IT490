<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logscript.php');
require_once('deck_class.php');
require_once('get_card.php');

$test_deck = new Deck('asdf');
//print_r($test_deck);


$harpie = json_decode(getCard("LTGY-EN035","Harpie Channeler"),true);
var_dump($harpie);

$card = [];
$card['name'] = $harpie['name'];
$card['tag'] = $harpie['tag'];
$card['avg_price'] = $harpie['avg_price'];

$jinzo = json_decode(getCard('BPT-011','Jinzo'),true);
$card2 = [];
$card2['name'] = $jinzo['name'];
$card2['tag'] = $jinzo['tag'];
$card2['avg_price'] = $jinzo['avg_price'];
var_dump($jinzo);

$test_deck->add_card($card);
$test_deck->add_card($card2);

$names = $test_deck->show_cards();
echo $names.PHP_EOL;
/*
foreach($names as $name)
{
  echo $name.PHP_EOL;
}
*/
//$test_deck->remove_card($card);
//$test_deck->save_deck();
//$new_deck = new Deck('aneesh');
//$new_deck->load_deck('chris');
//$new_deck->save_deck();

//$chris_deck = new Deck('chris');
//$chris_deck->load_deck('chris');
//print_r($chris_deck);

 ?>
