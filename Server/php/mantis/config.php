<?php
	$private_config = __DIR__ . '/config.private.php';
	if(file_exists($private_config)){
		include $private_config;
	}

	// the reporter of the issues
	define( 'MANTIS_USER',	'user' );
	define( 'MANTIS_PWD',	'password' );
	
	// If true, this script is running on the same machine hosting mantis,
	// so we can use its API directly.
	// Recommendation: Use the SOAP API. It should be more stable.
	define( 'MANTIS_LOCAL',	false );
	// path to your mantis installation, only needed if MANTIS_LOCAL is true
	define( 'MANTIS_PATH',	dirname( __FILE__ ) . '/../mantis/' );

	// used only when MANTIS_LOCAL is false. The SOAP extension is required.
	define( 'MANTIS_URL',	'http://www.yoursite.com/mantis/' );
	define( 'MANTIS_WSDL',	MANTIS_URL . 'api/soap/mantisconnect.php?wsdl' );

	// constants for the reports
	define( 'BUG_CATEGORY',	'Feedback' );
