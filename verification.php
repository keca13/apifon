<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title>Apifonica - send verification codes via SMS</title>
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
	<p>
		<form method="post" action="" class="col-md-6">
						  <input type="hidden" name="action" value="send_verification"/>
		  <h3>Phone Number Verification</h3>
		  <div class="form-group">
		    <label for="InputName">Please enter your name</label>
		    <input type="text" class="form-control" id="InputName" name="name" value="" required>
		  </div>
		  <div class="form-group">
		    <label for="InputPhone">... and your mobile number</label>
		    <input type="tel" class="form-control" id="InputPhone" name="phone" value="" required>
		  </div>
		  <button type="submit" class="btn btn-default">Send Verification Code</button>
				</form>
	</p>
</body>
</html>
