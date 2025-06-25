<?php

namespace Controllers;

use Servico\LoginServico;

class LoginController {

    // realizar login
    public function login() {
        $loginServico = new LoginServico();

        return $loginServico->login();
    }

    // efetuar logout do usuário
    public function logout() {
        $loginServico = new LoginServico();

        return $loginServico->logout();
    }

}