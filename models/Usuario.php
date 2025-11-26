<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id","nombre", "apellido", "email", "password", "confirmado", "token" ];

    protected static $alertas = [];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $password2;
    public $confirmado;
    public $token;

    public function __construct($args = []){
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->apellido = $args["apellido"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->password2 = $args["password2"] ?? "";
        $this->confirmado = $args["confirmado"] ?? 0;
        $this->token = $args["token"] ?? "";
    }

    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas["error"][] = "El nombre del usuario es obligatorio";
        }

        if(!$this->apellido){
            self::$alertas["error"][] = "El apellido del usuario es obligatorio";
        }

        if(!$this->email){
            self::$alertas["error"][] = "El email del usuario es obligatorio";
        }

        if(!$this->password){
            self::$alertas["error"][] = "El password del usuario es obligatorio";
        }

        if(strlen($this->password) < 8){
            self::$alertas["error"][] = "El password del usuario debe tener al menos 8 carácteres";
        }

        if($this->password !== $this->password2){
            self::$alertas["error"][] = "Los passwords no coinciden";
        }

        return self::$alertas;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = bin2hex(random_bytes(16));
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas["error"][] = "El email del usuario es obligatorio";
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas["error"][] = "El email no es válido";
        }

        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas["error"][] = "El password del usuario es obligatorio";
        }

        if(strlen($this->password) < 8){
            self::$alertas["error"][] = "El password del usuario debe tener al menos 8 carácteres";
        }

        if($this->password !== $this->password2){
            self::$alertas["error"][] = "Los passwords no coinciden";
        }

        return self::$alertas;
    }

}
