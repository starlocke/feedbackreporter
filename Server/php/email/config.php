<?php

class Config {
    protected static $config;

    static public function init($config){
        Config::$config = $config;
    }
    static public function get($key){
        return Config::$config[$key];
    }
}

// Use "config.private.php" to override $config values
$private_config = __DIR__ . '/config.private.php';
if(file_exists($private_config)) {
    include $private_config;
} else {
    $config = [
          'EOL'         => "\r\n" // sometimes should be '\r\n', sometimes just '\n'
        , 'email_to'    => 'root@localhost'
        , 'email_from'  => 'root@localhost'
        , 'redirect_to' => 'http://localhost/'
        , 'supported_apps' => ['Hello World', 'My Awesome App']
        , 'data_accepted' => [
            'email'         => 'E-mail'
            , 'comment'     => 'Comment'
            , 'crashes'     => 'Crash Report'
            , 'system'      => 'System Information'
            , 'preferences' => 'Preferences'
            , 'console'     => 'Console Output'
            , 'exception'   => 'Exception'
            , 'shell'       => 'Shell'
        ]
    ];
    Config::init($config);
}
