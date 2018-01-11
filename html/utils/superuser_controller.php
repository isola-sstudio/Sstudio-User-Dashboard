<?php
  session_start();

  /**Always, this guy is just ready to start dumping everything he knows about
   * the admin as far as tasks are concerned
   **He brings in some info without being asked.. maybe we should allow him to
   * be asked through AJAX before bringing in anything
   **He accepts some POST requests and AJAX requests from whereever and in turn
   * sends some response in php or JSON
   */

  require_once __DIR__ .'/../vendor/autoload.php';

  use Libs\AdminUser\AdminUser;
  use Libs\SuperUser\SuperUserO;

  if (isset($_SESSION['user_id'])) {
    # the needed session variables are set.. also means the user is logged in
    //init the SuperUserO object
    $superUserOperations = new SuperUserO();

    // details about all new tasks
    $newTasksInfo = $superUserOperations->getTasksInfo('*', '', array(array('key' => 'status','operator'=>'=', 'value'=>'0')), array('column' => 'created', 'type'=>'DESC'));

    // details about all ongoing tasks
    $ongoingTasksInfo = $superUserOperations->getTasksInfo('*', '', array(array('key' => 'status','operator'=>'=', 'value'=>'1')), array('column' => 'created', 'type'=>'DESC'));

    // task to update
    if ($_GET['task-id']) {
      # there is a get task-id
      $singleTaskRow = $superUserOperations->getTasksInfo('*', '', array(array('key' => 'id','operator'=>'=', 'value'=>$_GET['task-id'])));
      foreach ($singleTaskRow as $singleKey => $singleValue) {
        foreach ($singleValue as $key => $value) {
          # code...
          $singleTask[$key] = $value;
        }
      }
    }else {
      # there is none
      $singleTask = '';
      }
    $superUserAdmin = new AdminUser();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
      # there is a request to update a task
      if (isset($_GET['task-id']) && $superUserOperations->getTasksInfo('id', '', array(array('key' => 'id','operator'=>'=', 'value'=>$_GET['task-id'])))) {
        # check if there is a task id set in the get variable and the id exists
        //set the $info array
        foreach ($_POST as $key => $value) {
          # for each one of these post variables
          if (!empty($_POST["$key"])) {
            //check that each field to be updated is not empty
            # dynamically add up to the $updates array
            $updates["$key"] = $value;
          }
        }
        if ($superUserOperations->updateTask($singleTask['user_id'], $singleTask['id'], $updates)) {
          # code...
          $updateResponse = 'success';
          header('Location: task_backend.php');;
        }else {
            # code...
            $updateResponse = 'failure';
          }
      }
    }
  }

?>new
