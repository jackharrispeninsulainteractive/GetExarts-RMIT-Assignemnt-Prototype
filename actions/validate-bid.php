<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 20/06/2022
 */

//***** CREATE OUR BID VALIDATION SCRIPT VARIABLES *****\\

//response array containing json response data
$response = [];
//the bid id used to target this specific bid
$bid_id = null;
//the amount the user is validating the bid for
$amount = null;
//the users valid login token
$token = null;
//create the bid object
$bid_sql_array = [];

//***** VALIDATE OUR BID ID FIELD *****\\
if(isset($_POST["bid_id"])){
    $bid_id = trim($_POST["bid_id"]);
}
//validate the bid id is not null
if($bid_id == null){
    $response["error"]["bid_id"] = "no bid id provided";
    returnResponse($response);
}
//validate the bid id is numeric
if(!is_numeric($bid_id)){
    $response["error"]["bid_id"] = "bid id must be a numerical integer";
    returnResponse($response);
}
//validate this is a valid bid id
$sql = "SELECT * FROM bids WHERE bid_id='$bid_id'";
$result =  Database::query($sql)->fetch_all(MYSQLI_ASSOC);
if($result === [] || $result === null){
    $response["error"]["bid_id"] = "invalid bid id provided";
    returnResponse($response);
}
$bid_sql_array = $result;

//***** VALIDATE OUR BID AMOUNT *****\\
if(isset($_POST["amount"])){
    $amount = trim($_POST["amount"]);
}
if($amount == null){
    $response["error"]["amount"] = "no bid validation amount provided";
    returnResponse($response);
}
//validate the amount is numeric
if(!is_numeric($amount)){
    $response["error"]["amount"] = "amount must be a numerical integer";
    returnResponse($response);
}

//***** VALIDATE OUR TOKEN *****\\
if(isset($_POST["token"])){
    $token =  $_POST["token"];
}

$user = User::getUserFromSessionToken($token);

//validate our token is valid by attempting to get the user from the token
if($user === null){
    $response["error"]["token"] = "invalid login token provided";
    returnResponse($response);
}

//***** VALIDATE OUR CALLING USER IS THE ONE WHO CREATED THE BID *****\\
if($user->getId() != $bid_sql_array[0]["user_id"]){
    $response["error"]["token"] = "user attempting to validate someone else bid, please validate your own bids only";
    returnResponse($response);
}

$result = User::validateBid($bid_id,$user->getId(),$amount,$bid_sql_array[0]["hash"]);

if(!$result){
    $response["error"]["general"] = "invalid bid amount, please try again";
}
returnResponse($response);


