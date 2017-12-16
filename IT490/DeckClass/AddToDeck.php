<?php
/*Script defining functions to add and remove cards from the deck,
as well as load the deck */

//Define path for root to folow
$root_path = '/home/amuthiyan/git/IT490/';


// Import necessary rabbitmq scripts
require_once($root_path.'RabbitMQ/path.inc');
require_once($root_path.'RabbitMQ/get_host_info.inc');
require_once($root_path.'RabbitMQ/rabbitMQLib.inc');
require_once($root_path.'Logging/logscript.php');
require_once($root_path.'Failover/CheckAlive.php');

//Function to add card to deck
//Given card uid, deck_num, and JSON format card Array
function AddCard($uid/*,$decknum*/,$card)
{
  //Define path for root to folow
  $root_path = '/home/amuthiyan/git/IT490/';

  echo $card.PHP_EOL;
  //Decode the card array from JSON format
  $card = json_decode($card,true);
  echo $card['name'].PHP_EOL;

  //Create client to send card to deck database
  $client = new rabbitMQClient("/home/amuthiyan/git/Inis/DeckRabbit.ini","DeckServer");

  //insert the uid, decknum, and card print_tag into database
  $request = array();
  $request["type"] = "add_card";
  $request["uid"] = $uid;
  //$request["decknum"] = $decknum;
  $request["name"] = $card["name"];
  $request["tag"] = $card["tag"];
  $request["avg_price"] = $card["avg_price"];

  $response = $client->send_request($request);
  return $response;

  //echo "saving card to deck".PHP_EOL;
}

/* Function to remove card from Deck
Given uid, decknum, and JSON format card Array */
function RemoveCard($uid,$decknum,$card)
{
  //Define path for root to folow
  $root_path = '/home/amuthiyan/git/IT490/';

  //Decode the card array from JSON format
  $card = json_decode($card);

  //Create client to send removal request to deck database
  $client = new rabbitMQClient("/home/amuthiyan/git/Inis/DeckRabbit.ini","DeckServer");

  //Remove card from database
  $request["type"] = "remove_card";
  $request['uid'] = $uid;
  $request['tag'] = $card["tag"];

  $response = $client->send_request($request);
  return $response;
}

/* Function to load deck data
Given uid and decknum */
function LoadDeck($uid/*,$decknum*/)
{
  //Define path for root to folow
  $root_path = '/home/amuthiyan/git/IT490/';

  //load deck from the table
  $client = new rabbitMQClient("/home/amuthiyan/git/Inis/DeckRabbit.ini","DeckServer");
  $request = array();
  $request["type"] = "load_deck";
  $request['uid'] = $uid;
  //$request['decknum'] = $decknum;
  $response = $client->send_request($request);

  //Receive deck data and decode it
  $deck = json_decode($response,true);
  return $deck;
}

/*
//Accept input from html form and send it to database
if(isset($_POST))
{
  $request = $_POST;
  $response = "unsupported request type";
  switch($request['type'])
  {
    case "add_card":
      $response = AddCard($response['uid'],$response['decknum'],$response['card']);
    case "remove_card":
      $response = RemoveCard($response['uid'],$response['decknum'],$response['card']);
    case "load_deck":
      $deck = LoadDeck($response['uid'],$response['decknum']);
    break;
  }
*/

  /* functin to show the cards within the deck
  given uid and decknum*/
  function ShowCards($uid)
  {
    //Define path for root to folow
    $root_path = '/home/amuthiyan/git/IT490/';

    //load deck from the table
    $client = new rabbitMQClient("/home/amuthiyan/git/Inis/DeckRabbit.ini","DeckServer");
    $request = array();
    $request["type"] = "load_deck";
    $request['uid'] = $uid;
    $request['decknum'] = $decknum;
    $response = $client->send_request($request);

    //Receive deck data and decode it
    $deck = json_decode($response,true);

    //Show each card in the deck
    $card_names = [];
    foreach($deck as $card)
    {
      $card_info = [$card['tag'],$card['name']];
      array_push($card_names,$card_info);
      //$card_names[$card['tag']] = $card['name'];
    }
    return json_encode($card_names);
  }

  //Funcion to get price of deck given uid
  function GetPrice($uid)
  {
    //Define path for root to folow
    $root_path = '/home/amuthiyan/git/IT490/';

    //load deck from the table
    $client = new rabbitMQClient("/home/amuthiyan/git/Inis/DeckRabbit.ini","DeckServer");
    $request = array();
    $request["type"] = "load_deck";
    $request['uid'] = $uid;
    $request['decknum'] = $decknum;
    $response = $client->send_request($request);

    //Receive deck data and decode it
    $deck = json_decode($response,true);

    //Retrieve total price of deck
    $price = 0;
    foreach($deck as $card)
    {
      $card_price = $card["avg_price"];
      $price += $card_price;
    }

    return $price;
  }
//}

?>
