<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email;

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

            if(empty($alertas)){
                $existeUsuario = Usuario::where("email" , $usuario->email);

                if($existeUsuario){
                    Usuario::setAlerta("error", "El Usuario ya esta registrado");
                } else {
                    $usuario -> hashPassword();

                    unset($usuario->password2);

                    $usuario -> crearToken();

                    $resultado = $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    if($resultado){
                        header("Location: /mensaje");
                    }
                }
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

        $token = trim($_GET["token"]);
        $token = s($token); 


        if(!$token) {
            header("Location: /");
            exit;
        }

        $usuario = Usuario::where("token", trim($token));
        
        if(!$usuario){
            Usuario::setAlerta("error", "Token no válido o ya utilizado");
        } else {
            Usuario::setAlerta("success", "Tu cuenta ha sido confirmada");
            $usuario->token = null;
            $usuario->confirmado = 1;
            $usuario->guardar();


        }

        $alertas = Usuario::getAlertas();

        $router->render("auth/confirmar", [
            "titulo" => "Confirmar cuenta",
            "alertas"=> $alertas
        ]);
    }

}