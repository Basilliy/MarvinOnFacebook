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
    // Webhook setup request

file_put_contents("fb.txt",file_get_contents("php://input"));

$fb = file_get_contents("fb.txt");
$fb = json_decode($fb);
//$fb = json_decode(file_get_contents("php://input"), true);
$id = $fb->entry[0]->messaging[0]->sender->id;
$reid = $fb->entry[0]->messaging[0]->recipient->id;
$message = $fb->entry[0]->messaging[0]->message->text;
$postback = $fb->entry[0]->messaging[0]->postback->payload;
$token = "EAAXK3CoMH0QBAM3gZClSKzVcMLnL4uVvvUJG7wQaifTjgN65T2F8SmftMLJyD3uZCky02NA0bLjzEdfzhYc3TUY4HO8WkyqMZBZBdXD0P7BQlzge9CwZAZCZCDAybdGSyyoKJqRF1Rqj5nE723f5v8TqIawkWph7zeJdXxkYqUTnZCz7FHLLY59O";

$fp = json_decode(file_get_contents('user.json'), true);

if($postback=='en'){
$fuck = file_get_contents('https://evilinsult.com/generate_insult.php?lang=en');
}
else{
$fuck = file_get_contents('https://evilinsult.com/generate_insult.php?lang=de');
}

$buttonEN =json_encode(array(
        "type" => "postback",
         "title" => "en",
         "payload" => "en"
));
$buttonDE =json_encode(array(
        "type" => "postback",
         "title" => "de",
         "payload" => "USER_DEFINED_PAYLOAD"
 ));
 
$attachment = array(
         "type" => "template",
         "payload" => array(
               "template_type" => "button",
               "text" => "Language",
               "buttons" => [$buttonEN, $buttonDE]
        )
 );

$site =json_encode(array(
         "type" => "web_url",
         "url" => " https://evilinsult.com ",
         "title" => "Evil Insult Generator Homepage"
 ));

$element = array(
            "title" => "Evil Insult Generator Homepage",
            "image_url" => "https://martinfacebook.herokuapp.com/image.jpg",
            "subtitle" => "Click",
            "buttons" => [$site]
             );

$URL= array(
         "type" => "template",
         "payload" => array(
               "template_type" => "generic",
               "elements" => [$element]
        )
 );

$buttonGenerate = array(
        "content_type" => "text",
        "title" => "Generate",
        "payload" => "DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_EN"
 );

$buttonLanguage = array(
        "content_type" => "text",
        "title" => "Language",
        "payload" => "DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_EN"
 );
 
 $buttonHomepage = array(
        "content_type" => "text",
        "title" => "Homepage",
        "payload" => "DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_EN"
 );
 
$keyboardSet =[$buttonGenerate,$buttonLanguage,$buttonHomepage];

$keyboard = array(
         'content_type' => "text",
         'title' => 'Red',
         'payload' => 'DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_RED'
 );

if(($postback=='en')||($postback=='de')){
 if (checkUser($fp, $id) != false) {
            foreach ( $fp as $key=> $value) {
              if($key==$chat_id){
                 $fp[$key] = $postback;
              }
             }
             $arr3 = json_encode($fp);
             file_put_contents('user.json', $arr3);
          }
          else{
            AddUser($id,$fp,$postback);
          }
          
}

switch ($message) {
        case 'Generate':
           $data = array(
           'recipient' => array('id' => "$id" ),
           'message' => array("text" => "$fuck",
           "quick_replies" => json_encode($keyboardSet)
            )
           );
        break;
        case 'Language':
          $data = array(
          'recipient' => array('id' => "$id" ),
          'message' => array("attachment" => $attachment)
          );
          //SendMessage($data);
        break;
        case 'Homepage':
           $data = array(
           'recipient' => array('id' => "$id" ),
           'message' => array("text" => " ",
           "quick_replies" => json_encode($keyboardSet)
            )
           );
           $date = array(
           'recipient' => array('id' => "$id" ),
           'message' => array(
                      "attachment" =>$URL
                              )
           );
        break;
    case 'en':
     if (checkUser($fp, $id) != false) {
            foreach ( $fp as $key=> $value) {
              if($key==$chat_id){
                 $fp[$key] = $postback;
              }
             }
             $arr3 = json_encode($fp);
             file_put_contents('user.json', $arr3);
             language($id,$postback);
          }
          else{
            AddUser($id,$fp,$postback);
          }
        break;
    case 'de':
      $fuck = file_get_contents('https://evilinsult.com/generate_insult.php?lang=de');
        break;
    default:
      $data = array(
           'recipient' => array('id' => "$id" ),
           'message' => array("text" => "$fuck",
           "quick_replies" => json_encode($keyboardSet)
            )
           );
}
//$data = array(
//     'recipient' => array('id' => "$id" ),
//      'message' => array("text" => "$fuck",
//                         "quick_replies" => json_encode($keyboardSet)
//                          )
//);

//$data = array(
//      'recipient' => array('id' => "$id" ),
//      'message' => array("attachment" => $attachment)
// );

language($chat_id);

 $options = array(
          'http' => array(
             'method' => 'POST',
             'content' => json_encode($data),
             'header' => "Content-Type: application/json"
             )
 );
 $option = array(
          'http' => array(
             'method' => 'POST',
             'content' => json_encode($date),
             'header' => "Content-Type: application/json"
             )
 );


function checkUser($mass,$chat_id){
    $is = false;
    foreach ( $mass as $key=> $value) {
        if($key==$chat_id){
            $is = true;
        }
    }
    return $is;
}

function AddUser($chat_id,$mass,$message){
    $mass[$chat_id] = $message;
    $arr3 = json_encode($mass);
    file_put_contents('user.json', $arr3);
    if($message =='en'){
        english($chat_id);
    }
    if($message =='de'){
        deutch($chat_id);
    }
}
function checkLanguage($mass,$chat_id){
    $language = 'en';
    foreach ( $mass as $key=> $value) {
        if($key==$chat_id){
            $language = $value;
        }
    }
    if($language =='en'){
        english($chat_id);
    }
    if($language =='de'){
        deutch($chat_id);
    }
}




$context = stream_context_create($options);
$contexts = stream_context_create($option);
file_get_contents("https://graph.facebook.com/v2.7/me/messages?access_token=$token",false, $contexts);
file_get_contents("https://graph.facebook.com/v2.7/me/messages?access_token=$token",false, $context);








//"attachment" => array(
//                               "type"=>"template",
//                               "payload" => array(
   //                                "template_type" => "button",
     //                             "text" => "What do you want",
       //                           "buttons" => array(
         //                              "type" => "postback",
           //                            "title" => "Start Chatting",
             //                          "payload" => "USER_DEFINED_PAYLOAD"
               //                        )
                 //                 )
                   //       )

//$keyboardSet ='[
//      {
//        "content_type":"text",
//        "title":"de",
//        "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_EN"
//      },
//      {
//        "content_type":"text",
//        "title":"en",
//        "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_DE"
//      }
//    ]';


