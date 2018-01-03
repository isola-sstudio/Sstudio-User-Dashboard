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
    //set amount of total task created
    $totalTaskCreated = $taskOperations->taskCount($_SESSION['user_id']);

    //set amount of just created task
    $justCreatedTasks = $taskOperations->taskCount($_SESSION['user_id'], '0');

    //set amount of ongoing task
    $ongoingTasks = $taskOperations->taskCount($_SESSION['user_id'], '1');

    //set amount of completed task
    $completedTask = $taskOperations->taskCount($_SESSION['user_id'], '3');
    /**
     **End Setting stuffs for the quick analytics info div on the dashboard page
     */

    /**
     **Setting stuffs for the Recent Task div on the dashboard page
     */
    $lastFiveMonths = $taskOperations->getReccentTasksMonths($_SESSION['user_id'], 5);

    //get recent task report based on selected month
    function recentTaskReport($reportMonth, $amount){
      $taskOperations = new TaskO();
      $recentTaskReport = $taskOperations->getReccentTasks($_SESSION['user_id'], $reportMonth, $amount);
      return $recentTaskReport;
    }

    // details about all ongoing tasks
    $ongoingTasksInfo = $taskOperations->getTasksInfo($_SESSION['user_id'], '*', '', array(array('key' => 'status', 'operator'=>'=', 'value'=>'1')), array('column' => 'created', 'type'=>'ASC'));
    // create a JSON variable for this
    ?>
      <script type="text/javascript">
        var ongoingTasksGraph = <?php echo json_encode($ongoingTasksInfo); ?>;
      </script>
    <?php

    // details about all completed tasks
    $completedTasksInfo = $taskOperations->getTasksInfo($_SESSION['user_id'], '*', '', array(array('key' => 'status', 'operator'=>'=', 'value'=>'3')), array('column' => 'created', 'type'=>'ASC'));
    // create a JSON variable for this
    ?>
      <script type="text/javascript">
        var completedTasksGraph = <?php echo json_encode($completedTasksInfo); ?>;
      </script>
    <?php

    // all available tasks
    $taskHistory = $taskOperations->getTasksInfo($_SESSION['user_id'], '*', '', '', array('column' => 'created', 'type'=>'DESC'));
  }

?>
