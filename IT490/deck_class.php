<?php

class Deck
{
  private $deck;
  private $uid;

  public function __construct($uid)
  {
    $this->deck = [];
    $this->uid = $uid;
  }

  public function add_card($card)
  {
    if(count($this->deck)<60)
    {
      array_push($this->deck,$card);
      //send card to database
      $client = new rabbitMQClient("DeckRabbit.ini","DeckServer");
      //insert the uid, decknum, and card print_tag into database
      $request = array();
      $request["type"] = "save_deck";
      $request["uid"] = $this->uid;
      $request["name"] = $card["name"];
      $request["tag"] = $card["tag"];
      $request["avg_price"] = $card["avg_price"];
      $response = $client->send_request($request);
      echo "saving card to deck".PHP_EOL;
    }
    else
    {
      echo "Deck card limit reached, cannot add card";
    }
  }

  public function show_cards()
  {
    $card_names = [];
    foreach($this->deck as $card)
    {
      array_push($card_names,$card['name']);
      //$card_names[$card['tag']] = $card['name'];
    }
    return json_encode($card_names);
  }
  /*
  public function save_deck()
  {
    //$db = new mysqli("127.0.0.1","root","sisibdp02","login");

    foreach($this->deck as $card)
    {
      $client = new rabbitMQClient("DeckRabbit.ini","DeckServer");
      //$card = json_encode($card);
      //insert the uid, decknum, and card print_tag into database
      $request = array();
      $request["type"] = "save_deck";
      $request["uid"] = $this->uid;
      //$request["deck_num"] = $this->deck_num;
      $request["name"] = $card["name"];
      $request["tag"] = $card["tag"];
      $request["avg_price"] = $card["avg_price"];
      $response = $client->send_request($request);
      echo "saving card to deck".PHP_EOL;
    }
    echo "Deck Saved".PHP_EOL;
    //var_dump($this->deck);
  }
  */
  public function get_price($type)
  {
    $price = 0;
    if($type == "avg")
    {
      foreach($this->deck as $card)
      {
        $card_price = $card["avg_price"];
        $price += $card_price;
      }
    }
    elseif($type == 'high')
    {
      foreach($this->deck as $card)
      {
        $card_price = $card["high_price"];
        $price += $card_price;
      }
    }
    elseif($type == 'low')
    {
      foreach($this->deck as $card)
      {
        $card_price = $card["low_price"];
        $price += $card_price;
      }
    }
    return $price;
  }
  public function load_deck($uid)
  {
    //load deck from the table
    $client = new rabbitMQClient("DeckRabbit.ini","DeckServer");
    $request = array();
    $request["type"] = "load_deck";
    $request['uid'] = $uid;
    $response = $client->send_request($request);
    $response = json_decode($response,true);
    $this->uid = $uid;
    $this->deck = $response;
  }
  public function remove_card($card)
  {
    /*
    $client = new rabbitMQClient("DeckRabbit.ini","DeckServer");
    $request["type"] = "remove_card";
    $request['uid'] = $this->uid;
    $request['tag'] = $card['tag'];

    $response = $client->send_request($request);
    */
    if(in_array($card,$this->deck))
    {
      unset($this->deck[array_search($card,$this->deck)]);
      $client = new rabbitMQClient("DeckRabbit.ini","DeckServer");
      $request["type"] = "remove_card";
      $request['uid'] = $this->uid;
      $request['tag'] = $card['tag'];

      $response = $client->send_request($request);
    }
  }

}

?>
