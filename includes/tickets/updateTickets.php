<?php
	include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
	$query = "UPDATE tickets SET  IdCliente='".$_POST['ClienteUpdate']."', Origen='".$_POST['OrigenUpdate']."', Departamento='".$_POST['DepartamentoUpdate']."', Tipo='".$_POST['TipoUpdate']."', Subtipo='".$_POST['SubtipoUpdate']."', Prioridad='".$_POST['PrioridadUpdate']."', AsignarA='".$_POST['AsignarAUpdate']."', Estado='".$_POST['EstadoUpdate']."', IdServicios='".$_POST['ServicioUpdate']."', Observaciones='".$_POST['ObservacionesUpdate']."' WHERE IdTickets= ".$_POST['idUpdateTicket'];
	$run = new DB;
	$data = $run->query($query);
	echo $data;
 ?>