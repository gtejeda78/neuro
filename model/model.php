<?php

#EXTENSIÓN DE CLASES: Los objetos pueden ser extendidos, y pueden heredar propiedades y métodos. Para definir una clase como extensión, debo definir una clase padre, y se utiliza dentro de una clase hija.

require_once "conexion.php";

class Modelo extends Conexion{

	
	static public function mdlMostrarExamenes($tabla, $item, $valor,$item2,$valor2){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND $item2 = :$item2 ");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt-> close();

		$stmt = null;


	}

static public function mdlMostrarPreguntas($idUsuario, $idExamen){


		$stmt = Conexion::conectar()->prepare("SELECT id_pregunta,
       				id_examen,
       				pregunta,
       				id_tipo_pregunta,
       				case when p.id_imagen is null then 0
       				else p.id_imagen end id_imagen,
       				ruta
			   FROM pregunta p LEFT  JOIN pregunta_imagen pi
				 on p.id_imagen = pi.id_imagen
			  where p.id_examen= '".$idExamen."'
		   order by id_pregunta");
		
		$stmt -> execute();
		return $stmt -> fetchAll();

		$stmt-> close();
		$stmt = null;
}
static public function mdlMostrarRespuestas($idUsuario, $idExamen){


		$stmt = Conexion::conectar()->prepare("
						SELECT e.id_pregunta,
						       p.pregunta,
						       e.id_usuario,
						       e.id_examen,
						       devuelveOpciones(e.id_pregunta) opciones,
						       devuelveRespuesta(e.id_pregunta, e.id_usuario, e.id_examen) respuesta,
						       devuelveCorrecto(e.id_pregunta, e.id_usuario, e.id_examen) correcto
						FROM examen_calificacion_usuario e, pregunta p
						WHERE e.id_pregunta = p.id_pregunta
						GROUP BY e.id_pregunta, p.pregunta
						ORDER BY e.id_pregunta;");
		
		$stmt -> execute();
		return $stmt -> fetch(PDO::FETCH_ASSOC);

		$stmt-> close();
		$stmt = null;
}
static public function mdlActualizarAlumno($idAlumno,$idExamen){


		$stmt = Conexion::conectar()->prepare("UPDATE alumnos SET examen=1, idExamen=:idExamen WHERE id=:id");

				$stmt->bindParam(":id", $idAlumno, PDO::PARAM_STR);

				$stmt->bindParam(":idExamen", $idExamen, PDO::PARAM_STR);


		if($stmt->execute()){

			return "ok";
			echo 'ok';

		}else{

			return "error";
		echo 'error';
		}

		#$stmt->close();
		#$stmt = null;
}

static public function mdlBorraResultado($idExamen,$idAlumno){

$stmt = Conexion::conectar()->prepare("delete from examen_respuesta_usuario
					WHERE id_examen = :idExamen 
  						AND id_usuario = :idAlumno");

		$stmt->bindParam(":idExamen", $idExamen, PDO::PARAM_STR);	
		$stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
		
		if($stmt->execute()){

			return "ok";
			echo 'ok';

		}else{

			return "error";
		echo 'error';
		}

}

static public function mdlFinalizaresultado($idExamen,$idAlumno){

$stmt = Conexion::conectar()->prepare("call reportaCalificacion(:idAlumno,:idExamen)");

		$stmt->bindParam(":idExamen", $idExamen, PDO::PARAM_STR);	
		$stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
		
		if($stmt->execute()){

			return "ok";
			echo 'ok';

		}else{

			return "error";
		echo 'error';
		}

}
static public function mdlRegistraExamen($id_usuario,$id_examen){

		$stmt = Conexion::conectar()->prepare("INSERT INTO examen_usuario(examen_usuario_id,
			id_usuario, 
			id_examen, 
			concluido) 
			VALUES (null,
			        :id_usuario,
			        :id_examen,
			        0)");

		$stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_STR);	
		$stmt->bindParam(":id_examen", $id_examen, PDO::PARAM_STR);

		if($stmt->execute()){

			return "1";
			echo 'ok';

		}else{

			return "error";
		echo 'error';
		}

		#$stmt->close();
		#$stmt = null;
}
static public function mdlRegistraUsuario($username,$pass,$nombre,$a_paterno,$a_materno,$matricula){

		$stmt = Conexion::conectar()->prepare("INSERT INTO usuario(id_usuario,
			username, 
			contraseña, 
			nombre, 
			a_paterno, 
			a_materno, 
			matricula) 
			VALUES (null,
			        :username,
			        md5(:pass),
			        :nombre,
			        :a_paterno,
			        :a_materno,
			        :matricula)");

		$stmt->bindParam(":username", $username, PDO::PARAM_STR);	
		$stmt->bindParam(":pass", $pass, PDO::PARAM_STR);
		$stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
		$stmt->bindParam(":a_paterno", $a_paterno, PDO::PARAM_STR);
		$stmt->bindParam(":a_materno", $a_materno, PDO::PARAM_STR);
		$stmt->bindParam(":matricula", $matricula, PDO::PARAM_STR);

		if($stmt->execute()){

			return "1";
			echo 'ok';

		}else{

			return "error";
		echo 'error';
		}

		#$stmt->close();
		#$stmt = null;
}

static public function mdlIngresarResultadoOpciones($idExamen,$idAlumno,$idPregunta,$respuesta){


		$stmt = Conexion::conectar()->prepare("INSERT INTO examen_respuesta_usuario(
			examen_respuesta_usuario_id, 
			id_examen, 
			id_usuario, 
			id_pregunta_opciones, 
			respuesta, 
			fecha_registro) 
			VALUES (null,
			        :idExamen,
			        :idAlumno,
			        :idPregunta,
			        :respuesta,
			       now())");

		$stmt->bindParam(":idExamen", $idExamen, PDO::PARAM_STR);	
		$stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
		$stmt->bindParam(":idPregunta", $idPregunta, PDO::PARAM_STR);
		$stmt->bindParam(":respuesta", $respuesta, PDO::PARAM_STR);

		if($stmt->execute()){

			return "1";
			echo 'ok';

		}else{

			return "error";
		echo 'error';
		}

		#$stmt->close();
		#$stmt = null;
}
static public function mdlIngresarResultado($idExamen,$idAlumno,$idPregunta,$respuesta){


		$stmt = Conexion::conectar()->prepare("INSERT INTO examen_respuesta_usuario(
			examen_respuesta_usuario_id, 
			id_examen, 
			id_usuario, 
			id_pregunta_opciones, 
			respuesta, 
			fecha_registro) 
			VALUES (null,
			        :idExamen,
			        :idAlumno,
			        :idPregunta,
			        :respuesta,
			       now())");

		$stmt->bindParam(":idExamen", $idExamen, PDO::PARAM_STR);	
		$stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
		$stmt->bindParam(":idPregunta", $idPregunta, PDO::PARAM_STR);
		$stmt->bindParam(":respuesta", $respuesta, PDO::PARAM_STR);

		if($stmt->execute()){

			return "1";
			echo 'ok';

		}else{

			return "error";
		echo 'error';
		}

		#$stmt->close();
		#$stmt = null;
}


static public function mdlIngresarResultadoA($tabla, $dato,$idAlumno,$idExamen,$idPregunta,$tipo){



		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(idAlumno,idExamen,idPregunta,tipo,respuestaAlumno) VALUES (:idAlumno,:idExamen,:idPregunta,:tipo,:respuestaAlumno)");

		
		$stmt->bindParam(":respuestaAlumno", $dato, PDO::PARAM_STR);
	
		$stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
		$stmt->bindParam(":idExamen", $idExamen, PDO::PARAM_STR);
		$stmt->bindParam(":idPregunta", $idPregunta, PDO::PARAM_STR);
		$stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);


		
		

		if($stmt->execute()){

			return "ok";
		

		}else{

			return "error";
	
		}

		#$stmt->close();
		#$stmt = null;





	}

static public function MdlObtenAlumnos(){
		
			$stmt = Conexion::conectar()->prepare("SELECT id_usuario,username, 
				initcap(concat(a_paterno,' ',a_materno,' ',nombre)) completo from usuario
                where id_usuario>1;");

			$stmt -> execute();
			return $stmt -> fetchAll();
		
}
static public function MdlObtenExa(){
		
			$stmt = Conexion::conectar()->prepare("SELECT id_examen,examen from examenes where activo=1
				order by id_examen;");

			$stmt -> execute();
			return $stmt -> fetchAll();
		
}
	static public function mdlMostrarAlumnos($usuario){
		
			$stmt = Conexion::conectar()->prepare("SELECT e.id_examen,e.examen
								FROM examen_usuario eu, 
     								 examenes e
								WHERE eu.id_usuario = $usuario
      							  AND eu.concluido = 0
      							  AND eu.id_examen = e.id_examen
      							  AND e.activo = 1");

			$stmt -> execute();
			return $stmt -> fetchAll();
		
}


	static public function mdlValidaAlumnos($tabla, $usuario,$pass){

		$stmt = Conexion::conectar()->prepare("SELECT case when count(*)=0 then '0' else '1' end existe,
                                                   id_usuario ,username,nombre FROM $tabla 
                                                   WHERE username = '$usuario' 
                                                    and contraseña = md5('$pass')");

			$stmt -> execute();
			return $stmt -> fetch();

     }
}












