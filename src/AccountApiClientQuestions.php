<?php

namespace BetterDev\AccountClientSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Illuminate\Session;
use Carbon\Carbon;

class AccountApiClientQuestions
{
    public static function serverApiUrlQuestionsGetAll()
    {
        return AccountApiConfig::$api_url . '/security/questions';
    }

    public static function serverApiUrlQuestionsGet()
    {
        return AccountApiConfig::$api_url . '/security/questions';
    }

    public static function serverApiUrlQuestionsRegister()
    {
        return AccountApiConfig::$api_url . '/security/questions';
    }

    public static function serverApiUrlQuestionsUpdate()
    {
        return AccountApiConfig::$api_url . '/security/questions';
    }

    public static function getAllQuestions($token, $page = null)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', AccountApiClientQuestions::serverApiUrlQuestionsGetAll(), [
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
            throw new AccountApiClientException('Erro pegando todos as perguntas', $error_messages);
        }
    }

    public static function getQuestion($token = null, $question_id, $page = null)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', AccountApiClientQuestions::serverApiUrlQuestionsGet() . '/' . $question_id, [
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
            $res = $client->request('POST', AccountApiClientQuestions::serverApiUrlQuestionsRegister(), [
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
            $res = $client->request('PUT', AccountApiClientQuestions::serverApiUrlQuestionsUpdate() . '/' . $question_id, [
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
}