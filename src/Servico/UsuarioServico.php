<?php

namespace Servico;

use Exception;
use Models\Usuario;
use Repositorio\UsuarioRepositorio;
use Utils\Resposta;
use Utils\TokenInvalidoException;

class UsuarioServico extends ServicoBase {

    private $usuarioRepositorio;
    private $authServico;

    public function __construct()
    {
        parent::__construct();

        $this->authServico = new AuthServico();
        $this->usuarioRepositorio = new UsuarioRepositorio($this->bancoDados);
    }

    // cadastrar usuário
    public function cadastrarUsuario() {

        try {   
            $this->authServico->validar();

            $nomeCompleto = getParametro("nome_completo");
            $login = getParametro("login");
            $senha = getParametro("senha");

            $errosCampos = array();

            if (empty($nomeCompleto)) {
                $errosCampos["nome_completo"] = "Informe o nome completo.";
            }

            if (empty($login)) {
                $errosCampos["login"] = "Informe o login.";
            } else if (strlen($login) < 6) {
                $errosCampos["login"] = "O login deve ter no mínimo 6 caracteres.";
            }

            if (empty($senha)) {
                $errosCampos["senha"] = "Informe a senha.";
            } else if (strlen($senha) < 6) {
                $errosCampos["senha"] = "A senha deve possuir no mínimo 6 caracteres.";
            }

            // validar se já existe outro usuário cadastrado com o mesmo login
            if (!empty($this->usuarioRepositorio->buscarUsuarioPeloLogin($login))) {
                Resposta::response(false, "Login indisponível.");
            }

            $usuarioCadastrar = new Usuario();

            $usuarioCadastrar->nomeCompleto = $nomeCompleto;
            $usuarioCadastrar->login = $login;
            $usuarioCadastrar->senha = md5($senha);

            $this->usuarioRepositorio->cadastrarUsuario($usuarioCadastrar);

            Resposta::response(true, "Usuário cadastrado com sucesso.", $usuarioCadastrar);
        } catch (TokenInvalidoException) {
            Resposta::response(false, "Você não está autenticado.");
        } catch (Exception $e) {
            Resposta::response(false, "Erro ao tentar-se cadastrar o usuário.");
        }

    }

}