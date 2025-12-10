<?php 

namespace Controllers;

use MVC\Router;
use Model\Proyecto;
use Model\Usuario;

class DashboardController{

    public static function index(Router $router){

        session_start();

        isAuth();

        $proyectos = Proyecto::whereALL("propietarioId", $_SESSION["id"]);
    

        $router->render("dashboard/index", [
            "titulo" => "Proyectos",
            "proyectos" => $proyectos
        ]);
    }

    public static function crear(Router $router){
        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $proyecto = new Proyecto($_POST);
            
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                $proyecto->propietarioId = $_SESSION["id"];

                $proyecto->guardar();

                header("Location: /proyecto?url=" . $proyecto->url);
            }
        }

        $router->render("dashboard/crear-proyecto", [
            "titulo" => "Crear Proyecto",
            "alertas" => $alertas
        ]);
    }

    public static function perfil(Router $router){
        session_start();
        isAuth();

        $alertas = [];
        $usuario = Usuario::find($_SESSION["id"]);

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPerfil();

            if(empty($alertas)){
                $existeUsuario = Usuario::where("email", $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    Usuario::setAlerta("error", "El email ya pertenece a otra cuenta");
                    $alertas = $usuario->getAlertas();
                } else {
                    $usuario->guardar();
                    Usuario::setAlerta("exito", "Guardado Correctamente");
                    $alertas = $usuario->getAlertas();
                    $_SESSION["nombre"] = $usuario->nombre;
                }
                
            }
        }
        $router->render("dashboard/perfil", [
            "titulo" => "Perfil",
            "alertas" => $alertas,
            "usuario" => $usuario
        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();
        isAuth();

        $alertas = [];
        $usuario = Usuario::find($_SESSION["id"]);

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)){
                $resultado = $usuario->comprobar_password();
                if($resultado){
                    $usuario->password = $usuario->password_nuevo;

                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    unset($usuario->password2);

                    $usuario->hashPassword();

                    $resultado = $usuario->guardar();

                    if($resultado){
                        Usuario::setAlerta("exito", "Password Guardado Correctamente");
                        $alertas = $usuario->getAlertas();
                    }

                } else {
                    Usuario::setAlerta("error", "El password actual es incorrecto");
                    $alertas = $usuario->getAlertas();
                }
            }
            
        }

        $router->render("dashboard/cambiar-password", [
            "titulo" => "Cambiar Password",
            "alertas" => $alertas,
            "usuario" => $usuario 
        ]); 
    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();

        $urlProyecto = $_GET["url"];

        if(!$urlProyecto) header("Location: /dashboard");

        $proyecto = Proyecto::where("url", $urlProyecto);

        if($proyecto->propietarioId !== $_SESSION["id"]){
            header("Location: /dashboard");
        }

        $router->render("dashboard/proyecto", [
            "titulo" => $proyecto->proyecto
        ]);
    }
}
