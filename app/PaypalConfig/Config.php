<?php

// PayPal Environment 
//define("PAYPAL_ENVIRONMENT", "sandbox");
define("PAYPAL_ENVIRONMENT", "production");

// PayPal REST API endpoints
define("PAYPAL_ENDPOINTS", array(
	"sandbox" => "https://api.sandbox.paypal.com",
	"production" => "https://api.paypal.com"
));

// PayPal REST App credentials
define("PAYPAL_CREDENTIALS", array(
	"sandbox" => [
		"client_id" => "AcnIDyl2_CkrcUup_vpY1Ttraiiu-B79GGuzh2IY2fIuKcH76N9-EQ_SMtjXNUPtcdEuq_uo35_YzaF5",
		"client_secret" => "EATpwbVvZZtmWzyKnchWBmjgmn_A2ter3hEIzANcqu2rRGvFawazPKRDEpfMZRd2xWy2DPisJLjY1__Y"
	],
	"production" => [
		"client_id" => "AZ43o7FX2C_76ZZdKUpcPt7N3nxIsOIwi8h7gaRLBpwO537yYJZ0bwjpnoQDd8DNWVZ88vZM861OyAc-",
		"client_secret" => "EEwQn47sGvaAbZKFSSvfP4aYzL3xZ4nnqX_lzc-3t3m_HEkRJpX7oX0Cq0NUXR4tWCrcXLczv6mZSgoL"
	]
));

// PayPal REST API version
define("PAYPAL_REST_VERSION", "v2");

// ButtonSource Tracker Code
define("SBN_CODE", "CUN SPBs");
