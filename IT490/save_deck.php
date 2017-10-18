<?php
function saveCard($uid,$name,$tag,$avg_price)
{
  //$db = new mysqli("127.0.0.1","root","sisibdp02","login");
  $uid = $db->real_escape_string($uid);
	$name = $db->real_escape_string($name);
  $tag = $db->real_escape_string($tag);
  $avg_price = $db->real_escape_string($avg_price);

  //Insert card into deck table
  $statement = "insert into decks (uid,name,tag,avg_price) values ('$uid','$name','$tag',$avg_price);";
	$response = $db->query($statement);
}
function loadDeck($uid)
{
  //$db = new mysqli("127.0.0.1","root","sisibdp02","login");
  $deck = [];

  //retrieve data from database for user and deck_num
  $statement = "select * from decks where uid = '$uid';";
  $response = $db->query($statement);

  //add each card to the deck array
  while ($row = $response->fetch_assoc())
  {
    $card = [];
    $card['name'] = $row['name'];
    $card['tag'] = $row['tag'];
    $card['avg_price'] = $row['avg_price'];
    array_push($deck,$card);
  }
  //return deck
  $deck = json_encode($deck);
  return $deck;
}
function removeCard($uid,$tag)
{
  //find remove card with tag from deck_num
  echo "removing card";
}

?>
