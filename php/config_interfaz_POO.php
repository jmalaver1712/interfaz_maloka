<?php 

define('ID_CURSO',$_GET['id']);
// CLASS
class interfaz extends Conexion{

	public function __construct(){
		parent::__construct();
	}

    // DATOS DEL USUARIO LOGIN
	public function data_user(){
		$data = json_decode(json_encode($_SESSION['USER']),true);

		$qr = "SELECT startdate, from_unixtime(startdate) as fecha FROM mdl_course  
		WHERE id = '".ID_CURSO."' LIMIT 1";

		$result = $this->_db->query($qr);
		$login = $result->fetch_all(MYSQLI_ASSOC);
		$startdate = 0;
		if(count($login) > 0){
			$fecha = $login['0']['fecha'];
			$startdate = $login['0']['startdate'];
		}

		$usuario = array(
			"id" => $data['id'],
			"nombre" => $data['firstname'],
			"apellido" => $data['lastname'],
			"codigo_estudiante" => $data['idnumber'],
			"fecha" => $fecha,
			"startdate" => $startdate,
		);


		return $usuario;
	}

	// SECCIONES
	public function secciones(){
		$data = array();
		$qr = "SELECT id, sequence, name FROM ".DB_PRE."course_sections WHERE course = '".ID_CURSO."' ORDER BY id ASC";

		$result = $this->_db->query($qr);
		if (!$result) {
			printf("Error en Secciones: %s\n", $this->_db->error);
		}
		$secciones = $result->fetch_all(MYSQLI_ASSOC);
		$aux = 0;
		foreach ($secciones as $row){
			$seccion = $row['id'];
			$secuencia = $row['sequence'];
			$separar = explode(',',$secuencia);
			$data[$aux]['nombre'] = ($row['name']);
			// Si tiene contenido
			if(strlen($secuencia) > 0){
				$data[$aux]['id'] = $row['id'];
				$data[$aux]['contenidos'] = $separar;
				$data[$aux]['seccion'] = $seccion;
				$aux++;
			}

		}
		return $data;
	}

	// CONTENIDO DEL CURSO
	public function all_contenido(){
		$origen = $this->secciones();
		$aux = 0;
		$data = array();
		foreach ($origen as $row){
			$ids = $row['contenidos'];
			$seccion = $row['id'];
			$name_section = $row['nombre'];
			foreach ($ids as $id){
				
				$qr = "SELECT a.instance, a.module, b.name, a.id, a.section, '".$name_section."' as name_section FROM ".DB_PRE."course_modules a , ".DB_PRE."modules b WHERE a.deletioninprogress = 0 AND a.id = '".$id."' AND a.section = '".$seccion."' AND a.course = '".ID_CURSO."' AND a.module = b.id AND a.visible = '1' ORDER BY instance";

				$result = $this->_db->query($qr);

				if (!$result) {
					printf("Error en Contenido: %s\n", $this->_db->error);
				}
				$recursos = $result->fetch_all(MYSQLI_ASSOC);
				$data[$aux] = $recursos; 
				$aux++;
			}
		}
		return $data;
	}

	// CONSULTA DE ENLACES
	public function con_enlaces(){
		$user = $this->data_user();

		$origen = $this->all_contenido();
		$aux = 0;
		$data = array();
		foreach ($origen as $row) {
			foreach ($row as $value) {
				if($value['name'] == 'url'){

					$seccion = $value['section'];
					
					$result = $this->_db->query("select name, externalurl, intro from ".DB_PRE."url where id = ".$value['instance']." and course = '".ID_CURSO."' order by name");

					if (!$result) {
						printf("Error en Enlaces: %s\n", $this->_db->error);
					}
					$urls = $result->fetch_all(MYSQLI_ASSOC);

					foreach ($urls as $url){
						$enlace = $url['externalurl'];
						$nombre = ($url['name']);

						$tipo = "enlace";
						$ident = $enlace;

						$data[$aux]['id'] = $value['id'];
						$data[$aux]['module'] = $value['module'];
						$data[$aux]['seccion'] = $seccion;
						$data[$aux]['tipo'] = $tipo;
						$data[$aux]['enlace'] = $ident;
						$data[$aux]['nombre'] = ($nombre);
						$aux++;
					}
				}
			}
		}
		return $data;
		//print_r($data);
	}

