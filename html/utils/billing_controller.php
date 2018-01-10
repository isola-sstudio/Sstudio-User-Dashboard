<?php
  session_start();

  /**Always, this guy is just ready to start dumping everything he knows about
   * the admin's account or billing
   */

  require_once __DIR__ .'/../vendor/autoload.php';

  //use Libs\AdminUser\AdminUser;
  use Libs\AdminBilling\BillingO;
  use Libs\AdminUser\AdminUser;

  if (isset($_SESSION['user_id'])) {
    # the needed session variables are set.. also means the user is logged in
    //init the BillingO object
    $billingOperations = new BillingO();
    //init the AdminUser object
    $adminUser = new AdminUser();

    // details about all billing history
    $billingHistory = $billingOperations->getBillingInfo($_SESSION['user_id'], '*', '', '', array('column' => 'date_created', 'type'=>'DESC'));

    // most recent billing
    $mostRecentBillingHistoryRecords = $billingOperations->getBillingInfo($_SESSION['user_id'], '*', 1, '', array('column' => 'date_created', 'type'=>'DESC'));
    foreach ($mostRecentBillingHistoryRecords as $key => $record) {
      # since we are expecting just one record and this is a record in a record
      foreach ($record as $recordKey => $recordValue) {
        $mostRecentBillingHistory[$recordKey] = $recordValue;
      }
    }

    // current plan
    $userDetails = $adminUser->getAdminUserInfo('id', $_SESSION['user_id']);



  }

?>
