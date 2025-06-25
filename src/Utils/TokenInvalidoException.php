<?php

namespace Utils;

use Exception;

// exception que representa um erro no caso do usuário não estar autenticado
class TokenInvalidoException extends Exception {

    public function __construct()
    {
        parent::__construct();
    }

}