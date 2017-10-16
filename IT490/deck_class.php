<?php

class Deck
{
  private $deck;
  private $uid;
  private $deck_num;

  public function __construct($uid,$deck_num)
  {
    $this->deck = [];
    $this->uid = $uid;
    $this->deck_num = $deck_num;
  }

  public function add_card($card)
  {
    if(count($this->deck)<60)
    {
      array_push($this->deck,$card);
    }
    else
    {
      echo "Deck card limit reached, cannot add card";
    }
  }

  public function save_deck()
  {
    //$db = new mysqli("127.0.0.1","root","sisibdp02","login");
    $client = new rabbitMQClient("DeckRabbit.ini","DeckServer");
    foreach($this->deck as $card)
    {
      //$card = json_encode($card);
      //insert the uid, decknum, and card print_tag into database
      $request = array();
      $request["type"] = "save_deck";
      $request["uid"] = $this->uid;
      //$request["deck_num"] = $this->deck_num;
      $request["card"] = $card;
      $response = $client->send_request($request);
      echo "saving card to deck".PHP_EOL;
    }
    echo "Deck Saved".PHP_EOL;
    //var_dump($this->deck);
  }
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
  public function load_deck($uid,$deck_num)
  {
    //load deck from the table
    $client = new rabbitMQClient("DeckRabbit.ini","DeckServer");
    $request = array();
    $request["type"] = "load_deck";
    $request['uid'] = $uid;
    //$request['deck_num'] = $deck_num;
    $response = $client->send_request($request);
    $response = json_decode($response,true);
    $this->uid = $uid;
    $this->deck_num = $deck_num;
    $this->deck = $response['deck'];
  }
  public function remove_card($card)
  {
    if(in_array($card,$this->deck))
    {
      unset($this->deck[array_search($card,$this->deck)]);
    }
  }

}

?>
