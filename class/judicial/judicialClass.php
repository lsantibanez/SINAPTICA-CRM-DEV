<?php
    class Judicial {
        
        public function getPersonas(){
        	$db = new DB();
            $Sql = "	SELECT 
            				Persona.Rut, 
            				Persona.Nombre_Completo, 
            				Deuda.Numero_Operacion 
            			FROM 
							Persona 
						INNER JOIN 
							Deuda 
						ON 
							Deuda.Rut = Persona.Rut 
						WHERE 
							FIND_IN_SET('".$_SESSION['cedente']."',Persona.Id_Cedente) 
						ORDER BY 
							Persona.Nombre_Completo";
		    $Personas = $db -> select($Sql);
            return $Personas;
        }

        public function getPersona($RutId){

        	$RutId = explode('-',$RutId);
        	$Rut = $RutId[0];
        	$Id = $RutId[1];

        	$db = new DB();
        	$toReturn = array();
		    $Sql = "SELECT Direccion FROM Direcciones WHERE Rut = '".$Rut."' ORDER BY Fecha_Ingreso";
		    $Direccion = $db -> select($Sql);
		    $Sql = "SELECT formato_subtel FROM fono_cob WHERE Rut = '".$Rut."' ORDER BY fecha_carga";
		    $Telefono = $db -> select($Sql);

		    if($Direccion){
		    	$Direccion = $Direccion[0]['Direccion'];
		    }else{
		    	$Direccion = '';
		    }

		    if($Telefono){
		    	$Telefono = $Telefono[0]['formato_subtel'];
		    }else{
		    	$Telefono = '';
		    }

		    $Sql = "SELECT 
		    			Id_deuda,
		    			SaldoCapital,
		    			SaldoInteresVencido,
		    			Saldo_Interes_Suspendido,
		    			Saldo_Interes_Penal_Bco,
		    			SaldoGastosCobranza 
		    		FROM 
		    			Deuda 
		    		WHERE 
		    			Rut = '".$Rut."'";
		    $Deuda = $db -> select($Sql);

		    if($Deuda){
		    	$DeudaId = $Deuda[0]['Id_deuda'];
		    	$SaldoCapital = $Deuda[0]['SaldoCapital'];
		    	$InteresVencido = $Deuda[0]['SaldoInteresVencido'];
		    	$InteresSuspendido = $Deuda[0]['Saldo_Interes_Suspendido'];
		    	$InteresPenal = $Deuda[0]['Saldo_Interes_Penal_Bco'];
		    	$GastosCobranza = $Deuda[0]['SaldoGastosCobranza'];
		    }else{
		    	$DeudaId = '';
		    	$SaldoCapital = '';
		    	$InteresVencido = '';
		    	$InteresSuspendido = '';
		    	$InteresPenal = '';
		    	$GastosCobranza = '';
		    }	

		    $toReturn['Rut'] = $Rut;
		    $toReturn['Direccion'] = $Direccion;
		    $toReturn['Telefono'] = $Telefono;
		    $toReturn['DeudaId'] = $DeudaId;
		    $toReturn['SaldoCapital'] = $SaldoCapital;
		    $toReturn['InteresVencido'] = $InteresVencido;
		    $toReturn['InteresSuspendido'] = $InteresSuspendido;
		    $toReturn['InteresPenal'] = $InteresPenal;
		    $toReturn['GastosCobranza'] = $GastosCobranza;
            return $toReturn;
        }

        public function getDeuda($Id){

        	$db = new DB();
        	$toReturn = array();
		    $Sql = "SELECT 
		    			SaldoCapital,
		    			SaldoInteresVencido,
		    			Saldo_Interes_Suspendido,
		    			Saldo_Interes_Penal_Bco,
		    			SaldoGastosCobranza 
		    		FROM 
		    			Deuda 
		    		WHERE 
		    			Id_deuda = '".$Id."'";
		    $Deuda = $db -> select($Sql);

		    if($Deuda){
		    	$SaldoCapital = $Deuda[0]['SaldoCapital'];
		    	$InteresVencido = $Deuda[0]['SaldoInteresVencido'];
		    	$InteresSuspendido = $Deuda[0]['Saldo_Interes_Suspendido'];
		    	$InteresPenal = $Deuda[0]['Saldo_Interes_Penal_Bco'];
		    	$GastosCobranza = $Deuda[0]['SaldoGastosCobranza'];
		    }else{
		    	$SaldoCapital = '';
		    	$InteresVencido = '';
		    	$InteresSuspendido = '';
		    	$InteresPenal = '';
		    	$GastosCobranza = '';
		    }	

		    $toReturn['SaldoCapital'] = $SaldoCapital;
		    $toReturn['InteresVencido'] = $InteresVencido;
		    $toReturn['InteresSuspendido'] = $InteresSuspendido;
		    $toReturn['InteresPenal'] = $InteresPenal;
		    $toReturn['GastosCobranza'] = $GastosCobranza;
            return $toReturn;
        }

    }

?>