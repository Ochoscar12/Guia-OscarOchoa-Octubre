<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers: Origin, x-Requested-Widht, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Credentials: true');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');
require('classes/estudiante.class.php');

$Estudiante = new Estudiante();

if($_SERVER["REQUEST_METHOD"] === "GET"){
    //OBTENGO EL VALOR DEL PARAMETRO ENVIADO POR LA URL
    $tipo_peticion = ((isset($_GET["t"])) ? (($_GET["t"])!="" ? $_GET["t"] : null) : null);
    //EVALUO EL VALOR DEL PARAMETRO
    switch($tipo_peticion){
        case"selectAll":
            $resultado = $Estudiante->obtenerEstudiante();
        break;
        case "select":
            $id = ((isset($_GET["id"])) ? (($_GET["id"]!="") ? intval($_GET["id"]) : 0): 0); //OBTENGO EL VALOR DEL PARAMETRO id
            if($id > 0){
                $resultado = $Estudiante->obtenerEstudiante($id);
            }else{
                header('HTTP/1.1 412 Precondition Failed');
                $resultado = array("mensaje"=>"El parametro ID no es correcto","valores"=>"");
            }
        break;
        case "insert":
            //INSERTA UN REGISTRO
            if(array_key_exist("fecha_nac",$_GET) and array_key_exists("id_genero",$_GET)){
                if($_GET["fecha_nac"]!="" and $_GET["id_genero"]!=""){
                    $resultado = $Estudiante->nuevoEstudiante($_GET["fecha_nac"],$_GET["id_genero"]);
                }else{
                    //UNO DE LOS PARAMETROS ENVIADOS NO POSEE VALORES
                    header('HTTP/1.1 400 Bad Request');
                    $resultado = array("mensaje"=>"Verifique el valor de la fecha de nacimiento o del genero", "valores"=>"");
                }
            }else{
                //NO SE HAN ENVIADO VALORES DESDE EL METODO GET
                header('HTTP/1.1 400 Bad Request');
                $resultado = array("mensaje"=>"No se han enviado los parametros requeridos", "valores"=>"");
            }
        break;
        default:
            //NO SE DEFINIO EL TIPO DE PETICION "t"
            header('HTTP/1.1 403 Forbidden');
            $resultado = array("mensaje"=>"Debe indicar el tipo del procesamiento que se realizara","valores"=>"");
        break;
    }
}elseif($_SERVER["REQUEST_METHOD"] === "POST"){
if(array_key_exist("fecha_nac",$_POST) and array_key_exists("id_genero",$_POST)){
                if($_POST["fecha_nac"]!="" and $_POST["id_genero"]!=""){
                    $resultado = $Estudiante->nuevoEstudiante($_POST["fecha_nac"],$_POST["id_genero"]);
                }else{
                    //UNO DE LOS PARAMETROS ENVIADOS NO POSEE VALORES
                    header('HTTP/1.1 400 Bad Request');
                    $resultado = array("mensaje"=>"Verifique el valor de la fecha de nacimiento o del genero", "valores"=>"");
                }
            }else{
                //NO SE HAN ENVIADO VALORES DESDE EL METODO POST
                header('HTTP/1.1 400 Bad Request');
                $resultado = array("mensaje"=>"No se han enviado los parametros requeridos", "valores"=>"");
            }
}else{
    header('HTTP/1.1 400 Bad Request');
    $resultado = array("mensaje"=>"¿?","valores"=>"");
}
header('Content-type: application/json');
echo(json_encode($resultado));
?>