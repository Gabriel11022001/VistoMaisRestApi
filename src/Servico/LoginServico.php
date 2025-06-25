<?php

namespace Servico;

use DateTime;
use Exception;
use LDAP\Result;
use Models\Token;
use Repositorio\TokenRepositorio;
use Repositorio\UsuarioRepositorio;
use Utils\Resposta;
use Utils\TokenInvalidoException;

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

            if (empty($login)) {
                $errosCampos["login"] = "Informe o login.";
            } else if (strlen($login) < 6) {
                $errosCampos["login"] = "O login deve possuir no mínimo 6 caracteres.";
            }

            if (empty($senha)) {
                $errosCampos["senha"] = "Informe a senha.";
            } else if (strlen($senha) < 6) {
                $errosCampos["senha"] = "A senha deve possuir no mínimo 6 caracteres.";
            }

            if (!empty($errosCampos)) {
                Resposta::response(false, "Erros nos campos.", $errosCampos);
            }

            $usuarioRepositorio = new UsuarioRepositorio($this->bancoDados);

            $usuario = $usuarioRepositorio->buscarUsuarioPeloLoginSenha($login, md5($senha));

            if (empty($usuario)) {
                Resposta::response(false, "Login ou senha inválidos.");
            }

            // deletar todos os tokens anteriores do usuário
            $tokenRepositorio = new TokenRepositorio($this->bancoDados);

            $tokenRepositorio->deletarTodosTokensUsuario($usuario->usuarioId);

            // gerar um novo token para o usuário
            $tokenCadastrar = new Token();
            $dataAtual = new DateTime("now");
            $tokenCadastrar->dataCadastro = $dataAtual->format("Y-m-d H:i:s");
            $tokenCadastrar->token = md5($tokenCadastrar->dataCadastro . "" . $usuario->usuarioId . $usuario->login);
            $tokenCadastrar->dataLimite = $dataAtual->modify("+30 minutes")->format("Y-m-d H:i:s");
            $tokenCadastrar->usuarioId = $usuario->usuarioId;

            $tokenRepositorio->registrarTokenUsuario($tokenCadastrar);

            $usuario->token = $tokenCadastrar;
            
            $this->bancoDados->commit();

            Resposta::response(true, "Login efetuado com sucesso.", [
                "id_usuario" => $usuario->usuarioId,
                "nome_completo" => $usuario->nomeCompleto,
                "token" => [
                    "valor_token" => $tokenCadastrar->token,
                    "data_cadastro" => $tokenCadastrar->dataCadastro,
                    "data_limite_token" => $tokenCadastrar->dataLimite
                ]
            ]);
        } catch (Exception $e) {
            $this->bancoDados->rollBack();

            Resposta::response(false, "Erro ao tentar-se realizar login." . $e->getMessage());
        }

    }

    // realizar logout do usuário
    public function logout() {

        try {
            $authServico = new AuthServico();

            $authServico->validar();

            $idUsuario = getParametro("id_usuario");
            $token = getParametro("token");

            if (empty($idUsuario)) {
                Resposta::response(false, "Informe o id_usuario.");
            }

            if (empty($token)) {
                Resposta::response(false, "Informe o token.");
            }

            $usuarioRepositorio = new UsuarioRepositorio($this->bancoDados);
            $tokenRepositorio = new TokenRepositorio($this->bancoDados);
            
            if (empty($usuarioRepositorio->buscarUsuarioPeloId($idUsuario))) {
                Resposta::response(false, "Não existe um usuário cadastrado com esse id na base de dados.");
            }

            $tokenRepositorio->deletarTodosTokensUsuario($idUsuario);

            Resposta::response(true, "Logout efetuado com sucesso.");
        } catch (TokenInvalidoException $e) {
            Resposta::response(false, "Você não está autenticado.");
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se realizar logout.");
        }

    }

}