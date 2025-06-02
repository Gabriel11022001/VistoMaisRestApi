<?php

namespace Repositorio;

interface ICategoriaVeiculoRepositorio {

    function cadastrar($categoriaVeiculoCadastrar);

    function editar($categoriaVeiculoEditar);

    function deletar($categoriaVeiculoId);

    function buscarPeloId($categoriaVeiculoId);

    function listarTodos();

    function buscarPeloNome($nomeCategoria);

}