<?php
  require('authCheck.php');
  if(!isset($USER->id)) return;
  require('queries/pageQueries.php');
  $PAGE->id='pageDelete';

  $fields=array('pageid');
  $requiredFields=array('pageid');
  $inputs=array();
  parse_str(file_get_contents("php://input"),$_PUT);

  //check POST object for variables from front end
  foreach($fields as $postKey){
    if(isset($_PUT[$postKey])){
      $inputs[$postKey]=$_PUT[$postKey];
    }
  }

  //check inputs for all required fields 
  foreach($requiredFields as $postKey){
    if(!isset($inputs[$postKey]) || empty($inputs[$postKey])){
      return errorHandler("missing $postKey", 503);
    }
  }

  //print debug statement
  if($SERVERDEBUG){
    echo "\r\n inputs:";
    echo json_encode($inputs);
  }

  //setup for query
  $stmt = deletePage($DB, $inputs['pageid']);
  if(!$stmt) return; // getLists already send error.
  if(!$stmt->execute()) return errorHandler("failed to delete this page $stmt->errno: $stmt->error", 503);

  if($stmt->affected_rows != 1){
    return errorHandler("Deleted $stmt->affected_rows rows", 503);
  }

?>