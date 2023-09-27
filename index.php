<?php
header ("Access-Control-Allow-Origin: *");
// inclure la fonction dans index
require_once "fonction.php";
// si la requestque lutilisateur existe et GET 
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    // pour recuperer cek utilisateur en envoyer auniveau de url comme requete
    // REQUEST_URI des constante
    $url = $_SERVER["REQUEST_URI"];
    // cette ligne avk trim va enlever \ du debut /et la fin et quant on clique sur   send Request sur 
// GET http://localhost/api_backend/getuserlist/
    $url =trim($url,"\/");
    // convertir en tableau une chaine de caractère
     $url = explode("/", $url);
     $action = $url[1];

//    echo json_encode([
//        "status" => 200,
//        "data"=>$url
//    ]);
    if($action == "getuserlist"){

        getListUser();

    } else if ($action == "geListMessage") {
        // par rapport a la fonction message list dans api
              getListMessage($url[2], $url[3]);
    }else {
        echo json_encode([
        "status" => 404,
        "message" => "not found",
    
        ]);
    }
  } else {
//      echo json_encode([
//        "test" => "ok",
    
//    ]);
// ce que l'utilsateur envoit dans le formulaire non le recupere via un formulaire on le recupere dans la la variable $data(avec la fonction:file_get_contents)
$data = json_decode(file_get_contents("php://input"), true);

 if($data["action"] == "login"){
        // appel de la fonction login
        login($data['pseudo'], $data['password']);
    }else if($data["action"] == "register"){
        // on fait appel a la fonction register pour enregistrer le user
        register($data['firstname'], $data['lastname'], $data['password'], $data['pseudo']);
    }else if($data["action"] == "send message"){
        // appel de la fonction sendMessage
        sendMessage($data['expeditor'],$data['receiver'],$data['message']);
    }else{
        echo json_encode([
            "satus"     => 404,
            "message"   => "service not found"
        ]);
    }
}
