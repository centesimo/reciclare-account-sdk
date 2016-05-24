<?php

namespace BetterDev\AccountClientSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Illuminate\Session;
use Carbon\Carbon;
use Mockery\CountValidator\Exception;

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
	public static function serverApiUrlAppAddUser()
	{
		return Config::get('account_client.server-api-url').'/client/adduser';
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

	public static function registerApp($token, $app)
	{
		try {
			$client = new Client();
			$res = $client->request('POST', AccountApiClientApp::serverApiUrlAppRegister() , [
				'form_params' =>
					[
						'access_token' => $token,
    					'name' => $app['name'],
    					'id' => $app['id'],
    					'secret' => $app['secret'],
						'owner_user_id' => $app['owner_user_id']
					]
			]);
			$registerApp_response = json_decode($res->getBody());
			return $registerApp_response;
		} catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro tentando criar um aplicativo.', $error_messages);
		}
	}

	public static function updateApp($token, $app_id, $app)
	{
		try {
			$client = new Client();
			$res = $client->request('POST', AccountApiClientApp::serverApiUrlAppUpdate().'/'.$app_id, [
				'form_params' =>
					[
						'access_token' => $token,
						'name' => $app['name'],
						'id' => $app['id'],
						'secret' => $app['secret'],
						'owner_user_id' => $app['owner_user_id']
					]
			]);
			$updateApp_response = json_decode($res->getBody());
			return $updateApp_response;
		} catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro atualizando um aplicativo.', $error_messages);
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

			throw new AccountApiClientException('Erro ativando aplicativo.', $error_messages);
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

			throw new AccountApiClientException('Erro desativando aplicativo.', $error_messages);
		}
	}

	public static function addUser($token, $app_id, $user_add_ids, $user_remove_ids)
	{
		try {
			$client = new Client();
			$res = $client->request('POST', AccountApiClientApp::serverApiUrlAppAddUser().'/'.$app_id, [
				'form_params' =>
					[
						'access_token' => $token,
						'users_add' => $user_add_ids,
						'users_remove' => $user_remove_ids
					]
			]);
			$addUser_response = json_decode($res->getBody());
			return $addUser_response;
		}
		catch (Exception $e){
			throw $e;
		}
		catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro atualizando um aplicativo.', $error_messages);
		}
	}
}