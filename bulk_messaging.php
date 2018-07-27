<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title>Apifonica - send bulk SMS via Apifonica API</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
<script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/4831CDA5-4F0B-224D-AB98-A0802D71F8EB/main.js" charset="UTF-8"></script><style type="text/css">
	body { 
		padding: 50px;
	}
	.bg-danger, .bg-success {
		padding: 5px;
	}
</style>
</head>
<body>
	<h1>Mass Text Messaging</h1>
	<p>
		<form method="post" action="" class="col-md-6">
						  <input type="hidden" name="action" value="sendsms"/>
		  <h3>Complete the form below to send a message via SMS to multiple recipients</h3>		
		  <div class="form-group">
		    <label for="InputNumbers">Your marketing or other content will be sent to these numbers:</label><br />
            <span>Separate phone numbers by a comma, omit plus signs</span>
		    <textarea cols="10" class="form-control" id="InputNumbers" name="numbers" required></textarea>
		  </div>

		  <div class="form-group">
		    <label for="InputMessage">Please write your message here:</label>
		    <textarea class="form-control" id="InputMessage" name="message" required></textarea>
		  </div>
		  <button type="submit" class="btn btn-default">Send Your Message</button>
				</form>
	</p>
    <?php
    echo(123);
    ?>
</body>
</html>
