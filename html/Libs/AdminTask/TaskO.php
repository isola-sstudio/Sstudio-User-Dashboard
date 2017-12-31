<?php namespace Libs\AdminTask;

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
        # the query ran successfully, so return the number of Tasks
        $row = $result->fetch_assoc();
        return $row['numberOfTasks'];
      }else {
          # the query could not run.. definitely server issues
          return FALSE;
        }
    }

    /**
     **This method is used to retrieve info about a task or number, or
     * collection of tasks from the database.
     **To get a distinct set of values, set the distinct variable to TRUE
     **@param string $reference, $referenceValue, $userInfo
     **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
     * there was server issues and the query was not successful
     */
    public function getTasksInfo($userId, $taskInfo, $limit='', $constraint='', $order='', $distinct=FALSE){
      $query = "SELECT $taskInfo FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
      //check if we are to select distinct values
      if ($distinct) {
        # add distinct to query string
        $query = "SELECT DISTINCT $taskInfo FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
      }
      // in a situation where we have another constraint
      if (isset($constraint) && !empty($constraint)) {
        # there is a constraint, so we add up to the query
        $constraintKey = $constraint['key'];
        $constraintValue = $constraint['value'];
        $query .= " AND `$constraintKey`='$constraintValue'";
      }
      // in a situation where we have an order type to follow
      if (isset($order) && !empty($order)) {
        # there is an order type, so we add up to the query
        $orderColumn = $order['column'];
        $orderType = $order['type'];
        $query .= " ORDER BY `$orderColumn` $orderType";
      }
      // in a situation where we have a limit amount
      if (isset($limit) && !empty($limit)) {
        # there is a constraint, so we add up to the query
        $query .= " LIMIT $limit";
      }

      if ($result = Self::$serverConn->query($query)) {
        //query successful, return the mysqli object
        if ($result->num_rows >= 1) {
          # there was in fact a result
          while ($row = $result->fetch_assoc()) {//while there is still one
            # while there is still something from the task info column
            $rowContent[] = $row["$taskInfo"];//dynamically initiate an array to return
          }
          return $rowContent;
        }else {
            # there was no result
            return FALSE;
          }
      }else {
          # query was not successful
          return FALSE;
        }
    }

    public function getTasks(){

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
  // echo "bring in tasks without any additional stuffs<br>";
  // echo "<br>bring in tasks with a constraint<br>";
//      var_dump($test->getTasksInfo(1, "MONTHNAME(created)", 5, '', array('column' => 'created', 'type'=>'DESC' ), TRUE);
//$userId, $taskInfo, $limit='', $constraint='', $order='', $distinct=FALSE
     // echo date('Y',$test->getTasksInfo(1, 'MONTH(created)'));
   var_dump($test->getTasksInfo(1, 'MONTHNAME(created)', 3, '', array('column' => 'MONTHNAME(created)', 'type' => 'DESC'), TRUE));
  // echo "<br>bring in tasks with an order<br>";
  // var_dump($test->getTasksInfo(1, 'task_name', '', array('key' => 'status', 'value'=>'0'), array('column' => 'created', 'type'=>'DESC')));
  // echo "<br>bring in tasks with an order and a limit<br>";
  // var_dump($test->getTasksInfo(1, 'task_name', '3', array('key' => 'status', 'value'=>'0'), array('column' => 'created', 'type'=>'DESC')));
  // echo "<br><br><br>bring in tasks with an order and a limit<br>";
  // var_dump($test->getTasksInfo(1, 'MONTH(created)', '3', array('key' => 'status', 'value'=>'0'), array('column' => 'created', 'type'=>'DESC')));

?>
