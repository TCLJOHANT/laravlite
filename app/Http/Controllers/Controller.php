<?php 
namespace App\Http\Controllers;
class Controller{
    public function view($route,$data = []){
        extract($data); //destruye el array
        $route = str_replace('.','/',$route); //reemplaza el . por /
        if (file_exists("..resourcces/views/{$route}.php")) {
            ob_start();
            include "../resources/views/{$route}.php";
            $content = ob_get_clean();
            return $content;
        }else{
            return "El archivo no existe";
        }
    }
}