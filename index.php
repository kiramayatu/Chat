<?php 
$colors = array('d3f441','#FF7000','#FF7000','#15E25F','#CFC700','#CFC700','#CF1100','#CF00BE','#f44242','41f4d3');
$color_pick = array_rand($colors);
?>

<!DOCTYPE html>
<html>
<head>
	<title>GranBlue Fantasy Indonesia</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<div class="chat-wrapper">
		<div id="message-box"></div>
		<div class="user-panel">
			<input type="text" name="name" id="name" placeholder="Nama" maxlength="15" />
			<input type="text" name="message" id="message" placeholder="Pesan. . . " maxlength="100" />
			<button id="send-message">Send</button>
		</div>
	</div>

	<script src="chat.js"></script>
	<script language="javascript" type="text/javascript">  

		var msgBox = $('#message-box');
		var wsUri = "ws://0.0.0.0:80/server.php"; 	
		websocket = new WebSocket(wsUri); 

		websocket.onopen = function(ev) { 
		msgBox.append('<div class="system_msg" style="color:#bbbbbb">Selamat Datang di GBFID Chat Room"!</div>'); //notify user
	}
	
	websocket.onmessage = function(ev) {
		var response 		= JSON.parse(ev.data); 
		
		var res_type 		= response.type; 
		var usr_message 	= response.message; 
		var usr_name 		= response.name; 
		var usr_color 		= response.color; 

		switch(res_type){
			case 'usermsg':
			msgBox.append('<div><span class="user_name" style="color:' + usr_color + '">' + usr_name + '</span> : <span class="user_message">' + usr_message + '</span></div>');
			break;
			case 'system':
			msgBox.append('<div style="color:#bbbbbb">' + usr_message + '</div>');
			break;
		}
		msgBox[0].scrollTop = msgBox[0].scrollHeight; 

	};
	
	websocket.onerror	= function(ev){ msgBox.append('<div class="system_error">Error Occurred - ' + ev.data + '</div>'); }; 
	websocket.onclose 	= function(ev){ msgBox.append('<div class="system_msg">Connection Closed</div>'); }; 

	
	$('#send-message').click(function(){
		send_message();
	});
	
	
	$( "#message" ).on( "keydown", function( event ) {
		if(event.which==13){
			send_message();
		}
	});
	
	
	function send_message(){
		var message_input = $('#message'); 
		var name_input = $('#name'); 
		
		if(name_input.val() == ""){ 
			alert("Tolong masukan Nama Anda!");
			return;
		}
		if(message_input.val() == ""){ 
			alert("Masukan Pesan!");
			return;
		}

		
		var msg = {
			message: message_input.val(),
			name: name_input.val(),
			color : '<?php echo $colors[$color_pick]; ?>'
		};
		
		websocket.send(JSON.stringify(msg));	
		message_input.val(''); 
	}
</script>
</body>
</html>
