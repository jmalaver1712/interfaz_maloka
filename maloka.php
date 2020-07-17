<?php 
require_once("php/conexion_POO.php");//conexion BD
require_once("php/config_interfaz_POO.php");//conexion BD

$user = $interfaz->data_user();

$secciones = $interfaz->secciones(); 

// $all_contenido = $interfaz->all_contenido();

$enlaces = $interfaz->con_enlaces();

$documentos = $interfaz->con_documentos();

$forum = $interfaz->con_forum();

$chat = $interfaz->con_chat();


$ordenes = [
    "1" => "0",
    "2" => "4",
    "3" => "6",
    "4" => "18",
    "5" => "9",
    "6" => "10",
    "7" => "12",
    "8" => "19",
    "9" => "2",
    "10" => "14",
    "11" => "1",
    "12" => "16",
    "13" => "15",
    "14" => "17",
    "15" => "8",
    "16" => "5",
    "17" => "7",
    "18" => "3",
    "19" => "11",
    "20" => "13",
];

$semanas = [
    "0" => ["0", "1"],
    "1" => ["0", "1"],
    "2" => "2",
    "3" => ["3", "4"],
];

$fecha_now = date("Y-m-d H:i:s");
$datetime1 = date_create($user['fecha']);
$datetime2 = date_create($fecha_now);

$interval = date_diff($datetime1, $datetime2);
$dias = $interval->format('%a');

$sem = round( $dias/7 ,0);

echo "<pre>";
// print_r($enlaces);
// print_r($ids_seccion);
// print_r($forum);

// print_r($chat);
// print_r($user);
echo "</pre>";


?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title>Interfaz Maloka</title>
	
	<script src="../interfaz_maloka/js/TweenMax.min.js"></script>

	<link rel="stylesheet" href="../interfaz_maloka/css/style.css">
</head>

<body>
	<div id="ekiipu">

		<div id="main">

			<input type="hidden" id="id_usuario" value="<?= $user['id'] ?>">
			<input type="hidden" id="id_curso" value="<?= ID_CURSO ?>">
			<input type="hidden" id="url_master" value="<?= URL_MASTER ?>">

			<div id="body_angeles">
				<div class="row maloka_head">
					<img src="../interfaz_maloka/imgs/banner.png" width="100%">
					<a href="https://helpdesk.multipleskills.com.co/" target="_blank">
						<img class="btn_ayuda" src="../interfaz_maloka/imgs/BT_ayuda.png" width="10%">
					</a>
				</div>
				<div id="dash_capitulos">
					<div class="row centered">
						<div class="col-md-1 col-sm-12">
							<br>
						</div>
						<?php
						$aux = 0;
						$salto = 0;
						foreach ($ordenes as $orden => $sec) {
								if ($salto == 5) {
									?>
									<div class="col-md-1 col-sm-12">
										<br>
									</div>
									<div class="col-md-1 col-sm-12">
										<br>
									</div>
									<?php
									$salto = 0; 
								}

								if ($sec < $sem) {
									$est = "N";
									$clase = "open_contenido"; 
								}
								if ($sec == $sem) {
									$est = "A";
									$clase = "open_contenido"; 
								}
								if ($sec > $sem) {
									$est = "I";
									$clase = ""; 
								}
								/*
								$pasado = "N";
								$activo = "A";
								$inactivo = "I";
								*/
								?>
								<div class="ficha col-md-2 col-sm-12">
									<a class="<?= $clase ?>" recurso="<?= $secciones[$sec]['seccion'] ?>">
										<img src="../interfaz_maloka/imgs/SEM<?= $sec ?>_<?= $est ?>.png" width="100%">   
									</a>
								</div>
								<?php
							$salto++;
						}
						?>
						
						<div class="col-md-1 col-sm-12">
							<br>
						</div>

					</div>
				</div>

				<?php
				// CAPITULOS
				$flag = 0;
				$aux_sec = 1;
				foreach ($secciones as $seccion) {
					?>
					<div id="<?= $seccion['seccion'] ?>" class="capitulos ocultar <?= "cap_".$aux_sec ?>" >
						<div class="row">
						<a href="#!" class="regresa_dash"><h5><b> &larr; Volver al inicio</b></h5></a>
						</div>
						<center><h2><?= $seccion['nombre'] ?></h2></center>

						<div class="row">
							<div class="list_enlaces col-md-8 col-sm-12">
								<h4> Contenido </h4>
								<?php
								foreach ($documentos as $enlace) {
									if ($enlace['seccion'] == $seccion['seccion']) {
										// EDITAR !!

										?>

										<div class="card enlace_recurso">
											<div class="card-body">
												<a href="<?= $enlace['enlace'] ?>" target="_blank">
													<center><h2><?= $enlace['nombre'] ?></h2></center>
												</a>
											</div>
										</div>

										<?php
									}
								}
								?>
							</div>
							<div class="list_enlaces col-md-4">
								
								<h4>Foros</h4>
								<?php
								foreach ($forum as $enlace) {
									if ($enlace['seccion'] == $seccion['seccion']) {
										// EDITAR !!

										?>

										<div class="card enlace_recurso">
											<div class="card-body">
												<a href="<?= $enlace['enlace'] ?>" target="_blank">
													<center><h2><?= $enlace['nombre'] ?></h2></center>
												</a>
											</div>
										</div>

										<?php
									}
								}
								?>

								<h4>Chat</h4>
								<?php
								foreach ($chat as $enlace) {
									if ($enlace['seccion'] == $seccion['seccion']) {
										// EDITAR !!

										?>

										<div class="card enlace_recurso">
											<div class="card-body">
												<a href="<?= $enlace['enlace'] ?>" target="_blank">
													<center><h2><?= $enlace['nombre'] ?></h2></center>
												</a>
											</div>
										</div>

										<?php
									}
								}
								?>

								<h4>Zoom</h4>
								<?php
								foreach ($enlaces as $enlace) {
									if ($enlace['seccion'] == $seccion['seccion']) {
										// EDITAR !!

										?>

										<div class="card enlace_recurso">
											<div class="card-body">
												<a href="<?= $enlace['enlace'] ?>" target="_blank">
													<center><h2><?= $enlace['nombre'] ?></h2></center>
												</a>
											</div>
										</div>

										<?php
									}
								}
								?>

							</div>
						</div>
					</div>
					<?php
					$aux_sec++;
					$flag++;
				}
				?>
			</div>
		</div>

		<!-- jQuery CDN - Slim version (=without AJAX) -->
		<script src="../interfaz_maloka/js/jquery.min.js"></script>

		<script src="../interfaz_maloka/js/init.js"></script>

	</div>
</body>

</html>