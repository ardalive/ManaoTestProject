<?php


namespace app;


class Validator
{
    private $login, $password, $confirm, $email, $name, $response;

    public function __construct($credentials){
        $this->login = $credentials['login'];
        $this->password = $credentials['password'];
        $this->confirm = $credentials['confirm'];
        $this->email = $credentials['email'];
        $this->name = $credentials['name'];
    }

    public function validate($loginArray, $emailArray, $messages){
        $this->response = array();
        if(!$this->loginIsValid()) $this->response['login'] = $messages['invalidLoginMessage'];
        if(!$this->passwordIsValid()) $this->response['password'] = $messages['invalidPasswordMessage'];
        if(!$this->emailIsValid()) $this->response['email'] = $messages['invalidEmailMessage'];
        if(!$this->nameIsValid()) $this->response['name'] = $messages['invalidNameMessage'];
        if($this->loginInUse($loginArray)) $this->response['login'] = $messages['loginInUseMessage'];
        if($this->emailInUse($emailArray)) $this->response['email'] = $messages['emailInUseMessage'];
        return $this->response;
    }

    private function loginIsValid(){
        if(strlen($this->login) < 6 || preg_match('/[^\w\d]/', $this->login)) return false;
        return true;
    }

    private function passwordIsValid(){
        $passwordsMatch = $this->password === $this->confirm;
        $longEnough = strlen($this->password) >= 6;
        $hasLowercase = preg_match('/[a-z]/', $this->password);
        $hasUppercase = preg_match('/[A-Z]/', $this->password);
        $hasNumber = preg_match('/[0-9]/', $this->password);
        $hasSymbol = preg_match('/[^\w\d]/', $this->password);
        if($passwordsMatch && $longEnough && $hasLowercase && $hasUppercase && $hasNumber && $hasSymbol) return true;
        return false;
    }

    private function emailIsValid(){
        if(filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) return false;
        return true;
    }

    private function nameIsValid(){
        if(strlen($this->name) < 2 || preg_match('/[^\w\d]/', $this->name)) return false;
        return true;
    }

    private function loginInUse($loginArray){
        return array_search($this->login, $loginArray) !== false;
    }

    private function emailInUse($emailArray){
        return array_search($this->email, $emailArray) !== false;
    }
}