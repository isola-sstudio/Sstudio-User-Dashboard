<?php
  session_start();
  /**
   **This script is responsible for actions concerning the request page
   */

  require_once __DIR__ .'/../vendor/autoload.php';

  use Libs\Validation\Validation;
  use Libs\AdminTask\TaskO;

  // checck if we have a request to create a new task
  if ($_SERVER['REQUEST_METHOD'] == POST && isset($_POST['create_request'])) {
    # there is a new request to create a task
    if (!Validation::isBlank($_POST['task_name'], $_POST['task_priority'], $_POST['task_desc'])) {
      # no empty field, so create task
      $taskOperations = new TaskO();
      if ($taskOperations->createTask($_SESSION['user_id'], $_POST['task_name'], $_POST['task_priority'], $_POST['task_desc'])) {
        # the task was successfully created
        $requestResponse = 'Task Successfully Created';
      }else {
          # the task could not be Created
          $requestResponse = 'Please try again';
        }
  }else {
      # at least one empty field.
      $requestResponse = 'Blank Fields';
    }
  }
?>
