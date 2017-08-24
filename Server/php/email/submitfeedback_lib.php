<?php

// change the css to customize the look
function beginningOfReportWithTitle($reportTitle)
{
  $reportTitle = htmlspecialchars($reportTitle);
  return
"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\">
<html>
<head>
  <title>{$reportTitle}</title>
  <style type=\"text/css\">
    body {
      font-family: \"Lucida Grande\", sans-serif;
      font-size: 75%;
    }
    .content {
      background-color: #e7e7e7;
      border: 1px solid #e7e7e7;
      width: 100%;
    }
    code {
      display: block;
      font: 10pt Monaco, mono;
      margin: 1em;
      padding: 1em;
    }
    .navBar {
      padding-top: 2em;
      padding-bottom: 2em;
      margin-left: 2em;
    }
  </style>
</head>
<body>
<div id=\"FeedbackReporter\">
<h1>{$reportTitle}</h1>";
}


function bodyOfReport($postedData)
{
  $keys   = array_keys(Config::get('data_accepted'));
  $titles = Config::get('data_accepted');

  // build the navigation bar that will be placed between each section of the report
  $navigationBarArray = array();
  foreach ($keys as $key) {
    $title = !empty($titles[$key]) ? htmlspecialchars($titles[$key]) : htmlspecialchars($key);
    $navigationBarArray[] = "<a href=\"#{$title}\">{$title}</a>";
  }
  $navigationBar = "<div class=\"navBar\">".implode(' | ', $navigationBarArray)."</div>";

  // build up each section to create the body of the report
  $body = '';
  foreach ($keys as $key) {
    $title = !empty($titles[$key]) ? htmlspecialchars($titles[$key]) : htmlspecialchars($key);
    $content = !empty($postedData[$key]) ? htmlspecialchars($postedData[$key]) : '-empty-';
    $body   .= <<<EOT
<div id="{$title}">
    <h2>{$title}:</h2>
    <div class="content">
        <code>{$content}</code>
    </div>
</div>
{$navigationBar}

EOT;
  }

  return $body;
}

// an ounce of prevention...
function cleanerString($input)
{
  if (empty($input)){
    return '';
  }

  $badStuph = array('to:', 'cc:', 'bcc:', 'from:', 'return-path:', 'content-type:', 'mime-version:', 'multipart-mixed:', 'content-transfer-encoding:');

  // if any bad things are found don't use the input at all (as there may be other unknown bad things)
  foreach ($badStuph as $badThing){
    if (stripos($input, $badThing) !== false){
        return 'Found bad things';
    }
  }

  // these aren't technically bad things by themselves, but clean them up for good measure
  $input = str_replace(array("\r", "\n", "%0a", "%0d"), ' ', $input);
  return trim($input);
}

function createTitle($appName, $type, $fromAddress)
{
  $type = $type;
  $title = 'Crash Report';

  if ($type == 'feedback') {
    $title = 'Feedback Report';
  } elseif ($type == 'exception') {
    $title = 'Exception Report';
  }

  $fromAddress = cleanerString($fromAddress);
  if (empty($fromAddress)) {
    $fromAddress = 'Anonymous';
  }

  $title .= " from {$fromAddress} for {$appName}";

  return $title;
}

function endOfReport()
{
  return "</div>\n</body>\n</html>";
}

function get_client_ip() {
  $envs = [
      'HTTP_CLIENT_IP'
    , 'HTTP_X_FORWARDED_FOR'
    , 'HTTP_X_FORWARDED'
    , 'HTTP_FORWARDED_FOR'
    , 'HTTP_FORWARDED'
    , 'REMOTE_ADDR'
  ];
  foreach($envs as $env){
    if(getenv($env)){
      return getenv($env);
    }
  }
  return 'UNKNOWN';
}

// this will check the userAgent against multiple apps you may be supporting
function isSupportedAgent($userAgent)
{
  $userAgent = urldecode($userAgent);

  foreach (Config::get('supported_apps') as $appName){
    if (strncasecmp($userAgent, $appName, strlen($appName)) == 0){
        return true;
    }
  }

  return false;
}

// this will check the title against multiple apps you may be supporting
function isSupportedApp($appName)
{
  return in_array($appName, Config::get('supported_apps'));
}

// Don't put user submitted email addresses in the From or Return-Path headers,
// if your mail server is down it will bounce back to that address.
// A malicious person could send spam that way.
// Better to use an account at a seperate email provider so you won't miss a report.
function sendReport($subject, $message)
{
  $to       = Config::get('email_to');
  $from     = Config::get('email_from');
  $EOL      = Config::get('EOL');
  $headers  = "From: {$from}{$EOL}";
  $headers .= "Return-Path: {$from}{$EOL}";
  $headers .= "MIME-Version: 1.0{$EOL}";
  $headers .= "Content-Type: text/html; charset=\"utf-8\"{$EOL}";

  if (empty($message)){
    $message = 'There is no message';
  }

  $subject = cleanerString($subject);
  if (empty($subject)){
    $subject = 'There is no subject';
  }

  return mail($to, $subject, $message, $headers);
}