	// CONSULTA DE DOCUMENTOS
	public function con_documentos(){
		$origen = $this->all_contenido();
		$aux = 0;
		$data = array();
		$tipo = "documento";
		foreach ($origen as $row) {
			foreach ($row as $value) {
				if($value['name'] == 'resource'){
				$seccion = $value['section'];
				$id = $value['id'];
				$result = $this->_db->query("select name from ".DB_PRE."resource where id = ".$value['instance']." and course= ".ID_CURSO);
				if (!$result) {
					printf("Error en Documentos: %s\n", $this->_db->error);
				}
				$documentos = $result->fetch_all(MYSQLI_ASSOC);
				//print_r($documentos);

					foreach ($documentos as $documento) {
						$enlace = "../mod/resource/view.php?id=".$id;
						$nombre = ($documento['name']);
						//if(is_numeric($indice)){
							$data[$aux]['id'] = $value['id'];
							$data[$aux]['module'] = $value['module'];
							$data[$aux]['seccion'] = $seccion;
							$data[$aux]['tipo'] = $tipo;
							$data[$aux]['enlace'] = $enlace;
							$data[$aux]['nombre'] = ($nombre);
							$aux++;
						//}
					}
				}
			}
		}
		return $data;
	}

	
	public function con_chat(){
		$origen = $this->all_contenido();
		$aux = 0;
		$data = array();
		$tipo = "chat";
		foreach ($origen as $row) {
			foreach ($row as $value) {
				if($value['name'] == 'chat'){
				$seccion = $value['section'];
				$id = $value['id'];
				$result = $this->_db->query("select name from ".DB_PRE."chat where id = ".$value['instance']." and course= ".ID_CURSO);
				if (!$result) {
					printf("Error en Chat: %s\n", $this->_db->error);
				}
				$documentos = $result->fetch_all(MYSQLI_ASSOC);
				//print_r($documentos);

					foreach ($documentos as $documento) {
						$enlace = "../mod/chat/view.php?id=".$id;
						$nombre = ($documento['name']);
						//if(is_numeric($indice)){
							$data[$aux]['id'] = $value['id'];
							$data[$aux]['module'] = $value['module'];
							$data[$aux]['seccion'] = $seccion;
							$data[$aux]['tipo'] = $tipo;
							$data[$aux]['enlace'] = $enlace;
							$data[$aux]['nombre'] = ($nombre);
							$aux++;
						//}
					}
				}
			}
		}
		return $data;
	}



	public function con_forum(){
		$origen = $this->all_contenido();
		$aux = 0;
		$data = array();
		$tipo = "forum";
		foreach ($origen as $row) {
			foreach ($row as $value) {
				if($value['name'] == 'forum'){
				$seccion = $value['section'];
				$id = $value['id'];
				$result = $this->_db->query("select name from ".DB_PRE."forum where id = ".$value['instance']." and course= ".ID_CURSO);
				if (!$result) {
					printf("Error en forum: %s\n", $this->_db->error);
				}
				$documentos = $result->fetch_all(MYSQLI_ASSOC);
				//print_r($documentos);

					foreach ($documentos as $documento) {
						$enlace = "../mod/forum/view.php?id=".$id;
						$nombre = ($documento['name']);
						//if(is_numeric($indice)){
							$data[$aux]['id'] = $value['id'];
							$data[$aux]['module'] = $value['module'];
							$data[$aux]['seccion'] = $seccion;
							$data[$aux]['tipo'] = $tipo;
							$data[$aux]['enlace'] = $enlace;
							$data[$aux]['nombre'] = ($nombre);
							$aux++;
						//}
					}
				}
			}
		}
		return $data;
	}


