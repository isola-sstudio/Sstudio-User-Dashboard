<?php namespace Libs\Validation;

  require_once __DIR__ . '/../../vendor/autoload.php';
  //config constants for server connection
  require_once __DIR__ . '/../../../config/db/db_constants.php';

  use \mysqli;

  use Respect\Validation\Validator as v;

  /**
   **This class is intended for validation mostly validating inputs
   **It extends v, the Validator class of Respect\Validation
   */
  class Validation extends v {

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
     **This method checks an array of arguments in a combined way (bitwise) to see
     * if any of it is not set, empty or just a space character. It returns true if any
     * of the member of the array is not set, empty or a space character
     *Requires PHP 5.6+
     **@param string ..$arguments
     **@return bool TRUE if there is an empty argument in the list. FALSE otherwise
     */
    public static function isBlank(...$arguments){
      foreach ($arguments as $argument) {
        if(!(isset($argument)) || empty($argument) || $argument == ' '){
          return TRUE;
        }else {
            continue;
          }
      }
    }

    /**
     **Checks if a value exists in the database e.g. username or email
     **@param string $key, $value
     **@return bool TRUE if value exists in database. FALSE otherwise
     */
    public function ifExists($key, $value){
      //check if value exists
      $query = "SELECT `id` FROM `thestart_upstudio`.`tss_package_subscription` WHERE `$key` = '$value'";
      $result = Self::$serverConn->query($query);
      if ($result && $result->num_rows > 0) { // just to kill the notice when $result is non-object
        # value exists
        return TRUE;
      }else {
          # value does not exist so
          return FALSE;
        }
    }



  }


?>
<?php
  $test = new Validation();
?>
