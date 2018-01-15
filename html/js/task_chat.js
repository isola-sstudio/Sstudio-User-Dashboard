


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
function TaskChat() {
  this.getChat = getChat;
  this.sendChat = sendChat;
  this.updateChat = updateChat;
  this.getNewChatMessages = getNewChatMessages;
}

function getChat(taskId){
  $.ajax({
  type: "POST",
  url: "../utils/task_chat.php",
  data: {'task_id': taskId, 'action': 'read'},
  dataType: "json",
    success: function(response){
      var initialChatDivHeight = $("#chatbox").prop("scrollHeight");
      updateChat(response, initialChatDivHeight);
    }
  });
}

function updateChat(html, scrollHeight){
  $("#chatbox").append(html);//add this html to the chatbox div
  $("#chatbox").animate({scrollTop: scrollHeight}, 1000);// scroll to the new chat
}

function sendChat(taskId, chatMessage){
  $.ajax({
  type: "POST",
  url: "../utils/task_chat.php",
  data: {'task_id': taskId, 'chat_message': chatMessage, 'action': 'write'},
  dataType: "json",
    success: function(response){
      var initialChatDivHeight = $("#chatbox").prop("scrollHeight");
      updateChat(response, initialChatDivHeight);
      // console.log(response);
    }
  });
}
isset($_POST['compare_string']) && strtolower($_POST['action']) == 'update'
function getNewChatMessages(taskId){
  var dataToPass = {'task_id': taskId, 'action': 'update'};
  dataToPass.compare_string = $("#chatbox").html();
  $.ajax({
  type: "POST",
  url: "../utils/task_chat.php",
  data: dataToPass,
  dataType: "json",
    success: function(response){
      var initialChatDivHeight = $("#chatbox").prop("scrollHeight");
      if (response != '') {
        updateChat(response, initialChatDivHeight);
      }
      // console.log(response);
    }
  });
}

var test = new TaskChat();
</script>
