<?php namespace Libs\SuperUser;

  //config constants for server connection
  require_once __DIR__ . '/../../../../config/db/db_constants.php';



  use \mysqli;

  /**
   **This class is used to perform some admin task operations
   */
  class SuperUserO {

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
     **This method is used by a super user to update a task most especially to
     * update the progress info
     **@param string $userId, $taskId, $updates
     **@return bool TRUE if the task was successfully updated
     * without any error. FALSE otherwise.
     */
    public function updateTask($userId, $taskId, $updates){
      //build up a query string and update task
      $query = "UPDATE `thestart_upstudio`.`admin_task` SET";
      foreach ($updates as $key => $value) {
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
      $query .= " WHERE  `admin_task`.`user_id` = '$userId' AND `admin_task`.`id` = '$taskId'";

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

    /**
     **This method is used by the superuser to retrieve info about a task or
     * number, or collection of tasks from the database.
     **To retrieve all columns, just pass in * for $taskInfo
     **$constraint should be passed in, in the form of an series of associative
     * arrays inside an index array
     **@param string $taskInfo, $limit, Array $constraint, $order
     **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
     * there was server issues and the query was not successful
     */
    public function getTasksInfo($taskInfo, $limit='', $constraint, $order=''){
      $query = "SELECT $taskInfo FROM `thestart_upstudio`.`admin_task` WHERE";
      // call the constraints
      foreach ($constraint as $constraintSet) {
        # so we can have multiple constraints, we pass in a multidimensional array
        $constraintSetKey = $constraintSet['key'];
        $constraintSetOperator = $constraintSet['operator'];
        $constraintSetValue = $constraintSet['value'];

        if (array_keys($constraint)[0] == $key) {
          # we have the first one of the set of constraints passed in so do not prefix AND
          $query .= " `$constraintSetKey` $constraintSetOperator \"$constraintSetValue\"";
        }else {
          # it is another constraint so prefix AND
          $query .= " AND `$constraintSetKey` $constraintSetOperator \"$constraintSetValue\"";
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
}








?>
<?php

  // $test = new TaskO();
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
// var_dump($test->getTasksInfo(1, 'id', '', array(array('key' => 'task_name','operator'=>'=', 'value'=>"It's your boy Whizzy"),array('key' => 'task_description','operator'=>'=', 'value'=>"No problem"))));
?>
