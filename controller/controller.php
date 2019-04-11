<?php

class Controlador{



public function ctrIngresoAdministrador(){

		if(isset($_POST["usuario"])){
			   		
				$tabla = "usuario";
				$item = "username";
				$usuario = $_POST["usuario"];
				$pass = $_POST["pass"];

				$respuesta = Modelo::mdlValidaAlumnos($tabla, $usuario,$pass);

	   if($respuesta["existe"] ='1'){

					$_SESSION["id"] = $respuesta["id_usuario"];
					$_SESSION["usuario"] = $respuesta["username"];
					$_SESSION["nombre"] = $respuesta["nombre"];

if($_SESSION["id"]<2){
    	echo '<script>window.location = "menu.php";</script>';
		echo '<br><div class="alert alert-danger">si ingresa</div>';

}else{
		echo '<script>window.location = "gestionPlanes.php";</script>';
		echo '<br><div class="alert alert-danger">si ingresa</div>';

}
	
					}
	else{
		echo '<br><div class="alert alert-danger">Error al ingresar vuelva a intentarlo</div>';
			}
			       }
}

	
static public function ctrMostrarExamenes(){
	
	
		$_SESSION["examenes"] = $_POST["examenes1"];
	if(isset($_POST["examenes1"])){		

		echo '<script>window.location = "revisarexamen.php";</script>';
		echo '<br><div class="alert alert-danger">si ingresa</div>';
	}
}

static public function ctrContestarExamenes(){
	$sal=0;
	$respuesta =Modelo::mdlBorraResultado($_SESSION["examenes"],$_SESSION["id"]);	

	for ($i = $_SESSION["examenminimo"]; $i <= $_SESSION["examenmaximo"]; $i++) {		
       if(isset($_POST[$i])){
       	$sal=1;
       	$respuesta =Modelo::mdlIngresarResultado($_SESSION["examenes"],$_SESSION["id"],$i,$_POST[$i]);
        }           
       }

    if($sal>0){
    for ($i = 0; $i <= $_SESSION["examenmaximo"]; $i++) {		
       if(isset($_POST["OP".$i])){       	
       	$respuesta =Modelo::mdlIngresarResultadoOpciones($_SESSION["examenes"],$_SESSION["id"],$_POST["OP".$i],$_POST["OP".$i]);
        }           
       }
    	$respuesta=Modelo::mdlFinalizaresultado($_SESSION["examenes"],$_SESSION["id"]);
        echo '<script>window.location = "resultadoExamen.php";</script>';
		echo '<br><div class="alert alert-danger">si ingresa</div>';
       }    	
}

static public function ctrRegistraUsuario(){

if(isset($_POST["email"])){


$respuesta = Modelo::mdlRegistraUsuario($_POST["email"],$_POST["phone"],$_POST["nombre"],$_POST["aPaterno"],$_POST["aMaterno"],$_POST["phone"]);
	
		return $respuesta;	
		echo $respuesta;
  }	
//  este es del otro formulario

  if(isset($_POST["cbxexamen"])){

$respuesta = Modelo::mdlRegistraExamen($_POST["cbxalumno"],
	         $_POST["cbxexamen"]);
	
		return $respuesta;	
		echo $respuesta;
 }

 if(isset($_POST["cbxmexamen"])){

   $valor=$_POST["cbxmalumno"];
   $valor1=$_POST["cbxmexamen"];

	 $_SESSION["exusuario"] = $valor;   
	 $_SESSION["exexamen"] = $valor1;

        echo '<script>window.location = "mostrarCalificaciones1.php";</script>';
		echo '<br><div class="alert alert-danger">si ingresa</div>';
		
 		}  

}
static public function ctrprueba(){

 
}

static public function ctrMostrarRespuestas($idUsuario,$idExamen){
	
		$respuesta = Modelo::mdlMostrarPreguntas($idUsuario,$idExamen);
		return $respuesta;
	
}
static public function ctrMostrarPreguntas($idUsuario,$idExamen){
	
		$respuesta = Modelo::mdlMostrarPreguntas($idUsuario,$idExamen);
		return $respuesta;
	
}
static public function ctrObtenAlumnos(){

		$respuesta = Modelo::MdlObtenAlumnos();
		return $respuesta;
}
static public function ctrObtenExa(){

		$respuesta = Modelo::MdlObtenExa();
		return $respuesta;
}
static public function ctrMostrarAlumnos($idUsuario){

		$respuesta = Modelo::MdlMostrarAlumnos($idUsuario);
		return $respuesta;
}

static public function ctrActualizarAlumno($idAlumno,$idExamen){

		$tabla = "alumnos";
		$respuesta = Modelo::MdlActualizarAlumno($idAlumno,$idExamen);
		return $respuesta;
}

static public function ctrCrearResultadoM($idAlumno,$idExamen,$idPregunta,$tipo,$dato){

				$tabla = "resultados";
				$respuesta = Modelo::mdlIngresarResultadoM($tabla, $dato,$idAlumno,$idExamen,$idPregunta,$tipo);


				if($respuesta == "ok"){

				
				
			}

			else{
			
			}


		
}






	static public function ctrCrearResultadoA($idAlumno,$idExamen,$idPregunta,$tipo,$dato){

				

				
				$tabla = "resultados";
			
				$respuesta = Modelo::mdlIngresarResultadoA($tabla, $dato,$idAlumno,$idExamen,$idPregunta,$tipo);


				if($respuesta == "ok"){

				echo '<br>
					
<div class="alert alert-success">Respuestas enviadas con éxito</div>';

session_destroy();	

echo '<script>
	
	window.location= "login.php";

</script>';


				}

			else{
				echo 'no entra';
			}


		}

	}











