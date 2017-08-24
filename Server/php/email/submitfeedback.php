<?php
/*
  This is a script for mailing a report as posted by FeedbackReporter
  https://github.com/tcurdt/feedbackreporter

  It formats the output into a fairly readable HTML document. It should also
  be readable by an XML parser (after you remove the email headers). But you
  will have to decode the html entities after you read each section.
*/

include __DIR__ . '/config.php';
include __DIR__ . '/submitfeedback_lib.php';

// START

// I'm ignoring the $_GET data, instead I post a custom key called 'application' from the app
$ok = $_SERVER['REQUEST_METHOD'] == 'POST'
      && isSupportedAgent($_SERVER['HTTP_USER_AGENT'])
      && isSupportedApp($_POST['application']);
if ($ok) {
    $title  = createTitle($_POST['application'], $_POST['type'], $_POST['email']);
    $report = beginningOfReportWithTitle($title).bodyOfReport($_POST).endOfReport();
    if (!(sendReport($title, $report))) {
        // don't send detailed error reports on the production server
        // echo 'error with sendReport';
        sendReport('Failed sending crash report', 'sendReport() failed');
    }
    // this is to cause the app to not close the feedback report window (good for testing, make sure to comment this out when copying to the production server)
    // echo 'Testing';
} else {
  // not a proper feedback request
  // always send a message of some sort so you can see what a bot is up to
    if (count($_REQUEST)) {
        $ip = get_client_ip();
        $subject = 'Crash report unexpected error';
        $get = htmlspecialchars(json_encode($_GET));
        $post = htmlspecialchars(json_encode($_POST));
        $files = htmlspecialchars(json_encode($_FILES));
        $agent = htmlspecialchars(json_encode($_SERVER['HTTP_USER_AGENT']));
        $message = <<<EOT
Page called with the wrong parameters<br><br>
IP: {$ip}<br><br>
GET: {$get}<br><br>
POST: {$post}<br><br>
FILES: {$files}<br><br>
HTTP_USER_AGENT: {$agent}<br><br>

EOT;
        sendReport('Failed crash report attempt', cleanerString($message));
    } else {
        header("Location: {$redirect_to}");
    }

    // send them somewhere useful
    $redirect_to = Config::get('redirect_to');
    if(!empty($redirect_to)){
        header("Location: {$redirect_to}");
    }
}
