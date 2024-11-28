<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");

    $IdCliente = isset($_POST['Cliente']) ? trim($_POST['Cliente']) : "";
    $Origen = isset($_POST['Origen']) ? trim($_POST['Origen']) : "";
    $Departamento = isset($_POST['Departamento']) ? trim($_POST['Departamento']) : "";
    $Tipo = isset($_POST['Tipo']) ? trim($_POST['Tipo']) : "";
    $Subtipo = isset($_POST['Subtipo']) ? trim($_POST['Subtipo']) : "";
    $Prioridad = isset($_POST['Prioridad']) ? trim($_POST['Prioridad']) : "";
    $AsignarA = isset($_POST['AsignarA']) ? trim($_POST['AsignarA']) : "";
    $Estado = isset($_POST['Estado']) ? trim($_POST['Estado']) : "";
    $FechaCreacion = date("Y-m-d");
    $Observaciones = isset($_POST['Observaciones']) ? trim($_POST['Observaciones']) : "";
    $IdUsuarioSession = $_SESSION['id_usuario'];

	$query = "INSERT INTO tickets (IdCliente, Origen, Departamento, Tipo, Subtipo, Prioridad, AsignarA, Estado, FechaCreacion, Observaciones, IdUsuarioSession) VALUES ('$IdCliente', '$Origen', '$Departamento', '$Tipo', '$Subtipo', '$Prioridad', '$AsignarA', '$Estado', '$FechaCreacion', '$Observaciones', '$IdUsuarioSession')";

	$run = new DB;
	$data = $run->query($query);
	echo $data;
 ?>