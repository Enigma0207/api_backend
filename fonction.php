<?php
// fonction pour se connecter
function dbConnect(){
    $conn=null;
    try {
         $conn=new PDO("mysql:host=localhost;dbname=api_db", "root","");
    } catch (PDOException $e) {
     $conn= $e->getMessage();
    }
    return $conn;
}


// function

// function 1  pour registre
function register($firstname, $lastname, $password,$pseudo){

    // cript
    $passwordCrypt = password_hash($password, PASSWORD_DEFAULT);

    // connexion à la bdd
    $db = dbConnect();
    // prepare la request
    $request = $db->prepare("INSERT INTO user(pseudo,firstname, lastname, password) VALUES (?,?,?,?)");
    //execuet
    try {
        $request->execute(array($pseudo, $firstname, $lastname, $passwordCrypt));
        echo json_encode([
            "status" => 201,
            "message" => "everything good",
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "status" => 500,
            "message" => "internal server error",
        ]);
    }
}


// function 1  pour se connecter
function login ($pseudo,$password){
    //se connecter a la bd
    $db=dbConnect();
    //requete pour se connecter 
    $request=$db->prepare("SELECT * FROM user WHERE pseudo=?");
    // executer la requete
    try{
        $request->execute(array($pseudo));
        // recuperer la reponse de la requete
        $user1=$request->fetch(PDO::FETCH_ASSOC);
        // verifier si l'utilisateur n'existe
        if(empty($user1)){
            // retourner à l'utilisateur ceci
            echo json_encode([
                "status" =>404,
                "message" =>"user not found"
            ]);
        }else{
            // verifier si le password est correct
            if(password_verify($password,$user1['password'])){
                // si cest correct envoi a l'utilisateur ceci
                echo json_encode([
                    "status" =>200,
                    "message" =>"felicitation...",
                    "userInfo" => $user1
                ]);
            }
            else {
                // si me mdp est incorrect afficher ceci
                 echo json_encode([
                    "status" =>401,
                    "message" =>"passeword incorrect..."
                   
                ]);
            }
               
        }
    }catch(PDOException $e){
        echo json_encode([
            "status"=>500,
            "message"=>$e->getMessage()
        ]);
    }
}



// fonction pour envoyer des message
function sendMessage($expeditor, $receiver, $message){
    // se connecter à la bdd
    $db = dbConnect();
    $request = $db->prepare("INSERT INTO messages(message, expeditor_id, receiver_id) VALUES (?,?,?)");

    // executer
    try {
        $request->execute(array($message, $expeditor,$receiver));
        echo json_encode([
            "status"=>201,
            "message"=>"your message is safely sent..."
        ]);
    } catch (PDOException $e){
        echo json_encode([
            "status"=>404,
            "message"=>"service not found"
        ]);
    }
}

// fonction pour recupere la liste des user

function getListUser(){
    // se connecter
    $db = dbConnect();
      $request = $db->prepare("SELECT *FROM user" );
      try{
      $request->execute();
    //   recupere la liste
    $listeUsers =  $request->fetchAll(PDO::FETCH_ASSOC);
     
    echo json_encode([
            "status"=>200,
            "message"=>"voici la liste des utilisateurs",
            "users"=>$listeUsers
        ]);
      }catch(PDOException $e){
      echo json_encode([
            "status"=>500,
            "message"=>$e->getMessage()
           
        ]);
     }

}


// fonction pour recuper la conversation entre 2 users

function getListMessage ($expeditor, $receiver){
    $db = dbConnect();
    $request = $db->prepare("SELECT *FROM messages WHERE expeditor_id = ? AND receiver_id=? OR expeditor_id = ? AND receiver_id = ?");
    try {
     $request->execute(array($expeditor, $receiver, $receiver,$expeditor));
     $messages = $request->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            "status"=>200,
            "message"=>"voici la liste de votrre discution",
            "listMessage"=>$messages
           
        ]);

    } catch (PODException  $e) {
       echo json_encode([
            "status"=>500,
            "message"=>$e->getMessage()
           
        ]);
    }
}
