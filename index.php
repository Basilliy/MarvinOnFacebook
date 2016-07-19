<?php
/**
 * Created by PhpStorm.
 * User: russik
 * Date: 18.07.2016
 * Time: 10:56
 */
 //$verify_token = "hi"; // Verify token 
//if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) { 
//echo $_REQUEST['hub_challenge']; 
//}

//file_put_contents('fb.txt',file_get_contents('php://input'));
$fb = json_decode(file_get_contents('php://input'),true);
$id = $fb->entry[0]->messaging[0]->sender->id;
$token = 'EAAXK3CoMH0QBAM3gZClSKzVcMLnL4uVvvUJG7wQaifTjgN65T2F8SmftMLJyD3uZCky02NA0bLjzEdfzhYc3TUY4HO8WkyqMZBZBdXD0P7BQlzge9CwZAZCZCDAybdGSyyoKJqRF1Rqj5nE723f5v8TqIawkWph7zeJdXxkYqUTnZCz7FHLLY59O';

$data = array(
      'recipient' => array('id' => $id ),
      'message' => array('text' => 'russik')
 );

$options = array(
          'http'=>array(
             'method' => 'POST',
             'content' => json_encode($data),
             'header' => "Content-Type: application/json\n"
             )
 );

$contex = stream_context_create('$options');

file_get_contents("https://graph.facebook.com/v2.6/me/messages?access_token=$token", false, $context);

