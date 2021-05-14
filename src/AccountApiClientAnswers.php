<?php

namespace ReciclareAccount\AccountClientSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Illuminate\Session;
use Carbon\Carbon;

class AccountApiClientAnswers
{
    public static function serverApiUrlAnswers()
    {
        return AccountApiConfig::$api_url . '/security/answers';
    }

    public static function getAllAnswers($token, $page = null, $params = [])
    {
        try {
            $query = array_merge(
                [
                    'access_token' => $token,
                    'page' => $page
                ],
                $params
            );
            $client = new Client();
            $res = $client->request('GET', AccountApiClientAnswers::serverApiUrlAnswers(), [
                'query' => $query
            ]);
            $response = json_decode($res->getBody());
            return $response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }
            throw new AccountApiClientException('Erro pegando todas as respostas', $error_messages);
        }
    }

    public static function getAnswer($token = null, $answer_id, $page = null)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', AccountApiClientAnswers::serverApiUrlAnswers() . '/' . $answer_id, [
                'query' =>
                    [
                        'access_token' => $token,
                        'page' => $page
                    ]
            ]);
            $response = json_decode($res->getBody());
            return $response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }
            throw new AccountApiClientException('Erro recuperando dados da pergunta.', $error_messages);
        }
    }

    public static function registerAnswer($token, $answer)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientAnswers::serverApiUrlAnswers(), [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'user_id' => $answer['user_id'],
                        'question_id' => $answer['question_id'],
                        'answer' => $answer['answer'],
                    ]
            ]);
            $response = json_decode($res->getBody());
            return $response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro tentando criar uma resposta.', $error_messages);
        }
    }

    public static function updateAnswer($token, $answer_id, $answer)
    {
        try {
            $client = new Client();
            $res = $client->request('PUT', AccountApiClientAnswers::serverApiUrlAnswers() . '/' . $answer_id, [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'user_id' => $answer['user_id'],
                        'question_id' => $answer['question_id'],
                        'answer' => $answer['answer'],
                    ]
            ]);
            $response = json_decode($res->getBody());
            return $response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro atualizando a resposta.', $error_messages);
        }
    }

    public static function deleteAnswer($token = null, $answer_id)
    {
        try {
            $client = new Client();
            $res = $client->request(
                'DELETE',
                AccountApiClientAnswers::serverApiUrlAnswers() . '/' . $answer_id,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token
                    ]
                ]
            );
            $response = json_decode($res->getBody());
            return $response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }
            throw new AccountApiClientException('Erro excluindo resposta.', $error_messages);
        }
    }

    public static function validate($appId, $login, $answers)
    {
        try {
            $client = new Client();
            $res = $client->request(
                'GET',
                AccountApiClientAnswers::serverApiUrlAnswers() . '/validate',
                [
                    'query' => [
                        'app_id' => $appId,
                        'login' => $login,
                        'answers' => $answers
                    ]
                ]
            );
            $response = json_decode($res->getBody());
            return $response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }
            throw new AccountApiClientException('Erro validando respostas.', $error_messages);
        }
    }
}