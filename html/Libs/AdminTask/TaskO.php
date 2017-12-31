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
     **To retrieve all columns, just pass in * for $taskInfo
     **$constraint should be passed in, in the form of an series of associative
     * arrays inside an index array
     **@param string $reference, $referenceValue, $userInfo
     **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
     * there was server issues and the query was not successful
     */
    public function getTasksInfo($userId, $taskInfo, $limit='', $constraint='', $order='', $distinct=FALSE){
      $query = "SELECT `$taskInfo` FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
      //check if we are to select distinct values
      if ($distinct) {
        # add distinct to query string
        $query = "SELECT DISTINCT `$taskInfo` FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
      }
      // in a situation where we have another constraint
      if (isset($constraint) && !empty($constraint)) {
        # there is a constraint, so we add up to the query
        // $constraintKey = $constraint['key'];
        // $constraintOperator = $constraint['operator'];
        // $constraintValue = $constraint['value'];
        foreach ($constraint as $constraintSet) {
          # so we can have multiple constraints, we pass in a multidimensional array
          $constraintSetKey = $constraintSet['key'];
          $constraintSetOperator = $constraintSet['operator'];
          $constraintSetValue = $constraintSet['value'];

          $query .= " AND `$constraintSetKey` $constraintSetOperator '$constraintSetValue'";
        }
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
          if ($taskInfo == '*') {
            # if we were asked to bring all columns
            while ($row = $result->fetch_assoc()) {//while there is still one
              # while there is still something from the task info column
              $rowContents[] = $row;//dynamically initialize an array to return
            }
          }else {
              # we were asked to retrieve a particular column
              while ($row = $result->fetch_assoc()) {//while there is still one
                # while there is still something from the task info column
                $rowContents[] = $row["$taskInfo"];//dynamically initialize an array to return
              }
            }
           return $rowContents;
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
     **This method is used to retrieve the most recent months from the task table
     **@param string $userId, $monthConstraint, $limit
     **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
     * there was server issues and the query was not successful
     */
    public function getReccentTasksMonths($userId, $limit){
      $query = "SELECT DISTINCT MONTHNAME(created) FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
      //add constraints
      $query .= " AND YEAR(created) = YEAR(CURDATE())";
      //add order
      $query .= " ORDER BY MONTHNAME(created) DESC";
      //add limit
      $query .= " LIMIT $limit";

      if ($result = Self::$serverConn->query($query)) {
        //query successful, return the mysqli object
        if ($result->num_rows >= 1) {
          # there was in fact a result
          while ($row = $result->fetch_assoc()) {//while there is still one
            # while there is still something from the created column
            $rowContents[] = $row['MONTHNAME(created)'];//dynamically initialize an array to return
          }

          return $rowContents;
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
     **So the previous method would have certify for this situation but because
     * of some work arounds that i could not fix.. i terribly had to resolve to
     * this
     **This method is used to retrieve info about reent tasks in a particular month
     **@param string $userId, $monthConstraint, $limit
     **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
     * there was server issues and the query was not successful
     */
    public function getReccentTasks($userId, $monthConstraint, $limit=''){
      $query = "SELECT * FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
      //add constraints
      $query .= " AND MONTHNAME(created) = '$monthConstraint' AND YEAR(created) = YEAR(CURDATE())";
      //add order
      $query .= " ORDER BY `created` DESC";

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
            $rowContents[] = $row;//dynamically initialize an array to return
          }

          return $rowContents;
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
         // var_dump($test->getTasksInfo(1, 'created', 5, '', array('column' => 'created', 'type'=>'DESC' ), TRUE));
  // echo "<br>bring in tasks with an order<br>";
      // var_dump($test->getTasksInfo(1, 'task_name', '', array(array('key' => 'status', 'operator'=>'=', 'value'=>'0')), array('column' => 'created', 'type'=>'DESC')));
  // echo "<br>bring in tasks with an order and a limit<br>";
       // var_dump($test->getTasksInfo(1, 'task_name', '3', array(array('key' => 'status','operator'=>'=', 'value'=>'0')), array('column' => 'created', 'type'=>'DESC')));
  // echo "<br><br><br>bring in tasks with an order and a limit<br>";
        // var_dump($test->getTasksInfo(1, 'task_name', '3', array(array('key' => 'status','operator'=>'=', 'value'=>'0')), array('column' => 'created', 'type'=>'DESC')));
//  var_dump($variable = $test->getReccentTasks(1, 'December', 1));
// echo "<br>";
// echo "<br>";echo "<br>";
// foreach ($variable as $k => $v) {
// //  foreach ($v as $key) {
//     # code...
//     echo "<br><br><br><br><br><br>";
//     echo $v['task_name'];
//     var_dump($v);
//   // }
// }
?>
