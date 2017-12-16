<?php
/*
This Script contains functions that retrieve card data from an API and return them.
*/

//Define path for root to folow
$root_path = '/home/amuthiyan/git/IT490/';

require_once($root_path.'APIServers/api_pull.php.inc');
require_once($root_path.'Logging/logscript.php');

/*
Function CacheCard:
Stores the card data in the cache table. Takes as a parameter a php array
containing the card data.
It returns nothing, but echos a statement when the card has been cached.
Parameters:
card - A php array containing information about the card being stored in cache.
Returns:
Nothing
*/
function CacheCard($card)
{
  $client = new rabbitMQClient("/home/amuthiyan/git/Inis/DeckRabbit.ini","DeckServer");
  $card = json_encode($card);
  $request = array();
  $request["type"] = "cache_card";
  $request["card"] = $card;
  $response = $client->send_request($request);
  echo "Card Cached".PHP_EOL;
}
/*
Function InCache:
Checks if the card requested is in the cache table. Takes as parameters the card
tag, and the card name. It returns a boolean true false depending on if the card
is in the cache table.
Parameters:
tag - A string that is the unique id for the card being searched for.
name - A string that is the name of the card being searched for.
Returns:
response - A boolean value. Returns true (1) if the card is in the cache, and
False (0) if it is not.
*/
function InCache($tag,$name)
{
  $client = new rabbitMQClient("/home/amuthiyan/git/Inis/DeckRabbit.ini","DeckServer");
  $request["type"] = "check_cache";
  $request["tag"] = $tag;
  $request["name"] = $name;
  $response = $client->send_request($request);
  return $response;
}
/*
Function getCard:
Returns a JSON array containing card info which is pulled from an API.
Takes as parameters two strings, tag, which is the uniqe card id, and name,
which is the card name.
Parameters:
tag - A string that is the unique id for the card being searched for.
name - A string that is the name of the card being searched for.
Returns:
card - A JSON array containing the card information.
*/
function getCard($tag,$name)
{
  //Check if card can be pulled from cache
  if(InCache($tag,$name) == 1)
  {
    print('Pulling from cache').PHP_EOL;
    $client = new rabbitMQClient("/home/amuthiyan/git/Inis/DeckRabbit.ini","DeckServer");
    $request["type"] = "load_cache";
    $request["tag"] = $tag;
    $request["name"] = $name;
    $card = $client->send_request($request);
    $card = json_decode($card,true);
  }
  else
  {
    $card_price = readURL("http://yugiohprices.com/api/price_for_print_tag/$tag");
    //array with info about card
    $card = [];

    //get rarity, high price, low price, average price
    $card["name"] = $name;
    $card["tag"] = $tag;
    $card["rarity"] = $card_price["data"]["price_data"]["rarity"];
    $card["high_price"] = $card_price["data"]["price_data"]["price_data"]["data"]["prices"]["high"];
    $card["low_price"] = $card_price["data"]["price_data"]["price_data"]["data"]["prices"]["low"];
    $card["avg_price"] = $card_price["data"]["price_data"]["price_data"]["data"]["prices"]["average"];


    //get general card info
    $card_info = readURL("http://yugiohprices.com/api/card_data/$name");

    $card["text"] = $card_info["data"]["text"];
    $card["card_type"] = $card_info["data"]["card_type"];
    $card["type"] = $card_info["data"]["type"];
    $card["family"] = $card_info["data"]["family"];
    $card["atk"] = $card_info["data"]["atk"];
    $card["def"] = $card_info["data"]["def"];
    $card["level"] = $card_info["data"]["level"];
    $card["image_url"] = "static-3.studiobebop.net/ygo_data/card_images/".str_replace(" ","_",$name).".jpg";

    CacheCard($card);
  }

  echo json_encode($card);
  return json_encode($card);

}

$card = json_decode(getCard("LTGY-EN035","Harpie Channeler"),true);
var_dump($card);
 ?>
