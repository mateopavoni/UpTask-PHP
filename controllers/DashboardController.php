<?php 

namespace Controllers;

use MVC\Router;
use Model\Proyecto;

class DashboardController{

    public static function index(Router $router){

        session_start();

        isAuth();

        $router->render("dashboard/index", [
            "titulo" => "Proyectos"
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

        $router->render("dashboard/perfil", [
            "titulo" => "Perfil"
        ]);
    }
}
