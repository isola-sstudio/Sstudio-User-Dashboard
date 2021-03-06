<?php namespace Libs\AdminBilling;

  //config constants for server connection
  require_once __DIR__ . '/../../../../config/db/db_constants.php';



  use \mysqli;

  /**
   **This class is used to perform some admin task operations
   */
  class BillingO {

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
    **This method is used to retrieve info about a billing event or a number, or
    * collection of billing events from the database.
    **To retrieve all columns, just pass in * for $billingInfo
    **$constraint should be passed in, in the form of a series of associative
    * arrays inside an index array
    **@param string $reference, $referenceValue, $userInfo
    **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
    * there was server issues and the query was not successful
    */
    public function getBillingInfo($userId, $billingInfo, $limit='', $constraint='', $order=''){
      $query = "SELECT $billingInfo FROM `thestart_upstudio`.`admin_billing` WHERE `user_id` = '$userId'";
      // in a situation where we have another constraint
      if (isset($constraint) && !empty($constraint)) {
        # there is a constraint, so we add up to the query
        foreach ($constraint as $constraintSet) {
          # so we can have multiple constraints, we pass in a multidimensional array
          $constraintSetKey = $constraintSet['key'];
          $constraintSetOperator = $constraintSet['operator'];
          $constraintSetValue = $constraintSet['value'];

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
          if ($billingInfo == '*') {
            # if we were asked to bring all columns
            while ($row = $result->fetch_assoc()) {//while there is still one
              # while there is still something from the billinginfo column
              $rowContents[] = $row;//dynamically initialize an array to return
            }
          }else {
            # we were asked to retrieve a particular column
            while ($row = $result->fetch_assoc()) {//while there is still one
              # while there is still something from the billinginfo column
              $rowContents[] = $row["$billingInfo"];//dynamically initialize an array to return
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










    // /**
    //  **This method is used to create a task when a user submits a task form
    //  **@param string $userId, $taskName, $taskDescription
    //  **@return bool TRUE if the task was successfully created
    //  * without any error. FALSE otherwise.
    //  */
    // public function createTask($userId, $taskName, $taskPriority, $dueDate, $taskDescription){
    //   if ($this->getTasksInfo($userId, 'id', '', array(array('key' => 'task_name','operator'=>'=', 'value'=>"$taskName"),array('key' => 'task_description','operator'=>'=', 'value'=>"$taskDescription")))) {
    //     # check if the task has been created already, in order to avoid multiple
    //     // duplicate
    //     return "possibly duplicate";
    //   }else {
    //       # not a duplicate so
    //       //build up a query string and create a user
    //       $query = "INSERT INTO `thestart_upstudio`.`admin_task`(`user_id`,
    //         `task_name`, `task_priority`, `due_date`, `task_description`)
    //         VALUES('$userId', '$taskName', '$taskPriority', '$dueDate', '$taskDescription')";
    //         if (Self::$serverConn -> query($query) === TRUE) {
    //           # the task has been created successfully
    //           return TRUE;
    //         }else {
    //           # task could not be created at the moment so
    //           //try again with mysqli::escape_string
    //          $taskName = Self::$serverConn->escape_string($taskName);
    //          $taskDescription = Self::$serverConn->escape_string($taskDescription);
    //          //set the query again
    //          $query = "INSERT INTO `thestart_upstudio`.`admin_task`(`user_id`,
    //            `task_name`, `task_priority` `task_description`)
    //            VALUES('$userId', '$taskName', '$taskPriority', '$taskDescription')";
    //             if (Self::$serverConn -> query($query) === TRUE) {
    //               # the task has been created successfully
    //               return TRUE;
    //             }else {
    //               # Still, the task could not be created
    //               return FALSE;
    //             }
    //         }
    //     }
    // }
    //
    // /**
    //  **This method is used to count the 'a particular' amount of task created by
    //  * the admin.. it is expecting to be asked to count all tasks or tasks with
    //  * a specific status
    //  **@param int $userId, string $constraint
    //  **@return bool TRUE if the user info was successfully added to database
    //  * without any error. FALSE otherwise.
    //  */
    // public function taskCount($userId, $constraint='all'){
    //   //build up a query string and count the admin's created tasks
    //   $query = "SELECT COUNT(id) AS numberOfTasks FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
    //   if ($constraint != 'all') {
    //     # here, we are trying to count based on a constaint, most likely status
    //     $query = "SELECT COUNT(id) AS numberOfTasks FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId' AND `status` = '$constraint'";
    //   }
    //   if ($result = Self::$serverConn -> query($query)) {
    //     # the query ran successfully, so return the number of Tasks
    //     $row = $result->fetch_assoc();
    //     return $row['numberOfTasks'];
    //   }else {
    //       # the query could not run.. definitely server issues
    //       return FALSE;
    //     }
    // }
    //
    //
    // /**
    //  **This method is used to retrieve the most recent months from the task table
    //  **@param string $userId, $monthConstraint, $limit
    //  **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
    //  * there was server issues and the query was not successful
    //  */
    // public function getReccentTasksMonths($userId, $limit){
    //   $query = "SELECT DISTINCT MONTHNAME(created) FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
    //   //add constraints
    //   $query .= " AND YEAR(created) = YEAR(CURDATE())";
    //   //add order
    //   $query .= " ORDER BY MONTHNAME(created) DESC";
    //   //add limit
    //   $query .= " LIMIT $limit";
    //
    //   if ($result = Self::$serverConn->query($query)) {
    //     //query successful, return the mysqli object
    //     if ($result->num_rows >= 1) {
    //       # there was in fact a result
    //       while ($row = $result->fetch_assoc()) {//while there is still one
    //         # while there is still something from the created column
    //         $rowContents[] = $row['MONTHNAME(created)'];//dynamically initialize an array to return
    //       }
    //
    //       return $rowContents;
    //     }else {
    //         # there was no result
    //         return FALSE;
    //       }
    //   }else {
    //       # query was not successful
    //       return FALSE;
    //     }
    // }
    //
    // /**
    //  **So the previous method would have certify for this situation but because
    //  * of some work arounds that i could not fix.. i terribly had to resolve to
    //  * this
    //  **This method is used to retrieve info about reent tasks in a particular month
    //  **@param string $userId, $monthConstraint, $limit
    //  **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
    //  * there was server issues and the query was not successful
    //  */
    // public function getReccentTasks($userId, $monthConstraint, $limit=''){
    //   $query = "SELECT * FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
    //   //add constraints
    //   $query .= " AND MONTHNAME(created) = '$monthConstraint' AND YEAR(created) = YEAR(CURDATE())";
    //   //add order
    //   $query .= " ORDER BY `created` DESC";
    //
    //   // in a situation where we have a limit amount
    //   if (isset($limit) && !empty($limit)) {
    //     # there is a constraint, so we add up to the query
    //     $query .= " LIMIT $limit";
    //   }
    //   if ($result = Self::$serverConn->query($query)) {
    //     //query successful, return the mysqli object
    //     if ($result->num_rows >= 1) {
    //       # there was in fact a result
    //       while ($row = $result->fetch_assoc()) {//while there is still one
    //         # while there is still something from the task info column
    //         $rowContents[] = $row;//dynamically initialize an array to return
    //       }
    //
    //       return $rowContents;
    //     }else {
    //         # there was no result
    //         return FALSE;
    //       }
    //   }else {
    //       # query was not successful
    //       return FALSE;
    //     }
    // }
    //
    // /**
    //  **This method is used to retrieve the timeline of total task created according
    //  * to days, weeks, months or years the timeline type defaults to days
    //  **@param string $userId, $limit, $timelineType
    //  **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
    //  * there was server issues and the query was not successful
    //  */
    // public function getTotalTaskTimeline($userId, $limit='', $timelineType=''){
    //   $query = "SELECT DISTINCT DATE(created) FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId' ORDER BY DATE(created) ASC";
    //   // check if the request is in weeks
    //   //add order
    //
    //   // check if the request is in months then
    //
    //   // check if the request is in years then
    //
    //   //add limit
    //   if ($limit) {
    //     # there is a limit to add
    //     $query .= " LIMIT $limit";
    //   }
    //
    //   if ($result = Self::$serverConn->query($query)) {
    //     //query successful, return the mysqli object
    //     if ($result->num_rows >= 1) {
    //       # there was in fact a result
    //       while ($row = $result->fetch_assoc()) {//while there is still one
    //         # while there is still something from the created column
    //         $rowContents[] = $row['DATE(created)'];//dynamically initialize an array to return
    //       }
    //       return $rowContents;
    //     }else {
    //         # there was no result
    //         return FALSE;
    //       }
    //   }else {
    //       # query was not successful
    //       return FALSE;
    //     }
    // }
    //
    // /**
    //  **This method is used to retrieve the total number of ongoing tasks according
    //   * to days, weeks, months or years the timeline type defaults to days
    //  **@param string $userId, $limit, $timelineType
    //  **@return Array $arrayToReturn, Bool FALSE if there was nothing to fetch or if
    //  * there was server issues and the query was not successful
    //  */
    // public function getTotalOngoingTaskInfo($userId, $limit='', $timelineType=''){
    //   $query = "SELECT DATE(created) FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId' AND `status` = 1 ORDER BY DATE(created) ASC";
    //   // check if the request is in weeks
    //   //add order
    //
    //   // check if the request is in months then
    //
    //   // check if the request is in years then
    //
    //   //add limit
    //   if ($limit) {
    //     # there is a limit to add
    //     $query .= " LIMIT $limit";
    //   }
    //
    //   if ($result = Self::$serverConn->query($query)) {
    //     //query successful, return the mysqli object
    //     if ($result->num_rows >= 1) {
    //       # there was in fact a result
    //       while ($row = $result->fetch_assoc()) {//while there is still one
    //         # while there is still something from the created column
    //         $rowContents[] = $row;//dynamically initialize an array to return
    //       }
    //       //an array to keep what is to be returned
    //       $arrayToReturn = [];
    //
    //        $totalTaskTimeline = $this->getTotalTaskTimeline($userId, $limit, $timelineType);
    //       // for each one of the ongoing task date fetched, count amount that corresponds
    //        for ($i=0; $i < count($totalTaskTimeline); $i++) {
    //          # going through each element in this totalTaskTimeline
    //          $totalNumberOnThisDate = 0;
    //         foreach ($rowContents as $key => $value) {
    //           # go through all the elements in the total ongoing task timeline
    //           if ($value['DATE(created)'] == $totalTaskTimeline[$i]) {
    //             # this particular date of task created correspond to this timeline date
    //             $totalNumberOnThisDate++;
    //           }
    //         }
    //         $arrayToReturn[$totalTaskTimeline[$i]] = $totalNumberOnThisDate;
    //        }
    //       return $arrayToReturn;
    //     }else {
    //         # there was no result
    //         return FALSE;
    //       }
    //   }else {
    //       # query was not successful
    //       return FALSE;
    //     }
    // }
    //
    // /**
    //  **This method is used to retrieve the total number of ongoing tasks according
    //   * to days, weeks, months or years the timeline type defaults to days
    //  **@param string $userId, $limit, $timelineType
    //  **@return Array $arrayToReturn, Bool FALSE if there was nothing to fetch or if
    //  * there was server issues and the query was not successful
    //  */
    // public function getTotalTaskInfo($userId, $limit='', $timelineType=''){
    //   $query = "SELECT DATE(created) FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId' ORDER BY DATE(created) ASC";
    //   // check if the request is in weeks
    //   //add order
    //
    //   // check if the request is in months then
    //
    //   // check if the request is in years then
    //
    //   //add limit
    //   if ($limit) {
    //     # there is a limit to add
    //     $query .= " LIMIT $limit";
    //   }
    //
    //   if ($result = Self::$serverConn->query($query)) {
    //     //query successful, return the mysqli object
    //     if ($result->num_rows >= 1) {
    //       # there was in fact a result
    //       while ($row = $result->fetch_assoc()) {//while there is still one
    //         # while there is still something from the created column
    //         $rowContents[] = $row;//dynamically initialize an array to return
    //       }
    //       //an array to keep what is to be returned
    //       $arrayToReturn = [];
    //
    //        $totalTaskTimeline = $this->getTotalTaskTimeline($userId, $limit, $timelineType);
    //       // for each one of the ongoing task date fetched, count amount that corresponds
    //        for ($i=0; $i < count($totalTaskTimeline); $i++) {
    //          # going through each element in this totalTaskTimeline
    //          $totalNumberOnThisDate = 0;
    //         foreach ($rowContents as $key => $value) {
    //           # go through all the elements in the total ongoing task timeline
    //           if ($value['DATE(created)'] == $totalTaskTimeline[$i]) {
    //             # this particular date of task created correspond to this timeline date
    //             $totalNumberOnThisDate++;
    //           }
    //         }
    //         $arrayToReturn[$totalTaskTimeline[$i]] = $totalNumberOnThisDate;
    //        }
    //       return $arrayToReturn;
    //     }else {
    //         # there was no result
    //         return FALSE;
    //       }
    //   }else {
    //       # query was not successful
    //       return FALSE;
    //     }
    // }
    //







}








?>
<?php

  $test = new BillingO();
  if ($test) {
    echo "string";
  }else {
    echo "i dok";
  }
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
