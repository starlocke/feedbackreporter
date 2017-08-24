<?php
$config = [
      'EOL'         => "\n" // sometimes should be '\r\n', sometimes just '\n'
    , 'email_to'    => 'root@localhost'
    , 'email_from'  => 'root@localhost'
    , 'redirect_to' => ''
    , 'supported_apps' => ['Hello World', 'My Awesome App']
    , 'data_accepted' => [
          'console' => 'Console Output'
        , 'comment' => 'Comment'
        , 'crashes' => 'Crash Report' // original
        , 'email' => 'E-mail'
        , 'exception' => 'Exception' // original
        , 'preferences' => 'Preferences'
        , 'shell' => 'Shell' // original
        , 'system' => 'System Information'
        , 'type' => 'Type'
        , 'version' => 'Version'
        , 'version_short' => 'Version (short)'
        , 'version_bundle' => 'Version (bundle)'
    ]
];
Config::init($config);

if(1){
  // a common report
  $_SERVER['REQUEST_METHOD'] = 'POST';
  $_SERVER['HTTP_USER_AGENT'] = 'Hello World';
  $_POST['application'] = 'Hello World';

  $_POST['console'] = '<img src=x onerror=alert(123) />';
  $_POST['comment'] = '<img src=x onerror=alert(123) />';
  $_POST['crashes'] = '<img src=x onerror=alert(123) />'; // original
  $_POST['email'] = '<img src=x onerror=alert(123) />';
  $_POST['exception'] = '<img src=x onerror=alert(123) />'; // original
  $_POST['preferences'] = '<img src=x onerror=alert(123) />';
  $_POST['shell'] = '<img src=x onerror=alert(123) />'; // original
  $_POST['system'] = '<img src=x onerror=alert(123) />';
  $_POST['type'] = '<img src=x onerror=alert(123) />'; // "feedback", "exception", or "crash" (actually any value)
  $_POST['version'] = '<img src=x onerror=alert(123) />';
  $_POST['version_short'] = '<img src=x onerror=alert(123) />';
  $_POST['version_bundle'] = '<img src=x onerror=alert(123) />';
  $_POST['type'] = '<img src=x onerror=alert(123) />';
  $_POST['junk1'] = '<img src=x onerror=alert(123) />';
  $_POST['junk2'] = '<img src=x onerror=alert(123) />';
  $_POST['junk3'] = '<img src=x onerror=alert(123) />';
  $_POST['junk4'] = '<img src=x onerror=alert(123) />';
}

if(1){
  // "malicious" junk
  $_SERVER['REQUEST_METHOD'] = 'POST';
  $_SERVER['HTTP_USER_AGENT'] = '<img src=x onerror=alert(123) />';
  $_POST['application'] = '<img src=x onerror=alert(123) />';

  $_POST['foo'] = '<img src=x onerror=alert(123) />';
  $_GET['alice'] = '<img src=x onerror=alert(123) />';
}
