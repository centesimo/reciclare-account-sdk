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
        return AccountApiConfig::$api_url.'/groups/getall';
    }
    public static function serverApiUrlGroupGet()
    {
        return AccountApiConfig::$api_url.'/groups/get';
    }
    public static function serverApiUrlGroupRegister()
    {
        return AccountApiConfig::$api_url.'/groups/create';
    }
    public static function serverApiUrlGroupUpdate()
    {
        return AccountApiConfig::$api_url.'/groups/update';
    }
    public static function serverApiUrlGroupActivate()
    {
        return AccountApiConfig::$api_url.'/groups/activate';
    }
    public static function serverApiUrlGroupDeactivate()
    {
        return AccountApiConfig::$api_url.'/groups/deactivate';
    }
    public static function serverApiUrlGroupAddUser()
    {
        return AccountApiConfig::$api_url.'/groups/adduser';
    }
    public static function serverApiUrlGroupAddClient()
    {
        return AccountApiConfig::$api_url.'/groups/addclient';
    }	public static function serverApiUrlGroupAddGrant()
{
    return AccountApiConfig::$api_url.'/groups/addgrant';
}
    public static function getAllGroups($token, $page = null)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUserGroup::serverApiUrlGroupGetall(), [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'page' => $page
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

    public static function getGroup($token, $id)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUserGroup::serverApiUrlGroupGet().'/'.$id, [
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

            throw new AccountApiClientException('Erro recuperando dados do grupo.', $error_messages);
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

    public static function updateGroup($token, $group_id, $group)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUserGroup::serverApiUrlGroupUpdate().'/'.$group_id, [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'description' => $group['description'],
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

    public static function activateGroup($token, $id)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUserGroup::serverApiUrlGroupActivate().'/'.$id, [
                'form_params' =>
                    [
                        'access_token' => $token
                    ]
            ]);
            $activateGroup_response = json_decode($res->getBody());
            return $activateGroup_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro ativando aplicativo.', $error_messages);
        }
    }

    public static function deactivateGroup($token, $id)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUserGroup::serverApiUrlGroupDeactivate().'/'.$id, [
                'form_params' =>
                    [
                        'access_token' => $token
                    ]
            ]);
            $deactivateGroup_response = json_decode($res->getBody());
            return $deactivateGroup_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro desativando aplicativo.', $error_messages);
        }
    }

    public static function addUser($token, $group_id, $user_add_ids, $user_remove_ids)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUserGroup::serverApiUrlGroupAddUser().'/'.$group_id, [
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
            throw new AccountApiClientException('Erro atualizando um grupo.', $error_messages);
        }
    }

    public static function addClient($token, $group_id, $client_add_ids, $client_remove_ids)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUserGroup::serverApiUrlGroupAddClient().'/'.$group_id, [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'clients_add' => $client_add_ids,
                        'clients_remove' => $client_remove_ids
                    ]
            ]);
            $addClient_response = json_decode($res->getBody());
            return $addClient_response;
        }
        catch (Exception $e){
            throw $e;
        }
        catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }
            throw new AccountApiClientException('Erro atualizando um grupo.', $error_messages);
        }
    }

    public static function addGrant($token, $group_id, $grant_add_ids, $grant_remove_ids)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientUserGroup::serverApiUrlGroupAddGrant().'/'.$group_id, [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'grants_add' => $grant_add_ids,
                        'grants_remove' => $grant_remove_ids
                    ]
            ]);
            $addGrant_response = json_decode($res->getBody());
            return $addGrant_response;
        }
        catch (Exception $e){
            throw $e;
        }
        catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }
            throw new AccountApiClientException('Erro atualizando um grupo.', $error_messages);
        }
    }
}