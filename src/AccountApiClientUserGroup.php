<?php

namespace BetterDev\AccountClientSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Illuminate\Session;
use Carbon\Carbon;
use Mockery\CountValidator\Exception;

class AccountApiClientUserGroup
{
	public static function serverApiUrlGroupGetall()
	{
		return Config::get('account_client.server-api-url').'/groups/getall';
	}
	public static function serverApiUrlAppGet()
	{
		return Config::get('account_client.server-api-url').'/client/get';
	}
	public static function serverApiUrlGroupRegister()
	{
		return Config::get('account_client.server-api-url').'/groups/create';
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

	public static function getAllGroups($token)
	{
		try {
	        $client = new Client();
	        $res = $client->request('POST', AccountApiClientUserGroup::serverApiUrlGroupGetall(), [
	        	'form_params' =>
	        	[
	        		'access_token' => $token
	            ]
	        ]);
			$allGroups_response = json_decode($res->getBody());
	        return $allGroups_response;
		} catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro pegando todos os grupos', $error_messages);
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

	public static function registerGroup($token, $app)
	{
		try {
			$client = new Client();
			$res = $client->request('POST', AccountApiClientUserGroup::serverApiUrlGroupRegister() , [
				'form_params' =>
					[
						'access_token' => $token,
    					'description' => $app['description']
					]
			]);
			$registerGroup_response = json_decode($res->getBody());
			return $registerGroup_response;
		} catch (ClientException $e) {
			$error_messages = null;
			if ($e->getCode() == 401){
				$error_messages = json_decode($e->getResponse()->getBody());
			}

			throw new AccountApiClientException('Erro tentando criar um Grupo.', $error_messages);
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