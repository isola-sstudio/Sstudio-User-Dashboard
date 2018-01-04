<?php namespace Libs\AdminUser;

  //config constants for server connection
  require_once __DIR__ . '/../../../../config/db/db_constants.php';



  use \mysqli;

  /**
   **This class is used to perform some User Requests concerning
   * a particular User account
   */
  class AdminUser {

    //setting server connection variables to private
    private static $serverConn;
    //end setting server connection variables

    //let's just connect to the server
    function __construct()
    {
      # connecting to the server
      Self::$serverConn = new mysqli(SERVER_NAME, USER, USER_PASSWORD);

      //check connection
      if (Self::$serverConn -> connect_error){
        //die('Hey! Couldn\'t connect to the Server! '. Self::$serverConn->connect_error);
        die('Server down! Please try again in a moment');
      }
    }

    /**
     **This method is used to create an admin user in the database when a
     * user signs up
     **@param string name, email, password, contactNumber
     **@return bool TRUE if the user info was successfully added to database
     * without any error. FALSE otherwise.
     */
    public function createAdminUserAccount($name, $email, $picture='', $cover='', $password=''){
      //build up a query string and create a user
      $password = md5($password);
      $query = "INSERT INTO `thestart_upstudio`.`tss_package_subscription`(`company_name`,
        `company_email`, `picture`, `wallpaper`, `password`)
        VALUES('$name', '$email', '$picture', $cover, '$password')";
        if (Self::$serverConn -> query($query) === TRUE) {
          # user info has been successfully inserted into database so send
          return TRUE;
        }else {
          # user info could not be inserted at the moment so
          return FALSE;
        }
    }

    /**
     **This method is used to log a user in by checking that their username and
     * and password exists
     **@param string $username, $password
     **@return bool TRUE if query executed successful and there was a record of
     * of the user in the database. FALSE if there was a server problem or if the
     * user does not exist in the database
     */
    public function logAdminUserIn($username, $password){
      $password = md5($password);
      // to check if the username and password match
      $query = "SELECT `id` FROM `thestart_upstudio`.`tss_package_subscription` WHERE `company_email` = '$username' AND `password` = '$password'";
      if ($result = Self::$serverConn->query($query)) {
        # if the query executed well
        if ($result->num_rows == 1) {
          # there was actually a record of the user in the database
          return TRUE;
        }else {
            # no record of the user was found. Maybe a wrong password or account
            // has not been created
            return FALSE;
          }
      }else {
          # some issues with the server
          return FALSE;
        }

    }

    /**
     * This method is used to log a user out by unsetting all SESSION variables
     *
     */
    public static function logAdminUserOut(){
      if (isset($_SESSION['user_id']) || isset($_SESSION['email'])) {
        # there is a session
        session_destroy();
      }
    }

    /**
     **Checks if a user is logged in
     **@return bool FALSE if the necessary session variables are not set or if
     * they are set but they do not match in database TRUE if the session
     * variables are set and they match with email
     */
    public function loggedIn(){
      if (((isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
      && (isset($_SESSION['email']) && !empty($_SESSION['email']))) ||
      (isset($_SESSION['fb_access_token']) && !empty($_SESSION['fb_access_token']))) {
        # if the necessary session variables are set for session
        return TRUE;
      }else {
        # just return FALSE since the necessary session variables are not even set
        return FALSE;
      }
    }

    /**
     **This method sends a user to the web root or whereever is defined as home
     */
    public static function sendAdminUserHome(){
      header('Location: /');
    }


    /**
     **This method is used to retrieve one or more columns about the user from
     * the database. User Info argument defaults to all in a situation where a
     * specific info to get is not specified
     **@param string $reference, $referenceValue, $userInfo
     **@return Mysqli object $result, string Returns the fetched user info
     * bool FALSE if the query was not successful.
     */
    public function getAdminUserInfo($reference, $referenceValue, $userInfo = '*'){
      $query = "SELECT `$userInfo` FROM `thestart_upstudio`.`tss_package_subscription` WHERE `$reference` = '$referenceValue'";
      echo $query;
      if ($result = Self::$serverConn->query($query)) {
        //query successful, return the mysqli object
        if ($result->num_rows == 1) {
          # there was in fact a result
          if ($userInfo != '*') {
            # the request is for just one field
            $row = $result->fetch_assoc();
            return $row["$userInfo"];
          }else {
              while ($row = $result->fetch_assoc()) {
                # load up row contents into a dynamic array
                $rowContents[] = $row;
              }
              // since we aree most likely dealing with a user
              foreach ($rowContents as $key => $value) {
                foreach ($value as $nextKey => $nextValue) {
                  # code...
                  $returnRow["$nextKey"] = $nextValue;
                }
              }
              return $returnRow;
            }
        }else {
            # there was no result
            return FALSE;
          }
      }else {
          # query was not successful
          return FALSE;
        }
    }

    /**
     **This method is used to update info for an existing user in the database.
     **It accepts an associative array corresponding to table column name and value
     **It needs user id, passed in
     **@param Assoc Array $info, Int $userId
     **@return bool TRUE if all details passed in were successfully updated in
     *database. FALSE otherwise.
     */
     public function updateAdminUserInfo($info, $userId){
       //build up a query parts for query string from info
       $query = "UPDATE `thestart_upstudio`.`tss_package_subscription` SET";
       foreach ($info as $key => $value) {
         # add key and value pair of info to $query
         //adding each key value pair to $query
         if (array_keys($info)[count($info) - 1] == $key) {
           # we have the last one of the set of info passed in so no comma
           $query .= " `$key` = \"$value\"";
         }else {
           # it is the first of a set or one of a set so add a comma
           $query .= " `$key` = \"$value\",";
         }
       }
       //add the final part to query
       $query .= " WHERE `tss_package_subscription`.`id` = '$userId'";

       //perform the update
       if ($result = Self::$serverConn->query($query)) {
         # query execution was successful
         if (Self::$serverConn->affected_rows == 1) {
           # user info has been successfully inserted into database so send
           return TRUE;
         }else {
             # user info was not updated most likely due to wrong id or something else
             return FALSE;
           }
       }else {
           # user info could not be inserted at the moment due to server issue
           return FALSE;
         }
     }
  }

?>
