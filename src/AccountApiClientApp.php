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
        return AccountApiConfig::$api_url . '/client/getall';
    }

    public static function serverApiUrlAppGet()
    {
        return AccountApiConfig::$api_url . '/client_/get';
    }

    public static function serverApiUrlAppGetUsers()
    {
        return AccountApiConfig::$api_url . '/client/get/users';
    }

    public static function serverApiUrlAppRegister()
    {
        return AccountApiConfig::$api_url . '/client/register';
    }

    public static function serverApiUrlAppUpdate()
    {
        return AccountApiConfig::$api_url . '/client/update';
    }

    public static function serverApiUrlAppActivate()
    {
        return AccountApiConfig::$api_url . '/client/activate';
    }

    public static function serverApiUrlAppDeactivate()
    {
        return AccountApiConfig::$api_url . '/client/deactivate';
    }

    public static function serverApiUrlAppAddUser()
    {
        return AccountApiConfig::$api_url . '/client/adduser';
    }

    public static function getAllApps($token, $page = null)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientApp::serverApiUrlAppGetall(), [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'page' => $page
                    ]
            ]);
            $allApps_response = json_decode($res->getBody());
            return $allApps_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }
            throw new AccountApiClientException('Erro pegando todos os users', $error_messages);
        }
    }

    public static function getApp($token = null, $app_id, $page = null)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientApp::serverApiUrlAppGet() . '/' . $app_id, [
                'form_params' =>
                    [
                        'page' => $page
                    ]
            ]);
            $getApp_response = json_decode($res->getBody());
            return $getApp_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }
            throw new AccountApiClientException('Erro recuperando dados do app.', $error_messages);
        }
    }

    public static function getAppUsers($token, $app_id, $page = null)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientApp::serverApiUrlAppGetUsers() . '/' . $app_id, [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'page' => $page
                    ]
            ]);
            $getApp_response = json_decode($res->getBody());
            return $getApp_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }
            throw new AccountApiClientException('Erro recuperando dados do app.', $error_messages);
        }
    }

    public static function registerApp($token, $app)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientApp::serverApiUrlAppRegister(), [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'name' => $app['name'],
                        'id' => $app['id'],
                        'secret' => $app['secret'],
                        'owner_user_id' => $app['owner_user_id'],
                        'custom_logo_url' => $app['custom_logo_url'],
                        'custom_login_css' => $app['custom_login_css'],
                    ]
            ]);
            $registerApp_response = json_decode($res->getBody());
            return $registerApp_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro tentando criar um aplicativo.', $error_messages);
        }
    }

    public static function updateApp($token, $app_id, $app)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientApp::serverApiUrlAppUpdate() . '/' . $app_id, [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'name' => $app['name'],
                        'id' => $app['id'],
                        'secret' => $app['secret'],
                        'owner_user_id' => $app['owner_user_id'],
                        'custom_logo_url' => $app['custom_logo_url'],
                        'custom_login_css' => $app['custom_login_css'],
                        'security_questions_enabled' => $app['security_questions_enabled'],
                    ]
            ]);
            $updateApp_response = json_decode($res->getBody());
            return $updateApp_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro atualizando um aplicativo.', $error_messages);
        }
    }

    public static function activateApp($token, $login)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientApp::serverApiUrlAppActivate() . '/' . $login, [
                'form_params' =>
                    [
                        'access_token' => $token
                    ]
            ]);
            $activateApp_response = json_decode($res->getBody());
            return $activateApp_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro ativando aplicativo.', $error_messages);
        }
    }

    public static function deactivateApp($token, $login)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientApp::serverApiUrlAppDeactivate() . '/' . $login, [
                'form_params' =>
                    [
                        'access_token' => $token
                    ]
            ]);
            $deactivateApp_response = json_decode($res->getBody());
            return $deactivateApp_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro desativando aplicativo.', $error_messages);
        }
    }

    public static function addUser($token, $app_id, $user_add_ids, $user_remove_ids)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientApp::serverApiUrlAppAddUser() . '/' . $app_id, [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'users_add' => $user_add_ids,
                        'users_remove' => $user_remove_ids
                    ]
            ]);
            $addUser_response = json_decode($res->getBody());
            return $addUser_response;
        } catch (Exception $e) {
            throw $e;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro atualizando um aplicativo.', $error_messages);
        }
    }
}