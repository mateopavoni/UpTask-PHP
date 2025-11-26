<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){

        if($_SERVER["REQUEST_METHOD"] === "POST"){

        }

        $router->render("auth/login", [
            "titulo" => "Iniciar Sesión"
        ]);
    }

    public static function logout(){

    }

    public static function crear(Router $router){

        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $usuario->sincronizar($_POST);

            $alertas= $usuario->validarNuevaCuenta();

            $existeUsuario = Usuario::where("email" , $usuario->email);

            if($existeUsuario){
                Usuario::setAlerta("error", "El Usuario ya esta registrado");
            }

        }

        $router->render("auth/crear", [
            "titulo" => "Crear cuenta",
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }

    public static function olvide(Router $router){

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }

        $router->render("auth/olvide", [
            "titulo" => "Olvidé mi contraseña"
        ]);
    }

    public static function reestablecer(Router $router){

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }
        $router->render("auth/reestablecer", [
            "titulo" => "Reestablecer contraseña"
        ]);
    }

    public static function mensaje(Router $router){
        $router->render("auth/mensaje", [
            "titulo" => "Mensaje de confirmación"
        ]);
    }

    public static function confirmar(Router $router){
        $router->render("auth/confirmar", [
            "titulo" => "Confirmar cuenta"
        ]);
    }
}