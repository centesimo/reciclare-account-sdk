<?php

namespace BetterDev\AccountClientSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Session;
use Carbon\Carbon;
use Aura\Session\SessionFactory;
use Aura\Session\Segment;

class AccountApiClientUser
{
    public static function appId()
    {
        return AccountApiConfig::$api_client_app_id;
    }
    public static function appSecret()
    {
        return AccountApiConfig::$api_client_app_secret;
    }
    public static function serverApiUrl()
    {
        return AccountApiConfig::$api_url;
    }
    public static function serverApiUrlUserGetToken()
    {
        return AccountApiConfig::$api_url.'/access_token';
    }
    public static function serverApiUrlUserGetall()
    {
        return AccountApiConfig::$api_url.'/user/getall';
    }
    public static function serverApiUrlUserGet()
    {
        return AccountApiConfig::$api_url.'/user/get';
    }
    public static function serverApiUrlUserMe()
    {
        return AccountApiConfig::$api_url.'/user/me';
    }
    public static function serverApiUrlUserRegister()
    {
        return AccountApiConfig::$api_url.'/user/register';
    }
    public static function serverApiUrlUserUpdate()
    {
        return AccountApiConfig::$api_url.'/user/update';
    }
    public static function serverApiUrlUserActivate()
    {
        return AccountApiConfig::$api_url.'/user/activate';
    }
    public static function serverApiUrlUserDeactivate()
    {
        return AccountApiConfig::$api_url.'/user/deactivate';
    }

    private static $session = null;

    public static function getSession(){
        if (!AccountApiClientUser::$session){
            $session_factory = new SessionFactory();
            $session = $session_factory->newInstance($_COOKIE);
            $segment = $session->getSegment('BetterDev\AccountSDK\Token');
            AccountApiClientUser::$session = $segment;
        }
        return AccountApiClientUser::$session;
    }

    public static function getToken()
    {
        /* @var $session Segment*/
        $session = AccountApiClientUser::getSession();
        $token_response = $session->get('token_response');
        if ($token_response)
        {
            $token_datetime = $session->get('token_datetime');
            if ($token_datetime){
                $seconds_left = ($token_response->expires_in - $token_datetime->diffInSeconds(Carbon::now()));
                if (($token_datetime) && ($seconds_left <= 0))
                {
                    return AccountApiClientUser::refreshToken($token_response->refresh_token);
                }
            }
            if ((property_exists($token_response, 'access_token')) && (property_exists($token_response, 'refresh_token')))
            {
                return $token_response;
            }
        }
        throw new AccountApiClientException('Erro recuperando o token.');
    }

    public static function refreshToken($refresh_token)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUser::serverApiUrlUserGetToken(), [
                'form_params' =>
                    [
                        "grant_type" => "refresh_token",
                        "refresh_token" => $refresh_token,
                        "client_id" => AccountApiClientUser::appId(),
                        "client_secret" => AccountApiClientUser::appSecret()
                    ]
            ]);
            $token_response = json_decode($res->getBody());
            AccountApiClientUser::saveTokenSession($token_response);
            return $token_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro atualizando o token.', $error_messages);
        }
    }

    public static function doLogin($user_name, $password, $redirectURL = null, $callBackURL = null, $appName=null, $appSecret=null)
    {
        try {
            $client = new Client();
            $appname = AccountApiClientUser::appId();
            $appsecret = AccountApiClientUser::appSecret();
            if (($appName) && ($appSecret)){
                $appname = $appName;
                $appsecret = $appSecret;
            }
            $res = $client->request('POST', AccountApiClientUser::serverApiUrlUserGetToken(), [
                'form_params' =>
                    [
                        "grant_type" => "password",
                        "client_id" => $appname,
                        "client_secret" => $appsecret,
                        "username" => $user_name,
                        "password" => $password
                    ]
            ]);
            $token_response = json_decode($res->getBody());

            if ($redirectURL){
                header("Location: ".$redirectURL."?".http_build_query($token_response));
                die;
            }
            if ($callBackURL){
                $client = new Client();
                $res = $client->request('POST', urldecode($callBackURL), [
                    'form_params' =>
                        [
                            "acces_token" => $token_response
                        ]
                ]);
                die;
            }

            AccountApiClientUser::saveTokenSession($token_response);

            return $token_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro fazendo login, sem token.', $error_messages);
        }
    }

    public static function getAllUsers($token, $page = null, $search = null)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUser::serverApiUrlUserGetall(), [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'page' => $page,
                        'search' => $search
                    ]
            ]);
            $allUsers_response = json_decode($res->getBody());
            return $allUsers_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro pegando todos os users', $error_messages);
        }
    }

    public static function getUser($token, $login)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUser::serverApiUrlUserGet().'/'.$login, [
                'form_params' =>
                    [
                        'access_token' => $token
                    ]
            ]);
            $getUser_response = json_decode($res->getBody());
            return $getUser_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro recuperando dados do usuário.', $error_messages);
        }
    }

    /***
     * @param $token
     * @return mixed
     * @throws AccountApiClientException
     */
    public static function me($token)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUser::serverApiUrlUserMe(), [
                'form_params' =>
                    [
                        'access_token' => $token
                    ]
            ]);
            $allUsers_response = json_decode($res->getBody());
            return $allUsers_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro pegando meus dados.', $error_messages);
        }
    }

    public static function registerUser($token, $user)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUser::serverApiUrlUserRegister(), [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'login' => $user['login'],
                        'password'=> $user['password'],
                        'password_confirmation' => $user['password_confirmation'],
                        'metadatas' => $user['metadatas']
                    ]
            ]);
            $registerUser_response = json_decode($res->getBody());
            return $registerUser_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro tentando criar um usuário.', $error_messages);
        }
    }

    public static function updateUser($token, $user)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUser::serverApiUrlUserUpdate(), [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'login' => $user['login'],
                        'password'=> $user['password'],
                        'password_confirmation' => $user['password_confirmation'],
                        'metadatas' => $user['metadatas']
                    ]
            ]);
            $updateUser_response = json_decode($res->getBody());
            return $updateUser_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro atualizando um usuário.', $error_messages);
        }
    }

    public static function activateUser($token, $login)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUser::serverApiUrlUserActivate().'/'.$login, [
                'form_params' =>
                    [
                        'access_token' => $token
                    ]
            ]);
            $activateUser_response = json_decode($res->getBody());
            return $activateUser_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro ativando usuário.', $error_messages);
        }
    }

    public static function deactivateUser($token, $login)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUser::serverApiUrlUserDeactivate().'/'.$login, [
                'form_params' =>
                    [
                        'access_token' => $token
                    ]
            ]);
            $deactivateUser_response = json_decode($res->getBody());
            return $deactivateUser_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro desativando usuário.', $error_messages);
        }
    }

    public static function logout(){
        AccountApiClientUser::removeTokenSession();
    }

    public static function saveTokenSession($token){
        /* @var $session Segment*/
        $session = AccountApiClientUser::getSession();
        $session->set('token_response', $token);
        $session->set('token_datetime', Carbon::now());
    }

    public static function removeTokenSession(){
        /* @var $session Segment*/
        $session = AccountApiClientUser::getSession();
        $session->setFlash('token_response', null);
        $session->setFlash('token_datetime', null);
        $session->clear();
    }
}