<?php

namespace Servico;

use DateTime;
use Exception;
use Repositorio\TokenRepositorio;
use Utils\TokenInvalidoException;

class AuthServico extends ServicoBase {

    public function __construct()
    {
        parent::__construct();
    }

    // validar autenticação se o usuário está logado ou não no sistema
    public function validar() {

        try {
            $cabecalhos = getallheaders();

            if (!isset($cabecalhos["Authorization"])) {

                throw new TokenInvalidoException();
            }

            if (empty($cabecalhos["Authorization"])) {

                throw new TokenInvalidoException();
            }

            $tokenRepositorio = new TokenRepositorio($this->bancoDados);
            $token = $tokenRepositorio->buscarTokenUsuario($cabecalhos["Authorization"]);

            if (empty($token)) {

                throw new TokenInvalidoException();
            }

            $dataAtual = new DateTime("now");
            $dataLimite = new DateTime($token["data_limite"]);

            if ($dataAtual > $dataLimite) {

                throw new TokenInvalidoException();
            }

        } catch (Exception $e) {

            throw $e;
        }
        
    }

}