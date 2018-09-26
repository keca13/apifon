<?php
/**
 * Created by PhpStorm.
 * User: atkachenko
 * Date: 22.09.2017
 * Time: 9:38
 */

error_reporting(E_ALL);

/**
 * This function makes a call from the specified Apifonica phone number to a specified softphone/mobile number
 * @param string $api_url URL to retrieve Apifonica API
 * @param string $account_sid Your Apifonica account identifier
 * @param string $account_token Password for your Apifonica account
 * @param string $from_number Apifonica number to call from
 * @param string $to_number Recipient's phone number
 * @param string $application_sid - call application ID
 * @param integer $timeout
 * @return array
 */
function call($api_url, $account_sid, $account_token, $from_number, $to_number, $application_sid, $timeout) {

    $body = array(
        'from' => $from_number,
        'to' => $to_number,
        'call_app_sid' => $application_sid,
        'timeout' => $timeout,
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $api_url.'/v2/accounts/'.$account_sid.'/calls');
    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
    // Set user and password
    curl_setopt ($curl, CURLOPT_USERPWD, $account_sid.':'.$account_token);
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
 * This function retrieves all calls made from the specified account
 * @param string $api_url URL to retrieve Apifonica API
 * @param string $account_sid Your Apifonica account identifier
 * @param string $account_token Password for your Apifonica account
 * @param string $uri URI to check the status of the call at
 * @return array
 */
function getCalls($api_url, $account_sid, $account_token, $uri) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $api_url . $uri);
    curl_setopt ($curl, CURLOPT_POST, 0);
    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
    // Set user and password
    curl_setopt ($curl, CURLOPT_USERPWD, $account_sid.':'.$account_token);
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

/**
 * set variables from POST
 */
$action = isset($_POST['action']) ? $_POST['action'] : 'default';

// Apifonica API URL
$api_url = isset($_POST['api_url']) ? $_POST['api_url'] : 'https://api.apifonica.com';

// Apifonica account SID
$account_sid = isset($_POST['account_sid']) ? $_POST['account_sid'] : '';

// Apifonica account password
$account_token = isset($_POST['account_token']) ? $_POST['account_token'] : '';

// Caller's number (this number must belong to the Apifonica account you have specified)
$from_number = isset($_POST['from_number']) ? $_POST['from_number'] : '';

// Number to call
$to_number = isset($_POST['to_number']) ? $_POST['to_number'] : '';

// Application SID
$application_sid = isset($_POST['application_sid']) ? $_POST['application_sid'] : '';

$timeout = 330;

$result = false;

$call_status_text = isset($_POST['call_status_text']) ? $_POST['call_status_text'] : 'Call status unknown.';

$uri = isset($_POST['uri']) ? $_POST['uri'] : '';

/**
 * Making a call
 */
