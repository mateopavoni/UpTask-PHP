<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginController {
    public static function login(Router $router){

        $alertas = [];
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)){
                $usuario = Usuario::where("email", $usuario->email);
                
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta("error", "El usuario no existe o no esta confirmado");
                } else {
                    if(password_verify($_POST["password"], $usuario->password)){
                        session_start();

                        $_SESSION["id"] = $usuario -> id;
                        $_SESSION["nombre"] = $usuario-> nombre;
                        $_SESSION["apellido"] = $usuario-> apellido;
                        $_SESSION["email"] = $usuario-> email;
                        $_SESSION["login"] = true;

                        header("Location: /proyectos");

                    } else {
                        Usuario::setAlerta("error", "El password es incorrecto");
                    }
                }
            }

            $alertas = Usuario::getAlertas();

        }

        $router->render("auth/login", [
            "titulo" => "Iniciar Sesión",
            "alertas" => $alertas
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
        $alertas = [];
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where("email", $usuario->email);

                if($usuario && $usuario->confirmado === "1"){

                    $usuario->crearToken();
                    unset($usuario->password2);
                    $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarInstrucciones();

                    Usuario::setAlerta("success", "Hemos enviado las instrucciones a tu email");

                } else{
                    Usuario::setAlerta("error", "El usuario no existe o no esta confirmado"); 
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render("auth/olvide", [
            "titulo" => "Olvidé mi contraseña",
            "alertas" => $alertas
        ]);
    }

    public static function reestablecer(Router $router){
        $token = s($_GET["token"]);
        $mostrar = true;

        if(!$token) {
            header("Location: /");
            exit;
        }

        $usuario = Usuario::where("token", trim($token));
    
        
        if(empty($usuario)){
            Usuario::setAlerta("error", "Token no válido o ya utilizado");
            $mostrar = false;
        } 

        $alertas = Usuario::getAlertas();

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $usuario->sincronizar($_POST);

            $usuario->password2 = $_POST['password2'] ?? '';

            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                $usuario->hashPassword();

                unset($usuario->password2);

                $usuario->token = null;

                $resultado = $usuario->guardar();

                if($resultado){
                    header("Location: /");
                }
            }
        }

        $router->render("auth/reestablecer", [
            "titulo" => "Reestablecer contraseña",
            "alertas" => $alertas,
            "mostrar" => $mostrar
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