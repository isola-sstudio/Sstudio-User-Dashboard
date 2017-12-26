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
     **This method is used to insert a new user in the database
     *
     */
    function createAdminUserAccount(){

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
    function logAdminUserOut(){

    }

    /**
     **Checks if a user is logged in
     **@return bool FALSE if the necessary session variables are not set or if
     * they are set but they do not match in database TRUE if the session
     * variables are set and they match with email
     */
    public function loggedIn(){
      if ((isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
      && (isset($_SESSION['email']) && !empty($_SESSION['email']))) {
        # if the necessary session variables are set for session
        if ($_SESSION['email'] == $this->getAdminUserInfo('id', $_SESSION['user_id'], 'company_email')) {
          # means the set session variables(user_id and email) are explicitly correct
          return TRUE;
        }else {
          return FALSE;
        }
      }else {
        # just return FALSE since the necessary session variables are not even set
        return FALSE;
      }












    }

    /**
     **This method is used to retrieve one or more columns about the user from
     * the database. User Info argument defaults to all in a situation where a
     * specific info to get is not specified
     **@param string $reference, $value, $userInfo
     **@return Mysqli object $result, string Returns the fetched user info
     * bool FALSE if the query was not successful.
     */
    public function getAdminUserInfo($reference, $value, $userInfo = '*'){
      $query = "SELECT $userInfo FROM `thestart_upstudio`.`tss_package_subscription` WHERE `$reference` = '$value'";
      if ($result = Self::$serverConn->query($query)) {
        //query successful, return the mysqli object
        if ($result->num_rows == 1) {
          # there was in fact a result
          if ($userInfo != '*') {
            # the request is for just one field
            $row = $result->fetch_assoc();
            return $row["$userInfo"];
          }else {
              # return the mysqli object
              return $result;
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



  }

?>
<?php
  $test = new AdminUser();
?>
