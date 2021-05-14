<?php

namespace Reciclare\AccountClientSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Illuminate\Session;
use Carbon\Carbon;

class AccountApiClientQuestions
{
    public static function serverApiUrlQuestions()
    {
        return AccountApiConfig::$api_url . '/security/questions';
    }

    public static function getAllQuestions($token, $page = null, $params = [])
    {
        try {
            $query = array_merge(
                [
                    'access_token' => $token,
                    'page' => $page
                ],
                $params);
            $client = new Client();
            $res = $client->request('GET', AccountApiClientQuestions::serverApiUrlQuestions(), [
                'query' => $query
            ]);
            $response = json_decode($res->getBody());
            return $response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }
            throw new AccountApiClientException('Erro pegando todas as perguntas', $error_messages);
        }
    }

    public static function getQuestion($token = null, $question_id, $page = null)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', AccountApiClientQuestions::serverApiUrlQuestions() . '/' . $question_id, [
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

    public static function registerQuestion($token, $question)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', AccountApiClientQuestions::serverApiUrlQuestions(), [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'oauth_client_id' => isset($question['oauth_client_id']) ? $question['oauth_client_id'] : null,
                        'question' => $question['question'],
                    ]
            ]);
            $response = json_decode($res->getBody());
            return $response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro tentando criar uma pergunta.', $error_messages);
        }
    }

    public static function updateQuestion($token, $question_id, $question)
    {
        try {
            $client = new Client();
            $res = $client->request('PUT', AccountApiClientQuestions::serverApiUrlQuestions() . '/' . $question_id, [
                'form_params' =>
                    [
                        'access_token' => $token,
                        'question' => $question['question'],
                    ]
            ]);
            $response = json_decode($res->getBody());
            return $response;
        } catch (ClientException $e) {
            $error_messages = null;
            if ($e->getCode() == 401) {
                $error_messages = json_decode($e->getResponse()->getBody());
            }

            throw new AccountApiClientException('Erro atualizando a pergunta.', $error_messages);
        }
    }

    public static function deleteQuestion($token = null, $question_id)
    {
        try {
            $client = new Client();
            $res = $client->request(
                'DELETE',
                AccountApiClientQuestions::serverApiUrlQuestions() . '/' . $question_id,
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
            throw new AccountApiClientException('Erro excluindo pergunta.', $error_messages);
        }
    }
}