<?php
try{
    $bdd = new PDO("mysql:host=localhost;dbname=sky_travel;charset=UTF8", "root", "");
}
catch(PDOExeption $e){
    echo $e -> getMessage();
    die();
}
?>