if ($action == 'make_call') {

    $result = call($api_url, $account_sid, $account_token, $from_number, $to_number, $application_sid, $timeout);

    if ($result['status_code'] > 299) {
        // If the call action failed, display an error message and web contact form
        $action = 'view';
    }

    /**
     * Check call status
     */
} else if ($action == 'get_call_status') {
    $result = getCalls($api_url, $account_sid, $account_token, $uri);
    if (isset($result['status'])) {
        switch ($result['status']) {
            case 'queued':
                $call_status_text = 'Call has been queued!';
                break;
            case 'initiated':
                $call_status_text = 'Call has been initiated!';
                break;
            case 'ringing':
                $call_status_text = 'Call is ringing!';
                break;
            case 'answered':
                $call_status_text = 'Call has been answered!';
                break;
            case 'no_answer':
                $call_status_text = 'There was no answer!';
                break;
            case 'completed':
                $call_status_text = 'Call has been completed!';
                break;
            case 'busy':
                $call_status_text = 'Call destination is busy!';
                break;
            case 'rejected':
                $call_status_text = 'Call has been rejected!';
                break;
            case 'failed':
                $call_status_text = 'Call has failed!';
                break;
            case 'modified':
                $call_status_text = 'Call has been modified!';
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
    <title>Apifonica - Make Test Call</title>
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
    <?php if ($action == "make_call" && $result && isset($result['status_code']) && $result['status_code'] > 299) { ?>
        <div class="bg-danger form-group">
            <h4>An error occured while sending the request :(</h4>
            <ul><li><?php echo $result['error_text']; ?></li></ul>
        </div>
    <?php } ?>
    <?php if ($action == 'make_call') { ?>
        <input type="hidden" name="action" value="get_call_status"/>
        <input type="hidden" name="uri" value="<?php echo $result['uri']; ?>"/>
        <input type="hidden" name="api_url" value="<?php echo $_POST['api_url']; ?>"/>
        <input type="hidden" name="account_sid" value="<?php echo $_POST['account_sid']; ?>"/>
        <input type="hidden" name="account_token" value="<?php echo $_POST['account_token']; ?>"/>
        <input type="hidden" name="status_code" value="<?php echo $result['status_code']; ?>"/>
        <input type="hidden" name="status_message" value="<?php echo $result['status_message']; ?>"/>
        <div class="bg-success form-group">
            <h4>You call is being connected!!</h4>
        </div>
        <div>
            <button type="submit" class="btn btn-info">Check Your Call Status</button>
            <a href="" class="btn btn-default">Make Another Call</a>
        </div>
    <?php } else if ($action == 'get_call_status') { ?>
        <input type="hidden" name="action" value="get_call_status"/>
        <input type="hidden" name="status" value="<?php echo $result['status']; ?>"/>
        <input type="hidden" name="status_message" value="<?php echo $_POST['status_message']; ?>"/>
        <input type="hidden" name="call_status_text" value="<?php echo $call_status_text; ?>"/>
        <div class="bg-success form-group">
            <h4><?php echo $call_status_text; ?></h4>
        </div>
        <div>
            <a href="" class="btn btn-default">Make Another Call</a>
        </div>
    <?php } else { ?>
        <input type="hidden" name="action" value="make_call"/>
        <h3>Making a Test Phone Call</h3>
        <h3>Please fill out the fields below:</h3>

        <div class="form-group">
            <label for="InputUrl">API URL</label>
            <input type="text" class="form-control" id="InputUrl" name="api_url" value="<?php echo $api_url; ?>" required>
        </div>

        <div class="form-group">
            <label for="InputAccountSid">Your Account SID *</label>
            <input type="text" class="form-control" id="InputAccountSid" name="account_sid" value="<?php echo $account_sid; ?>" required>
        </div>

        <div class="form-group">
            <label for="InputAccountToken">Your Account Password (Token) **</label>
            <input type="password" class="form-control" id="InputAccountToken" name="account_token" value="<?php echo $account_token; ?>" required>
        </div>

        <div class="form-group">
            <label for="InputAppSid">Your Application SID ***</label>
            <input type="text" class="form-control" id="InputAppSid" name="application_sid" value="<?php echo $application_sid; ?>" required>
        </div>

        <div class="form-group">
            <label for="FromNumber">Caller Number (this number must belong to your account)</label>
            <input type="text" class="form-control" id="FromNumber" name="from_number" value="<?php echo $from_number; ?>" required>
        </div>

        <div class="form-group">
            <label for="ToNumber">Destination Number</label>
            <input type="text" class="form-control" id="ToNumber" name="to_number" value="<?php echo $to_number; ?>" required>
        </div>

        <div class="form-group">
            <p>* Please obtain your Account SID by registering on the <a href="http://account.apifonica.com">http://account.apifonica.com</a> web site.<br />
            ** After registering and verifying your account, you can view your Account SID and Token in the API Access section of the <a href="http://account.apifonica.com">Apifonica Accounts</a> site.<br />
            *** Your Application SID is obtained by registering an application in the <a href="https://account.apifonica.com/applications/manage/">Manage Applications</a> section of the Apifonica Accounts site.
            </p>
        </div>

    <button type="submit" class="btn btn-default">Make Your Call</button>

    <?php } ?>
</form>
</p>
</body>
</html>
