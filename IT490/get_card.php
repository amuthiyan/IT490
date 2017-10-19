<?php
require_once('api_pull.php.inc');
require_once('logscript.php');

function getCard($tag,$name)
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

  //$name_temp = $card_price["data"]["name"];

  //get general card info
  $card_info = readURL("http://yugiohprices.com/api/card_data/$name");

  $card["text"] = $card_info["data"]["text"];
  $card["card_type"] = $card_info["data"]["card_type"];
  $card["type"] = $card_info["data"]["type"];
  $card["family"] = $card_info["data"]["family"];
  $card["atk"] = $card_info["data"]["atk"];
  $card["def"] = $card_info["data"]["def"];
  $card["level"] = $card_info["data"]["level"];

  //return info about the card in an array
  //LogMsg('Card information sent');
  echo json_encode($card);
  return json_encode($card);

}

//$card = getCard("LTGY-EN035","Harpie Channeler");
//var_dump($card);

 ?>
