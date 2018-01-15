<?php
  session_start();

  /**
   */
  require_once __DIR__ .'/../vendor/autoload.php';
  require_once __DIR__ . '/send_mail.php';

  use Libs\AdminUser\AdminUser;
  use Libs\AdminTask\TaskO;

  $adminUser = new AdminUser();
  $taskOperations = new TaskO();

  $_SESSION['user_id'] = 1;
  // block for reading task chat file
  if (isset($_SESSION['user_id']) && isset($_POST['task_id']) && strtolower($_POST['action']) == 'read') {
    # get the task file and return the content
    $chatContent = $taskOperations->getTaskChatFile($_SESSION['user_id'], $_POST['task_id']);
    echo json_encode($chatContent);
  }

  // block for writing to task chat file
  if (isset($_SESSION['user_id']) && isset($_POST['task_id']) && isset($_POST['chat_message']) && strtolower($_POST['action']) == 'write') {
    # write to the task chat file and return the written message
    if($updatedMessage = $taskOperations->updateTaskChatFile($_SESSION['user_id'], $_POST['task_id'], $_POST['chat_message'])){

      $companyName = $adminUser->getAdminUserInfo('id', $_SESSION['user_id'], 'name_of_company');
      $email = $adminUser->getAdminUserInfo('id', $_SESSION['user_id'], 'company_email');

      $postMessageArray['Task Name'] = $taskOperations->getTasksInfo($_SESSION['user_id'], 'task_name', '', array(array('key' => 'id', 'operator'=>'=', 'value'=>$_POST['task_id'])))[0];
      $postMessageArray['Latest Message'] = $updatedMessage;
      sendMail('New Chat on a Task', $companyName, $email, $postMessageArray);
      echo json_encode($updatedMessage);
    }
  }

  // block for reading the latest update from the chat
  if (isset($_SESSION['user_id']) && isset($_POST['task_id']) && isset($_POST['compare_string']) && strtolower($_POST['action']) == 'update') {
    # get the latest messages from the chat file
    if($updatedChatMessages = getUpdatedTaskChatMessages($_SESSION['user_id'], $_POST['task_id'], $_POST['compare_string'])){
      echo json_encode($updatedChatMessages);
    }
  }
?>
