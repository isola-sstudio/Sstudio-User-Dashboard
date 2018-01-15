<?php namespace Libs\AdminTask;
 error_reporting(E_ALL);
 ini_set("display_errors", 1);

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
     **This method is used to create a task when a user submits a task form
     **@param string $userId, $taskName, $taskDescription
     **@return bool TRUE if the task was successfully created
     * without any error. FALSE otherwise.
     */
    public function createTask($userId, $taskName, $taskPriority, $dueDate, $taskDescription){
      if ($this->getTasksInfo($userId, 'id', '', array(array('key' => 'task_name','operator'=>'=', 'value'=>"$taskName"),array('key' => 'task_description','operator'=>'=', 'value'=>"$taskDescription")))) {
        # check if the task has been created already, in order to avoid multiple
        // duplicate
        return "possibly duplicate";
      }else {
          # not a duplicate so
          //build up a query string and create a user
          $query = "INSERT INTO `thestart_upstudio`.`admin_task`(`user_id`,
            `task_name`, `task_priority`, `due_date`, `task_description`)
            VALUES('$userId', '$taskName', '$taskPriority', '$dueDate', '$taskDescription')";
            if (Self::$serverConn -> query($query) === TRUE) {
              # the task has been created successfully
              return TRUE;
            }else {
              # task could not be created at the moment so
              //try again with mysqli::escape_string
             $taskName = Self::$serverConn->escape_string($taskName);
             $taskDescription = Self::$serverConn->escape_string($taskDescription);
             //set the query again
             $query = "INSERT INTO `thestart_upstudio`.`admin_task`(`user_id`,
               `task_name`, `task_priority` `task_description`)
               VALUES('$userId', '$taskName', '$taskPriority', '$taskDescription')";
                if (Self::$serverConn -> query($query) === TRUE) {
                  # the task has been created successfully
                  return TRUE;
                }else {
                  # Still, the task could not be created
                  return FALSE;
                }
            }
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
     **@param string $userId, $taskInfo, $limit, Array $constraint, $order, Bool $distinct
     **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
     * there was server issues and the query was not successful
     */
    public function getTasksInfo($userId, $taskInfo, $limit='', $constraint='', $order='', $distinct=FALSE){
      $query = "SELECT $taskInfo FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
      //check if we are to select distinct values
      if ($distinct) {
        # add distinct to query string
        $query = "SELECT DISTINCT `$taskInfo` FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId'";
      }
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
     **This method is used to retrieve the timeline of total task created according
     * to days, weeks, months or years the timeline type defaults to days
     **@param string $userId, $limit, $timelineType
     **@return Array $rowContent, Bool FALSE if there was nothing to fetch or if
     * there was server issues and the query was not successful
     */
    public function getTotalTaskTimeline($userId, $limit='', $timelineType=''){
      $query = "SELECT DISTINCT DATE(created) FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId' ORDER BY DATE(created) ASC";
      // check if the request is in weeks
      //add order

      // check if the request is in months then

      // check if the request is in years then

      //add limit
      if ($limit) {
        # there is a limit to add
        $query .= " LIMIT $limit";
      }

      if ($result = Self::$serverConn->query($query)) {
        //query successful, return the mysqli object
        if ($result->num_rows >= 1) {
          # there was in fact a result
          while ($row = $result->fetch_assoc()) {//while there is still one
            # while there is still something from the created column
            $rowContents[] = $row['DATE(created)'];//dynamically initialize an array to return
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
     **This method is used to retrieve the total number of ongoing tasks according
      * to days, weeks, months or years the timeline type defaults to days
     **@param string $userId, $limit, $timelineType
     **@return Array $arrayToReturn, Bool FALSE if there was nothing to fetch or if
     * there was server issues and the query was not successful
     */
    public function getTotalOngoingTaskInfo($userId, $limit='', $timelineType=''){
      $query = "SELECT DATE(created) FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId' AND `status` = 1 ORDER BY DATE(created) ASC";
      // check if the request is in weeks
      //add order

      // check if the request is in months then

      // check if the request is in years then

      //add limit
      if ($limit) {
        # there is a limit to add
        $query .= " LIMIT $limit";
      }

      if ($result = Self::$serverConn->query($query)) {
        //query successful, return the mysqli object
        if ($result->num_rows >= 1) {
          # there was in fact a result
          while ($row = $result->fetch_assoc()) {//while there is still one
            # while there is still something from the created column
            $rowContents[] = $row;//dynamically initialize an array to return
          }
          //an array to keep what is to be returned
          $arrayToReturn = [];

           $totalTaskTimeline = $this->getTotalTaskTimeline($userId, $limit, $timelineType);
          // for each one of the ongoing task date fetched, count amount that corresponds
           for ($i=0; $i < count($totalTaskTimeline); $i++) {
             # going through each element in this totalTaskTimeline
             $totalNumberOnThisDate = 0;
            foreach ($rowContents as $key => $value) {
              # go through all the elements in the total ongoing task timeline
              if ($value['DATE(created)'] == $totalTaskTimeline[$i]) {
                # this particular date of task created correspond to this timeline date
                $totalNumberOnThisDate++;
              }
            }
            $arrayToReturn[$totalTaskTimeline[$i]] = $totalNumberOnThisDate;
           }
          return $arrayToReturn;
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
     **This method is used to retrieve the total number of ongoing tasks according
      * to days, weeks, months or years the timeline type defaults to days
     **@param string $userId, $limit, $timelineType
     **@return Array $arrayToReturn, Bool FALSE if there was nothing to fetch or if
     * there was server issues and the query was not successful
     */
    public function getTotalTaskInfo($userId, $limit='', $timelineType=''){
      $query = "SELECT DATE(created) FROM `thestart_upstudio`.`admin_task` WHERE `user_id` = '$userId' ORDER BY DATE(created) ASC";
      // check if the request is in weeks
      //add order

      // check if the request is in months then

      // check if the request is in years then

      //add limit
      if ($limit) {
        # there is a limit to add
        $query .= " LIMIT $limit";
      }

      if ($result = Self::$serverConn->query($query)) {
        //query successful, return the mysqli object
        if ($result->num_rows >= 1) {
          # there was in fact a result
          while ($row = $result->fetch_assoc()) {//while there is still one
            # while there is still something from the created column
            $rowContents[] = $row;//dynamically initialize an array to return
          }
          //an array to keep what is to be returned
          $arrayToReturn = [];

           $totalTaskTimeline = $this->getTotalTaskTimeline($userId, $limit, $timelineType);
          // for each one of the ongoing task date fetched, count amount that corresponds
           for ($i=0; $i < count($totalTaskTimeline); $i++) {
             # going through each element in this totalTaskTimeline
             $totalNumberOnThisDate = 0;
            foreach ($rowContents as $key => $value) {
              # go through all the elements in the total ongoing task timeline
              if ($value['DATE(created)'] == $totalTaskTimeline[$i]) {
                # this particular date of task created correspond to this timeline date
                $totalNumberOnThisDate++;
              }
            }
            $arrayToReturn[$totalTaskTimeline[$i]] = $totalNumberOnThisDate;
           }
          return $arrayToReturn;
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
     **This method is used by an Admin user to update a task
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
        if (array_keys($updates)[count($updates) - 1] == $key) {
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
     **This method is used by an Admin user during chat on a task to retrieve all
     * the previous content of the chat on that task
     **@param string $userId, $taskId
     **@return string $chatFileContent, bool FALSE if the task does not exist
     */
    public function getTaskChatFile($userId, $taskId){
      //check that the task exists
      if ($this->getTasksInfo($userId, 'id', '', array(array('key' => 'id', 'operator'=>'=', 'value'=>$taskId)), '')) {
        # check if a chat file has been created then open it
        $chatFile = $this->getTasksInfo($userId, 'chat_file', '', array(array('key' => 'id', 'operator'=>'=', 'value'=>$taskId)), '');
        if ($chatFile[0] != '') {
          # a chat file has been created so open it for reading and read back
          $handle = fopen('../task_chats/'.$chatFile[0].'.html', 'a+');
          $chatFileContent = file_get_contents('../task_chats/'.$chatFile[0].'.html');
          fclose($handle);
          return $chatFileContent;
        }else {
          # create a chat file, opened for reading and read back
          $chatFileName = rand(1000, 9999) . '_' . $taskId;
          $this->updateTask($userId, $taskId, array('chat_file' => $chatFileName,));

          $handle = fopen('../task_chats/'.$chatFileName.'.html', 'a+');
          // chmod('../task_chats/'.$chatFileName.'.html', 0733);  //change file permission
          $chatFileContent = file_get_contents('../task_chats/'.$chatFileName.'.html');
          fclose($handle);
          return $chatFileContent;
        }
      }else {
        # most likely, someone is messing with the request
        return FALSE;
      }
    }

    /**
     **This method is used by an Admin user during chat on a task to post new
     * chat message and also retrieve it on success
     **@param string $userId, $taskId, $updateString
     **@return string $updatedContent, bool FALSE if the task chat file does not
     * exist
     */
    public function updateTaskChatFile($userId, $taskId, $updateString){
      //check that the task exists
      if ($this->getTasksInfo($userId, 'id', '', array(array('key' => 'id', 'operator'=>'=', 'value'=>$taskId)), '')) {
        # check if a chat file has been created then open it
        $chatFile = $this->getTasksInfo($userId, 'chat_file', '', array(array('key' => 'id', 'operator'=>'=', 'value'=>$taskId)), '');
        if ($chatFile[0] != '') {
          # a chat file has been created so open it for appending and read back
          $handle = fopen('../task_chats/'.$chatFile[0].'.html', 'a+');
          if (filesize('../task_chats/'.$chatFile[0].'.html') != 0) {
            # the file is not empty
            fread($handle, filesize('../task_chats/'.$chatFile[0].'.html'));
          }
          $currentPosition = ftell($handle);

          fwrite($handle, $updateString.PHP_EOL);
          fseek($handle, $currentPosition);
          $updatedContent = fread($handle, strlen($updateString));
          fclose($handle);
          return $updatedContent;
        }else {
            # the task chat file does not exist... for now return FALSE
            return FALSE;
          }
      }else {
        # most likely, someone is messing with the request
        return FALSE;
      }
    }

    /**
     **This method is used by an Admin user during chat on a task to get updated
     * chat messages most likely from the other side
     **@param string $userId, $taskId, $compareString
     **@return string $updatedContent, bool FALSE if the task chat file does not
     * exist
     */
    public function getUpdatedTaskChatMessages($userId, $taskId, $compareString){
      //check that the task exists
      if ($this->getTasksInfo($userId, 'id', '', array(array('key' => 'id', 'operator'=>'=', 'value'=>$taskId)), '')) {

        # check if a chat file has been created then open it
        $chatFile = $this->getTasksInfo($userId, 'chat_file', '', array(array('key' => 'id', 'operator'=>'=', 'value'=>$taskId)), '');
        if ($chatFile[0] != '') {
          # a chat file has been created so open it for comparing and read back
          $handle = fopen($chatFile[0].'.html', 'r');
          fread($handle, strlen($compareString));
          $updatedContent = fread($handle, filesize($chatFile[0].'.html'));
          fclose($handle);
          return $updatedContent;
        }else {
            # the task chat file does not exist... for now return FALSE
            return FALSE;
          }

      }else {
        # most likely, someone is messing with the request
        return FALSE;
      }
    }






  }

?>
<?php

  $test = new TaskO();
  // var_dump($test -> getTaskChatFile(1, 1));
  // var_dump($test -> updateTaskChatFile(1, 1, 'project'));
  // var_dump($test -> getUpdatedTaskChatMessages(1, 1, 'it is going alrightit is going alrightit is going alrightNa so ooooo! Na so ooooo! Na so ooooo! Na so ooooo! N'));
?>
