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

  //use Libs\AdminUser\AdminUser;
  use Libs\AdminTask\TaskO;

  if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    # the needed session variables are set.. also means the user is logged in
    //init the TaskO object
    $taskOperations = new TaskO();

    /**
     **Setting stuffs for the quick analytics info div on the dashboard page
     */
    //set total task created
    $totalTaskCreated = $taskOperations->taskCount($_SESSION['user_id']);

    //set just created task
    $justCreatedTasks = $taskOperations->taskCount($_SESSION['user_id'], '0');

    //set ongoing task
    $ongoingTasks = $taskOperations->taskCount($_SESSION['user_id'], '1');

    //set completed task
    $completedTask = $taskOperations->taskCount($_SESSION['user_id'], '3');
    /**
     **End Setting stuffs for the quick analytics info div on the dashboard page
     */

    /**
     **Setting stuffs for the Recent Task div on the dashboard page
     */
    $lastFiveMonths = $taskOperations->getTasksInfo($_SESSION['user_id'], 'MONTHNAME(created)', 5, '', array('column' => 'MONTHNAME(created)', 'type' => 'DESC'), TRUE);

  }

?>
