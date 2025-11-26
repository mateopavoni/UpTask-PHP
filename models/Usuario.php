<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id","nombre", "apellido", "email", "password", "confirmado", "token" ];

    

    public function __construct($args = []){
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->apellido = $args["apellido"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->confirmado = $args["confirmado"] ?? "";
        $this->token = $args["token"] ?? "";
    }
}