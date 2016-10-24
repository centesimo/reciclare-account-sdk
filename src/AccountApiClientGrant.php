<?php
/**
 * Created by PhpStorm.
 * User: marcelozani
 * Date: 14/07/16
 * Time: 17:18
 */

namespace BetterDev\AccountClientSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Illuminate\Session;
use Carbon\Carbon;
use Mockery\CountValidator\Exception;

class AccountApiClientGrant
{
    public static function serverApiUrlGrantGetall()
    {
        return AccountApiConfig::$api_url.'/grants/getall';
    }
    public static function serverApiUrlGrantGet()
    {
        return AccountApiConfig::$api_url.'/grants/get';
    }
    public static function serverApiUrlGrantRegister()
    {
        return AccountApiConfig::$api_url.'/grants/create';
    }
    public static function serverApiUrlGrantUpdate()
    {
        return AccountApiConfig::$api_url.'/grants/update';
    }
    public static function serverApiUrlGrantActivate()
    {
        return AccountApiConfig::$api_url.'/grants/activate';
    }
    public static function serverApiUrlGrantDeactivate()
    {
        return AccountApiConfig::$api_url.'/grants/deactivate';
    }
    public static function serverApiUrlGrantAddGroup()
    {
        return AccountApiConfig::$api_url.'/grants/addgroup';
    }

    public static function getAllGrants($token, $page = null)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientGrant::serverApiUrlGrantGetall(), [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'page' => $page
                    ]
            ]);
            $allGrants_response = json_decode($res->getBody());
            return $allGrants_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro pegando todos os grupos', $error_messages);
        }
    }

    public static function getGrant($token, $id)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientGrant::serverApiUrlGrantGet().'/'.$id, [
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

    public static function registerGrant($token, $grant)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientGrant::serverApiUrlGrantRegister() , [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'description' => $grant['description'],
                        'short_code' => $grant['short_code']
                    ]
            ]);
            $registerGrant_response = json_decode($res->getBody());
            return $registerGrant_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro tentando criar um Grupo.', $error_messages);
        }
    }

    public static function updateGrant($token, $grant_id, $grant)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientGrant::serverApiUrlGrantUpdate().'/'.$grant_id, [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'description' => $grant['description'],
                        'short_code' => $grant['short_code']
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

    public static function activateGrant($token, $id)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientGrant::serverApiUrlGrantActivate().'/'.$id, [
                'form_params' =>
                    [
                        'access_token' => $token
                    ]
            ]);
            $activateGrant_response = json_decode($res->getBody());
            return $activateGrant_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro ativando aplicativo.', $error_messages);
        }
    }

    public static function deactivateGrant($token, $id)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientGrant::serverApiUrlGrantDeactivate().'/'.$id, [
                'form_params' =>
                    [
                        'access_token' => $token
                    ]
            ]);
            $deactivateGrant_response = json_decode($res->getBody());
            return $deactivateGrant_response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401){
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro desativando aplicativo.', $error_messages);
        }
    }

    public static function addGroup($token, $grant_id, $group_add_ids, $group_remove_ids)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientGrant::serverApiUrlGrantAddGroup().'/'.$grant_id, [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'groups_add' => $group_add_ids,
                        'groups_remove' => $group_remove_ids
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
}