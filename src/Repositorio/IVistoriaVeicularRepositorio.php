<?php

namespace Repositorio;

interface IVistoriaVeicularRepositorio {

    function cadastrarVistoriaVeicular($vistoriaVeicularCadastrar);

    function editarVistoriaVeicular($vistoriaVeicularEditar);

    function buscarVistoriasVeiculares($paginaAtual, $elementosPorPagina);

    function buscarVistoriaVeicularPeloId($idVistoriaVeicular);

    function deletarVistoriaVeicular($idVistoriaVeicular);

}
