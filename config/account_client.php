<?php

return [
	/*
	|--------------------------------------------------------------------------
	| Account URL
	|--------------------------------------------------------------------------
	| This file is for storing the credentials for the account client.
	| 
	| 
	| 
	| 
	*/
	'server-api-url' => env('CLIENT_URL', 'http://localhost:8000'),

	/*
	|--------------------------------------------------------------------------
	| Account Credentials
	|--------------------------------------------------------------------------
	| This file is for storing the credentials for the account client.
	| 
	| 
	| 
	| 
	*/
	'client-app-id' => env('CLIENT_ID', 'acc-centesimo'),
	'client-app-secret' => env('CLIENT_SECRET', '1234567890'),
];
