<?php
error_reporting(E_ALL);

/**
 * This function sends SMS messages from specified Apifonica phone number to specified mobile number
 * @param string $api_url URL to retrieve Apifonica API
 * @param string $accountSID Your Apifonica account identifier
 * @param string $password Password for your Apifonica account
 * @param string $from Apifonica number used as a message sender
 * @param string $to Recipient’s mobile phone number
 * @param string $message Message text
 * @return array 
 */
function sendSMS($api_url, $accountSID, $password, $from, $to, $message) {

		$body = array(
			'from' => $from,
			'to' => $to,
			'text' => $message,
		);
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url.'/v2/accounts/'.$accountSID.'/messages');
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
		// Set user and password
		curl_setopt ($curl, CURLOPT_USERPWD, $accountSID.':'.$password);
		// Do not check SSL
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		// Add header
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		// Set POST
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
		
		$result = curl_exec($curl);
		
		if ($result) {
			$result = json_decode($result, true);
		} else {
			$result = array(
				'error_text' => curl_error($curl),	
				'error_code' => curl_errno($curl),
				'status_code' => 600,
			);
		}
		
		return $result;
}

/**
 * This function checks the current status of the message
 * @param string $api_url URL to retrieve Apifonica API
 * @param string $accountSID Your Apifonica account identifier
 * @param string $password Password for your Apifonica account
 * @param string $smsuri SMS URL for check status 
 * @return array 
 */
function checkSMS($api_url, $accountSID, $password, $smsuri) {

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $api_url.$smsuri);
	curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
	// Set user and password
	curl_setopt ($curl, CURLOPT_USERPWD, $accountSID.':'.$password);
	// Do not check SSL
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	// Add header
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	$result = curl_exec($curl);

	if ($result) {
		$result = json_decode($result, true);
	} else {
		$result = array(
				'error_text' => curl_error($curl),
				'error_code' => curl_errno($curl),
				'status_code' => 600,
		);
	}

	return $result;
}


/**
 * Set default variables 
 */

// Specify Apifonica API URL
$api_url = 'https://api.apifonica.com';
// Specify your Apifonica account SID
$accountSID = '';
// Specify your Apifonica account password
$password = '';
// Specify the message sender's number (this number must belong to the Apifonica account you have specified) 
$from = '';
// Specify the mobile number to receive SMS messages sent from the web form
$to = '';

/**
 * set variables from POST
 */
$action = isset($_POST['action'])?$_POST['action']:'default';
$email =  isset($_POST['email'])?trim($_POST['email']):'';
$name =  isset($_POST['name'])?trim($_POST['name']):'';
$message =  isset($_POST['message'])?trim($_POST['message']):'';
$smsuri =  isset($_POST['smsuri'])?trim($_POST['smsuri']):'';

$text = 'Name: '.$name.'; '.'E-mail: '.$email.'; '.'Text: '.$message;

$result = false;

/**
 * Sending SMS
 */
if ($action == 'sendsms') {
	
	$result = sendSMS($api_url, $accountSID, $password, $from, $to, $text);
	
	if ($result['status_code'] > 299) {
		// In case SMS send action is failed, display an error message and web contact form
		$action = 'view';
	}
	
/**
 * Check SMS status
 */
} else if ($action == 'checksms') {
	$result = checkSMS($api_url, $accountSID, $password, $smsuri);
	//prn($result);
	$check_text = 'Unknown status';
	if ($result && isset($result['status'])) {
		switch ($result['status']) {
			case 'queued':
				$check_text = 'Message is on its way!';
				break;
			case 'sent':
				$check_text = 'Message is successfully sent.';
				break;
			case 'delivered':
				$check_text = 'Message is delivered to recipient\'s phone. ';
				break;
			case 'failed':
				$check_text = 'Message delivery failed.';
				break;
			default: 
		}
	}
}
/**
 * Default view form
 */

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title>Apifonica - send SMS</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
<style type="text/css">
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
		<?php if ($result && isset($result['status_code']) && $result['status_code'] > 299) { ?>
			<div class="bg-danger form-group">
				<h4>An error occured while sending the request :(</h4>
				<ul><li><?php echo $result['error_text']; ?></li></ul>
			</div>
		<?php } ?>
		<?php if ($action == 'sendsms') { ?>
			<input type="hidden" name="action" value="checksms"/>
			<input type="hidden" name="smsuri" value="<?php echo $result['uri']; ?>"/>
			<div class="bg-success form-group">
				<h4>Message is on its way!</h4>
			</div>
			<div>
				<button type="submit" class="btn btn-info">Update Delivery Status</button>
				&nbsp;
				<a href="" class="btn btn-default">Send Another Message</a>
			</div>
		<?php } else if ($action == 'checksms') { ?>
			<input type="hidden" name="action" value="checksms"/>
			<input type="hidden" name="smsuri" value="<?php echo $smsuri; ?>"/>
			<div class="bg-success form-group">
				<h4><?php echo $check_text; ?></h4>
			</div>
			<div>
				<button type="submit" class="btn btn-info">Update Delivery Status</button>
				&nbsp;
				<a href="" class="btn btn-default">Send Another Message</a>
			</div>
		<?php } else { ?>
		  <input type="hidden" name="action" value="sendsms"/>
		  <h3>Contact Us By SMS</h3>
		  <h3>Complete the form below to reach us by sending an SMS message.</h3>		
		  <div class="form-group">
		    <label for="InputName">Your name</label>
		    <input type="text" class="form-control" id="InputName" name="name" value="<?php echo $name; ?>" required>
		  </div>
		  <div class="form-group">
		    <label for="InputEmail">Your email</label>
		    <input type="email" class="form-control" id="InputEmail" name="email" value="<?php echo $email; ?>" required>
		  </div>
		  <div class="form-group">
		    <label for="InputMessage">Please write your message here</label>
		    <textarea class="form-control" id="InputMessage" name="message" required><?php echo $message ?></textarea>
		  </div>
		  <button type="submit" class="btn btn-default">Send Your Message</button>
		<?php } ?>
		</form>
	</p>
</body>
</html>
