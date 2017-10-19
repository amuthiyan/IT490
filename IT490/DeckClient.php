<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logscript.php');
require_once('deck_class.php');

$test_deck = new Deck('Ventus_Deus');
//print_r($test_deck);
$card = [];
$card['name'] = 'Harpie Channeler';
$card['tag'] = 'MP14-EN021';
$card['avg_price'] = 4.24;

$card2 = [];
$card2['name'] = 'Harpy';
$card2['tag'] = 'MP14-EN021';
$card2['avg_price'] = 4.24;

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