	// ACTIVIDADES VALORATIVAS
	/*

	public function actividades(){
		$data_user = $this->datos_usuario();
		$id_user_log = $data_user['id'];

		$origen = $this->all_contenido();
		$aux = 0;
		$data = array();

		foreach ($origen as $row) {
			foreach ($row as $value) {
				$con_actividad = "";
				$tipo = "";
				//Entrega
				if($value['module'] == 1){
					$tipo = "assign";
					$con_actividad  = "
					SELECT a.id AS Id, a.name AS Nombre, a.allowsubmissionsfromdate AS Inicio, a.duedate AS Final, a.grade AS Nota,b.finalgrade AS Nota_final
					FROM ".DB_PRE."assign a, ".DB_PRE."grade_items c
					LEFT JOIN ".DB_PRE."grade_grades b ON c.id = b.itemid AND b.userid = '$id_user_log'
					WHERE c.iteminstance = a.id
					AND c.itemmodule = '$tipo'
					AND a.id = ".$value['instance']."
					AND a.course = ".ID_CURSO;
				}

				//Foros
				if($value['module'] == 9){
					$tipo = "forum";
					$con_actividad  = "
					SELECT a.id AS Id, a.name AS Nombre, a.assesstimestart AS Inicio, a.assesstimefinish AS Final, a.scale AS Nota, b.finalgrade AS Nota_final
					FROM ".DB_PRE."forum a, ".DB_PRE."grade_items c
					LEFT JOIN ".DB_PRE."grade_grades b ON c.id = b.itemid AND b.userid = '$id_user_log'
					WHERE c.iteminstance = a.id
					AND c.itemmodule = '$tipo'
					AND a.id = ".$value['instance']."
					AND a.course = ".ID_CURSO;
				}

				//Quiz
				if($value['module'] == 16){
					$tipo = "quiz";
					$con_actividad  = "
					SELECT a.id AS Id, a.name AS Nombre, a.timeopen AS Inicio, a.timeclose AS Final, a.grade AS Nota,b.finalgrade AS Nota_final
					FROM ".DB_PRE."quiz a, ".DB_PRE."grade_items c
					LEFT JOIN ".DB_PRE."grade_grades b ON c.id = b.itemid AND b.userid = '$id_user_log'
					WHERE c.iteminstance = a.id
					AND c.itemmodule = '$tipo'
					AND a.id = ".$value['instance']."
					AND a.course = ".ID_CURSO;
				}

				if($tipo != ""){
					$result = $this->_db->query($con_actividad);
					if (!$result) {
						printf("Error en Actividades: %s\n", $this->_db->error);
					}
					$nowtime = time();

					$actividad = $result->fetch_all(MYSQLI_ASSOC);
					foreach ($actividad as $val) {

						if ($val['Inicio'] <= $nowtime && $val['Final'] >= $nowtime){
							$tablatr = "tablatr";
						} else {
							$tablatr = "";
						}

						// ORGANIZAR POR FECHA 
						$data[$aux]['id'] = $value['id'];
						$data[$aux]['nombre'] = ($val['Nombre']);
						$data[$aux]['f_inicio'] = $val['Inicio'];
						$data[$aux]['f_final'] = $val['Final'];
						$data[$aux]['nota'] = round($val['Nota'],0);
						$data[$aux]['nota_final'] = round($val['Nota_final'],3);
						$data[$aux]['enlace'] = "../mod/$tipo/view.php?id=".$value['id'];
						$data[$aux]['tipo'] = $tipo;
						$data[$aux]['clase'] = $tablatr;
						$aux++;
					}
				}
			}
		}
		return $data;
	}

	*/


}

$interfaz = new interfaz();
?>
