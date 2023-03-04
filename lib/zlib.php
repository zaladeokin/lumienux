<?php
/*
                ______________________________________________
               |    DOC FOR ZLib utility                      |
               ________________________________________________

#Import Database library or set database connection before importing ZLib for Database operation

#start session before importing ZLib


*/

function createTable($tname, $tProperty, $pdo){
    /* 
    Uses PDO library to create table for database at the point of setting up database for Application.
    */
try{
  $pdo->query("CREATE TABLE IF NOT EXISTS $tname($tProperty) ENGINE = InnoDB");
  echo "<h1>Table '$tname' created or already exists.</h1><br>";
}catch(Exception $e){
  error_log("Database(Admin) error  ::::". $e->getMessage());
  echo "<h1>An error occurred.</h1>";
  }
}

function flashMessage($sesName){
    /*
    Flash error or status messages.
    # $sesName is the key of $_SESSION
    */
    if ( isset($_SESSION[$sesName]) ) {
        echo $_SESSION[$sesName];
        unset($_SESSION[$sesName]);
      }
}

function repopulate($sesName, $dmsg="", $unset= true){
  /*
  Repopulate form.
# $sesName is key of session
# $unset (default is true), if false will not unset session
# $dmsg (optional) set default message.
  */
  if ( isset($_SESSION[$sesName]) ) {
    $data= htmlentities($_SESSION[$sesName]);
    if($unset){
      unset($_SESSION[$sesName]);
    }
  }else{
    $data=$dmsg;
  }
  return $data;
}

function FormFlashMsg($sesName){
  /*
    Display error message or notice in form field before <label> preceding <input> tag.....
    Notice: validation.css must be link in <head> for this function to work with an attribute class="zl-form-info" added to each input <label>.
    Function is to be called inside <label>. for example:
      <label for="v2" class="zl-form-info" <?= FormFlashMsg('test'); ?> >Test</label>
      <input type="text" name='test' id="v2">

    To set your own color and background, override the below selector in your css:
        .zl-form-info::before{}


  # $sesName is the session key for flashmessage
  # This function uses repopulate() function....
  */
  $info= repopulate($sesName);
  $info= $info !="" ? "data-error='$info'" : "";
  return $info;
}

function reCaptchaVerify($secret, $response){
  /*
  reCapthaVerify() is for serverside verification of goole reCaptcha. it return JSON.
  */
  $url= "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$_SERVER[REMOTE_ADDR]";
  $validate= json_decode(file_get_contents($url));
  return $validate;
}

function pagination($num_of_row, $limit, $current_page){
  /**
   * Notice: session must me started to utilize this function because it's going to set $_SESSION['total_page']
   * $num_of_row is the total number of data in the Database table
   * $limit is the total number to be return per page
   * $current_page is the value of either $_GET or $_POST representing the current page
   * minimum value for $current_page is 1
   * pagination() returns an array of data of type Integer in the following order: [$offset, $next, $previous]
   * $offset is the database column number to start retrieving data
   * $next is the next page value 
   * $previous is the previous page value
   * $next and $previous will be zero(0) if $num_of row is lesser or equal to limit
   */

   if($num_of_row/$limit > 1){//Check if total number  > than limit
    $_SESSION['total_page']= ($num_of_row%$limit == 0) ? $num_of_row/$limit : intdiv($num_of_row, $limit) + 1;
  }else{
    $_SESSION['total_page']= 1;
  }
  $current_page= intval($current_page);
  $current_page= ($current_page >= 1) ? $current_page : 1; 
  $offset= ($current_page <= $_SESSION['total_page']) ? $limit * ($current_page - 1) : 0;
  $next= ($current_page < $_SESSION['total_page']) ? $current_page + 1 : 0;
  $previous= ($current_page <= $_SESSION['total_page'] && $current_page > 1) ? $current_page - 1 : 0;

  return array(
    'offset' => $offset,
    'next' => $next, 
    'previous' => $previous
  );
  /**
   * use $limit and $offset on SQL statement
   * use conditional flow to hide $next and $previous when there value is zero
   */
}
?>