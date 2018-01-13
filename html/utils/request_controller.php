<?php
  session_start();
  /**
   **This script is responsible for actions concerning the request page
   */

  require_once __DIR__ .'/../vendor/autoload.php';
  require_once __DIR__ . '/send_mail.php';

  use Libs\Validation\Validation;
  use Libs\AdminTask\TaskO;
  use Libs\AdminUser\AdminUser;

  // checck if we have a request to create a new task
  if ($_SERVER['REQUEST_METHOD'] == POST && isset($_POST['create_request'])) {
    # there is a new request to create a task
    if (!Validation::isBlank($_POST['task_name'], $_POST['task_priority'], $_POST['task_desc'])) {
      if (isset($_POST['due_date'])) {
        # the user remembered to put a due date
        $dueDate = $_POST['due_date'];
      }else {
        # the user has forgotten to put a due date for this task
        $dueDate = '';
      }
      # no empty field, so create task
      $taskOperations = new TaskO();
      if ($taskOperations->createTask($_SESSION['user_id'], $_POST['task_name'], $_POST['task_priority'], $dueDate, $_POST['task_desc'])) {
        # the task was successfully created
        $adminUser = new AdminUser();
        $userDetails = $adminUser->getAdminUserInfo('id', $_SESSION['user_id']);

        $companyName = $userDetails['name_of_company'];
        $companyEmail = $userDetails['company_email'];
        $postMessageArray['Task Name'] = $_POST['task_name'];
        $postMessageArray['Priority'] = $_POST['task_priority'].'%';
        $postMessageArray['Due Date'] = $dueDate;
        $postMessageArray['Description'] = $_POST['task_desc'];

        sendMail('New Task Created', $companyName, $companyEmail, $postMessageArray);

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
