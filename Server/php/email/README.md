# E-mail Relay

## !!IMPORTANT!! Server Configuration for Security

You must configure your web server to block requests to:

- config.php
- config.private.php
- submitfeedback_lib.php

You should configure your web server to block requests to:

- README.md

## End-of-Line string for PHP mail()

Sometimes, it is \n that works; other times, it is \r\n that will work.

Therefore, the EOL is a configurable option.
