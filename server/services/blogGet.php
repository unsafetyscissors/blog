<?php
  require('queries/blogQueries.php');
  $PAGE->id='blogGet';

  //get inputs. requires listId
  $requiredField='blogid';
  $input='';
  if(isset($_GET[$requiredField]) && !empty($_GET[$requiredField])){
    $input=$_GET[$requiredField];
  }else{
    return errorHandler("missing $requiredField", 503);
  }

  //setup for query
  $stmt = getBlog($DB, $input);
  if(!$stmt) return; // getLists already send error.
  if(!$stmt->execute()) return errorHandler("failed to get this list $stmt->errno: $stmt->error");
  //format results
  $data = array();
  $stmt->bind_result($data['blogId'],$data['blogDate'],$data['blogName'],$data['blogDesc'],$data['blogTitle'],$data['blogHtml'],$data['blogFeature']);

  /* fetch values */
  $listResults = array();
  while ($stmt->fetch()) {
    $row = arrayCopy($data);
    array_push($listResults, $row);
  }
  echo json_encode($listResults);

  function arrayCopy( array $array ) {
    $result = array();
    foreach( $array as $key => $val ) {
        if( is_array( $val ) ) {
            $result[$key] = arrayCopy( $val );
        } elseif ( is_object( $val ) ) {
            $result[$key] = clone $val;
        } else {
            $result[$key] = $val;
        }
    }
    return $result;
  }
?>