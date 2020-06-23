<?php
//error_reporting(0);

$user=$_POST['username'];
$password=$_POST['password'];

$usuario = "root";
$passwd = "";
$servidor = "localhost";
$bd = "esw";

$cad = "";
$mensaje = [
    "status" => "",
    "key" => ""
];
$conexion = mysqli_connect( $servidor, $usuario, $passwd, $bd );

if(!$conexion)
{
    $error = "Error al conectar con MySQL";
    $mensaje["status"]=$error;
}
else
{
    $consulta="SELECT * FROM usuario WHERE user='$user' AND password='$password'";
    $veri_user = mysqli_query( $conexion, $consulta );
    
    if(!$veri_user or $rows_user= mysqli_num_rows($veri_user)!=1)
    {
        $error = "El usuario y/o el password son incorrectos";
        $mensaje["status"]=$error;
    }
    else
    {      
        if($rows_user= mysqli_num_rows($veri_user)==1)
        {
            $fila =mysqli_fetch_array($veri_user);
            $cad=$fila["user"];
            $hoy = getdate();
            $cad.=rand(0,10000000).$hoy['seconds'];

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "http://localhost/claves/acceso.php");
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "clave=$cad&&tipo=agregar");
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
            $resp = curl_exec($curl);
            curl_close($curl);
            $ins = json_decode($resp,true);

            if($ins["status"]=="ok")
            {
                $error = "ok";
                $mensaje["status"]=$error;
                $mensaje["key"]=$cad;
            }
            else
            {
                $error = "Problema al insertar la clave";
                $mensaje["status"]=$error;
            }  
        }
        else
        {
            $error = "Hay mas de un usuario en la base de datos";
            $mensaje["status"]=$error; 
        }      
            
    }
}
    
mysqli_close( $conexion );
echo json_encode($mensaje);



?>
