<?php

// PayPal Environment 
define("PAYPAL_ENVIRONMENT", "sandbox");
//define("PAYPAL_ENVIRONMENT", "production");

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
		"client_id" => "AchNiBlV930Kno4SopMQ0YmY5swOWCdVi_WqrOF8uPgl1WaTKdnbGIiTBEZDBclbHx4DkRnz4Y77iP8l",
		"client_secret" => "EKUkb243CboJUvo9NU9sAOz1jy5MxlDARuUTPqsXNgXTuKvLHmnGHjRPMcZ-myMy5yIPIZwjnyArxGej"
	]
));

// PayPal REST API version
define("PAYPAL_REST_VERSION", "v2");

// ButtonSource Tracker Code
define("SBN_CODE", "CUN SPBs");
