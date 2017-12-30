<?php namespace Libs\TaskO;

  //config constants for server connection
  require_once __DIR__ . '/../../../../config/db/db_constants.php';



  use \mysqli;

  /**
   **This class is used to perform some admin task operations
   */
  class TaskO {

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
     **This method is used to count the 'a particular' amount of task created by
     * the admin.. it is expecting to be asked to count all tasks or tasks with
     * a specific status
     **@param int $userId, string $constraint
     **@return bool TRUE if the user info was successfully added to database
     * without any error. FALSE otherwise.
     */
    public function taskCount($userId, $constraint='all'){
      //build up a query string and count the admin's created tasks
      $query = "SELECT COUNT(id) AS numberOfTasks FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
      if ($constraint != 'all') {
        # here, we are trying to count based on a constaint, most likely status
        $query = "SELECT COUNT(id) AS numberOfTasks FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId' AND `status` = '$constraint'";
      }
        if ($result = Self::$serverConn -> query($query)) {
          # user info has been successfully inserted into database so send
          return $result;
        }else {
          # user info could not be inserted at the moment so
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
     public function updateAdminUserInfo($info, $user_id){
       //build up a query parts for query string from info
       $query = "UPDATE `thestart_upstudio`.`tss_package_subscription` SET";
       foreach ($info as $key => $value) {
         # add key and value pair of info to $query
         //adding each key value pair to $query
         if (array_keys($info)[count($info) - 1] == $key) {
           # we have the last one of the set of info passed in so no comma
           $query .= " `$key` = '$value'";
         }else {
           # it is the first of a set or one of a set so add a comma
           $query .= " `$key` = '$value',";
         }
       }
       //add the final part to query
       $query .= " WHERE `tss_package_subscription`.`id` = '$user_id'";

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
<?php
  $test = new TaskO();
  //echo
  echo "count all from 1";
  var_dump($test->taskCount('1'));
  echo "<br>count all from 2";
  var_dump($test->taskCount('2'));
  echo "<br>count just created from 1";
  var_dump($test->taskCount('1',0));
    echo "<br>count just created from 2";
  var_dump($test->taskCount('2',0));
  echo "<br>count just in progress from 1";
  var_dump($test->taskCount('1',1));
  echo "<br>count just in progress from 2";
  var_dump($test->taskCount('2'1));

 ?>
