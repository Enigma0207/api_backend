<?php
// inclure la fonction dans index
require_once "fonction.php";
// si la request existe avek la méthod get
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    // pour recuperer url
    $url = $_SERVER["PATH_INFO"];
//    echo json_encode([
//        "status" => 200,
//        "message"=>"ok"
//    ]);
} else {
//      echo json_encode([
//        "test" => "ok",
    
//    ]);
// ce que l'utilsateur envoit dans le formulaire non le recupere via un formulaire on le recupere dans la la variable $data
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
