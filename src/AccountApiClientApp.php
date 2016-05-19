<?php

namespace BetterDev\AccountClientSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Illuminate\Session;
use Carbon\Carbon;

class AccountApiClientApp
{
	public static function serverApiUrlAppGetall()
	{
		return Config::get('account_client.server-api-url').'/client/getall';
	}
	public static function serverApiUrlAppGet()
	{
		return Config::get('account_client.server-api-url').'/client/get';
	}
	public static function serverApiUrlAppRegister()
	{
		return Config::get('account_client.server-api-url').'/client/register';
	}
	public static function serverApiUrlAppUpdate()
	{
		return Config::get('account_client.server-api-url').'/client/update';
	}
	public static function serverApiUrlAppActivate()
	{
		return Config::get('account_client.server-api-url').'/client/activate';
	}
	public static function serverApiUrlAppDeactivate()
	{
		return Config::get('account_client.server-api-url').'/client/deactivate';
	}

	public static function getAllApps($token)
	{
		try {
	        $client = new Client();
	        $res = $client->request('POST', AccountApiClientApp::serverApiUrlAppGetall(), [
	        	'form_params' =>
	        	[
	        		'access_token' => $token
	            ]
	        ]);
			$allApps_response = json_decode($res->getBody());
	        return $allApps_response;
		} catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro pegando todos os users', $error_messages);
		}
	}

	public static function getApp($token, $login)
	{
		try {
			$client = new Client();
			$res = $client->request('POST', AccountApiClientApp::serverApiUrlAppGet().'/'.$login, [
				'form_params' =>
					[
						'access_token' => $token
					]
			]);
			$getApp_response = json_decode($res->getBody());
			return $getApp_response;
		} catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro recuperando dados do usuário.', $error_messages);
		}
	}

	public static function registerApp($token, $user)
	{
		try {
			$client = new Client();
			$res = $client->request('POST', AccountApiClientApp::serverApiUrlAppRegister(), [
				'form_params' =>
					[
						'access_token' => $token,
    					'name' => $user['name'],
    					'email' => $user['email'],
    					'login' => $user['login'],
    					'password'=> $user['password'],
    					'password_confirmation' => $user['password_confirmation']
					]
			]);
			$registerApp_response = json_decode($res->getBody());
			return $registerApp_response;
		} catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro tentando criar um usuário.', $error_messages);
		}
	}

	public static function updateApp($token, $user)
	{
		try {
			$client = new Client();
			$res = $client->request('POST', AccountApiClientApp::serverApiUrlAppUpdate(), [
				'form_params' =>
					[
						'access_token' => $token,
						'id' => $user['id'],
						'name' => $user['name'],
						'email' => $user['email'],
						'login' => $user['login'],
						'password'=> $user['password'],
						'password_confirmation' => $user['password_confirmation']
					]
			]);
			$updateApp_response = json_decode($res->getBody());
			return $updateApp_response;
		} catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro atualizando um usuário.', $error_messages);
		}
	}

	public static function activateApp($token, $login)
	{
		try {
			$client = new Client();
			$res = $client->request('POST', AccountApiClientApp::serverApiUrlAppActivate().'/'.$login, [
				'form_params' =>
					[
						'access_token' => $token
					]
			]);
			$activateApp_response = json_decode($res->getBody());
			return $activateApp_response;
		} catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro ativando usuário.', $error_messages);
		}
	}

	public static function deactivateApp($token, $login)
	{
		try {
			$client = new Client();
			$res = $client->request('POST', AccountApiClientApp::serverApiUrlAppDeactivate().'/'.$login, [
				'form_params' =>
					[
						'access_token' => $token
					]
			]);
			$deactivateApp_response = json_decode($res->getBody());
			return $deactivateApp_response;
		} catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro desativando usuário.', $error_messages);
		}
	}
}