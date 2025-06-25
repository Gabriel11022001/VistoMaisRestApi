<?php

namespace Repositorio;

use Exception;
use Models\Token;
use Models\Usuario;
use PDO;

class UsuarioRepositorio extends Repositorio implements IUsuarioRepositorio {

    public function __construct(PDO $bancoDados)
    {
        parent::__construct($bancoDados);
    }

    // cadastrar usuário
    public function cadastrarUsuario($usuarioCadastrar) {
        $stmt = $this->bancoDados->prepare("INSERT INTO tb_usuarios(nome_completo, login, senha)
        VALUES(:nome_completo, :login, :senha)");

        $stmt->bindValue(":nome_completo", $usuarioCadastrar->nomeCompleto);
        $stmt->bindValue(":login", $usuarioCadastrar->login);
        $stmt->bindValue(":senha", $usuarioCadastrar->senha);

        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se cadastrar o usuário.");
        }

        $usuarioCadastrar->usuarioId = $this->bancoDados->lastInsertId();
    }

    // editar usuário
    public function editarUsuario($usuarioEditar) {
        $stmt = $this->bancoDados->prepare("UPDATE tb_usuarios SET nome_completo = :nome_completo, login = :login,
        senha = :senha WHERE usuario_id = :usuario_id");

        $stmt->bindValue(":usuario_id", $usuarioEditar->usuarioId);
        $stmt->bindValue(":nome_completo", $usuarioEditar->nomeCompleto);
        $stmt->bindValue(":login", $usuarioEditar->login);
        $stmt->bindValue(":senha", $usuarioEditar->senha);

        if (!$stmt->execute()) {

            throw new Exception("Erro ao tentar-se editar o usuário.");
        }

    }

    public function deletarUsuario($idUsuarioDeletar) {

    }

    public function buscarTodosUsuarios($paginaAtual, $elementosPorPagina) {
        
    }

    // buscar usuário pelo login e senha
    public function buscarUsuarioPeloLoginSenha($login, $senha) {
        $stmt = $this->bancoDados->prepare("SELECT * FROM tb_usuarios 
        WHERE login = :login AND senha = :senha");

        $stmt->bindValue(":login", $login);
        $stmt->bindValue(":senha", $senha);
        $stmt->execute();
        $usuarioObj = $stmt->fetchObject();

        if (empty($usuarioObj)) {

            return null;
        }

        $usuario = new Usuario();

        $usuario->usuarioId = $usuarioObj->usuario_id;
        $usuario->nomeCompleto = $usuarioObj->nome_completo;
        $usuario->login = $usuarioObj->login;
        $usuario->senha = $usuarioObj->senha;

        $token = new Token();
        
        $stmt = $this->bancoDados->prepare("SELECT * FROM tb_tokens WHERE usuario_id = :usuario_id");
        $stmt->bindValue(":usuario_id", $usuario->usuarioId);
        $stmt->execute();
        $tokenObj = $stmt->fetchObject();

        if (empty($tokenObj)) {
            $usuario->token = null;
        } else {
            $token = new Token();
            $token->tokenId = $tokenObj->token_id;
            $token->token = $tokenObj->token;
            $token->dataCadastro = $tokenObj->data_cadastro;
            $token->dataLimite = $tokenObj->data_limite;

            $usuario->token = $token;
        }

        return $usuario;
    }

    // buscar usuário pelo login
    public function buscarUsuarioPeloLogin($login) {
        $stmt = $this->bancoDados->prepare("SELECT * FROM tb_usuarios 
        WHERE login = :login");

        $stmt->bindValue(":login", $login);
        $stmt->execute();
        $usuarioObj = $stmt->fetchObject();

        if (empty($usuarioObj)) {

            return null;
        }

        $usuario = new Usuario();

        $usuario->usuarioId = $usuarioObj->usuario_id;
        $usuario->nomeCompleto = $usuarioObj->nome_completo;
        $usuario->login = $usuarioObj->login;
        $usuario->senha = $usuarioObj->senha;

        $token = new Token();
        
        $stmt = $this->bancoDados->prepare("SELECT * FROM tb_tokens WHERE usuario_id = :usuario_id");
        $stmt->bindValue(":usuario_id", $usuario->usuarioId);
        $stmt->execute();
        $tokenObj = $stmt->fetchObject();

        if (empty($tokenObj)) {
            $usuario->token = null;
        } else {
            $token = new Token();
            $token->tokenId = $tokenObj->token_id;
            $token->token = $tokenObj->token;
            $token->dataCadastro = $tokenObj->data_cadastro;
            $token->dataLimite = $tokenObj->data_limite;

            $usuario->token = $token;
        }

        return $usuario;
    }

    // buscar usuário pelo id
    public function buscarUsuarioPeloId($idUsuario) {
        $stmt = $this->bancoDados->prepare("SELECT * FROM tb_usuarios 
        WHERE usuario_id = :usuario_id");

        $stmt->bindValue(":usuario_id", $idUsuario);
        $stmt->execute();
        $usuarioObj = $stmt->fetchObject();

        if (empty($usuarioObj)) {

            return null;
        }

        $usuario = new Usuario();

        $usuario->usuarioId = $usuarioObj->usuario_id;
        $usuario->nomeCompleto = $usuarioObj->nome_completo;
        $usuario->login = $usuarioObj->login;
        $usuario->senha = $usuarioObj->senha;

        $token = new Token();
        
        $stmt = $this->bancoDados->prepare("SELECT * FROM tb_tokens WHERE usuario_id = :usuario_id");
        $stmt->bindValue(":usuario_id", $usuario->usuarioId);
        $stmt->execute();
        $tokenObj = $stmt->fetchObject();

        if (empty($tokenObj)) {
            $usuario->token = null;
        } else {
            $token = new Token();
            $token->tokenId = $tokenObj->token_id;
            $token->token = $tokenObj->token;
            $token->dataCadastro = $tokenObj->data_cadastro;
            $token->dataLimite = $tokenObj->data_limite;

            $usuario->token = $token;
        }

        return $usuario;
    }

}