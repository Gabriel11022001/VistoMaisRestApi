<?php

namespace Servico;

use Exception;
use Utils\Resposta;

class LoginServico extends ServicoBase {

    public function __construct()
    {
        parent::__construct();
    }

    // realizar login do usuário
    public function login() {
        $this->bancoDados->beginTransaction();

        try {
            $login = getParametro("login");
            $senha = getParametro("senha");

            $errosCampos = [];
        } catch (Exception $e) {
            $this->bancoDados->rollBack();

            Resposta::response(false, "Erro ao tentar-se realizar login.");
        }

    }

}