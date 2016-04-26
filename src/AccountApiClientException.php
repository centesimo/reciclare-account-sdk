<?php

namespace BetterDev\AccountApiSDK;

class AccountApiClientException extends \Exception
{
	public $errors;

	// Redefine a exceção de forma que a mensagem não seja opcional
    public function __construct($message, $errors = null, $code = 0, Exception $previous = null) {
        // código
    	$this->errors = $errors;

        // garante que tudo está corretamente inicializado
        parent::__construct($message, $code, $previous);
    }

    // personaliza a apresentação do objeto como string
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function customFunction() {
        echo "Uma função específica desse tipo de exceção\n";
    }
}