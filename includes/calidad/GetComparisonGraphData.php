<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("personal");
    $CalidadClass = new Calidad();
    $PersonalClass = new Personal();
    $Comparacion = $_POST["Comparacion"];
    $Comparacion = $Comparacion == "false" ? false : true;
    $MandanteLeft = $_POST['MandanteLeft'];
    $PautaLeft = $_POST['PautaLeft'];
    $EjecutivoLeft = $_POST['EjecutivoLeft'];
    $MandanteRight = $_POST['MandanteRight'];
    $PautaRight = $_POST['PautaRight'];
    $EjecutivoRight = $_POST['EjecutivoRight'];
    $Type = isset($_POST['Tipo']) ? $_POST['Tipo'] : "Historico";
    $ArrayGeneral = array();
    $ArrayGeneral["GeneralItems"] = array();
    $ArrayGeneral["GeneralItems"][0]["General"] = array();
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                            INICIO GRAFICA GENERAL                            ////
    //////////////////////////////////////////////////////////////////////////////////////
    $ArrayGeneral["General"][0][0] = $CalidadClass->getGeneralGraphDataByUserType("1",$MandanteLeft,$PautaLeft,$EjecutivoLeft,$Type);
    $ArrayGeneral["General"][0][1] = $CalidadClass->getGeneralGraphDataByUserType("3",$MandanteLeft,$PautaLeft,$EjecutivoLeft,$Type);
    $ArrayGeneral["General"][0][2] = $CalidadClass->getGeneralGraphDataByUserType("2",$MandanteLeft,$PautaLeft,$EjecutivoLeft,$Type);
    if($Comparacion){
        $ArrayGeneral["General"][1][0] = $CalidadClass->getGeneralGraphDataByUserType("1",$MandanteRight,$PautaRight,$EjecutivoRight,$Type);
        $ArrayGeneral["General"][1][1] = $CalidadClass->getGeneralGraphDataByUserType("3",$MandanteRight,$PautaRight,$EjecutivoRight,$Type);
        $ArrayGeneral["General"][1][2] = $CalidadClass->getGeneralGraphDataByUserType("2",$MandanteRight,$PautaRight,$EjecutivoRight,$Type);
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                            FIN GRAFICA GENERAL                               ////
    //////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////
    ////                     INICIO GRAFICA GENERAL POR ITEMS                         ////
    //////////////////////////////////////////////////////////////////////////////////////
    $ArrayGeneralItems[0][0] = $CalidadClass->getGeneralByEvaluationGraphDataByUserType("1",$MandanteLeft,$PautaLeft,$EjecutivoLeft);
    $ArrayGeneralItems[0][1] = $CalidadClass->getGeneralByEvaluationGraphDataByUserType("3",$MandanteLeft,$PautaLeft,$EjecutivoLeft);
    $ArrayGeneralItems[0][2] = $CalidadClass->getGeneralByEvaluationGraphDataByUserType("2",$MandanteLeft,$PautaLeft,$EjecutivoLeft);
    if($Comparacion){
        $ArrayGeneralItems[1][0] = $CalidadClass->getGeneralByEvaluationGraphDataByUserType("1",$MandanteRight,$PautaRight,$EjecutivoRight);
        $ArrayGeneralItems[1][1] = $CalidadClass->getGeneralByEvaluationGraphDataByUserType("3",$MandanteRight,$PautaRight,$EjecutivoRight);
        $ArrayGeneralItems[1][2] = $CalidadClass->getGeneralByEvaluationGraphDataByUserType("2",$MandanteRight,$PautaRight,$EjecutivoRight);
    }
    $conttemp = 0;
    foreach($ArrayGeneralItems[0][0] as $Item){
        $ArrayGeneral["GeneralItems"][0]["General"][$conttemp]["evaluacion"] = $ArrayGeneralItems[0][0][$conttemp]["Evaluacion"];
        $ArrayGeneral["GeneralItems"][0]["General"][$conttemp][$ArrayGeneralItems[0][0][0]["UserTypeName"]] = $ArrayGeneralItems[0][0][$conttemp]["Nota"];
        $ArrayGeneral["GeneralItems"][0]["General"][$conttemp][$ArrayGeneralItems[0][1][0]["UserTypeName"]] = $ArrayGeneralItems[0][1][$conttemp]["Nota"];
        $ArrayGeneral["GeneralItems"][0]["General"][$conttemp][$ArrayGeneralItems[0][2][0]["UserTypeName"]] = $ArrayGeneralItems[0][2][$conttemp]["Nota"];
        
        
        $conttemp++;
    }
    
    if($Comparacion){
        $conttemp = 0;
        foreach($ArrayGeneralItems[1][0] as $Item){
            
            $ArrayGeneral["GeneralItems"][1]["General"][$conttemp]["evaluacion"] = $ArrayGeneralItems[1][0][$conttemp]["Evaluacion"];
            $ArrayGeneral["GeneralItems"][1]["General"][$conttemp][$ArrayGeneralItems[1][0][0]["UserTypeName"]] = $ArrayGeneralItems[1][0][$conttemp]["Nota"];
            $ArrayGeneral["GeneralItems"][1]["General"][$conttemp][$ArrayGeneralItems[1][1][0]["UserTypeName"]] = $ArrayGeneralItems[1][1][$conttemp]["Nota"];
            $ArrayGeneral["GeneralItems"][1]["General"][$conttemp][$ArrayGeneralItems[1][2][0]["UserTypeName"]] = $ArrayGeneralItems[1][2][$conttemp]["Nota"];
            
            
            $conttemp++;
        }
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                     FIN GRAFICA GENERAL POR ITEMS                            ////
    //////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////
    ////                         INICIO GRAFICA POR ITEMS                             ////
    //////////////////////////////////////////////////////////////////////////////////////
    $ArrayItems[0][0] = $CalidadClass->getByEvaluationGraphDataByUserType("1",$MandanteLeft,$PautaLeft,$EjecutivoLeft,$Type);
    $ArrayItems[0][1] = $CalidadClass->getByEvaluationGraphDataByUserType("3",$MandanteLeft,$PautaLeft,$EjecutivoLeft,$Type);
    $ArrayItems[0][2] = $CalidadClass->getByEvaluationGraphDataByUserType("2",$MandanteLeft,$PautaLeft,$EjecutivoLeft,$Type);
    if($Comparacion){
        $ArrayItems[1][0] = $CalidadClass->getByEvaluationGraphDataByUserType("1",$MandanteRight,$PautaRight,$EjecutivoRight,$Type);
        $ArrayItems[1][1] = $CalidadClass->getByEvaluationGraphDataByUserType("3",$MandanteRight,$PautaRight,$EjecutivoRight,$Type);
        $ArrayItems[1][2] = $CalidadClass->getByEvaluationGraphDataByUserType("2",$MandanteRight,$PautaRight,$EjecutivoRight,$Type);
    }
    $Evaluations = $CalidadClass->getEvaluationTemplateByPerfil($PautaLeft,$MandanteLeft);
    $ArrayEvaluation = array();
    $Cont = 0;
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    foreach($Evaluations as $Evaluation){
        $Array = array();
        $cadena = $Evaluation["Nombre"];
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = utf8_encode($cadena);
        if(isset($ArrayItems[0][0][$cadena])){
            array_push($Array,$ArrayItems[0][0][$cadena]);
        }
        array_push($Array,$ArrayItems[0][1][$cadena]);
        array_push($Array,$ArrayItems[0][2][$cadena]);
        array_push($ArrayEvaluation,$Array);
        $Cont++;
    }
    array_push($ArrayGeneral["GeneralItems"][0],$ArrayEvaluation);
    if($Comparacion){
        $Evaluations = $CalidadClass->getEvaluationTemplateByPerfil($PautaRight,$MandanteRight);
        $ArrayEvaluation = array();
        $Cont = 0;
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        foreach($Evaluations as $Evaluation){
            $Array = array();
            $cadena = $Evaluation["Nombre"];
            $cadena = utf8_decode($cadena);
            $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
            $cadena = utf8_encode($cadena);
            if(isset($ArrayItems[1][0][$cadena])){
                array_push($Array,$ArrayItems[1][0][$cadena]);
            }
            array_push($Array,$ArrayItems[1][1][$cadena]);
            array_push($Array,$ArrayItems[1][2][$cadena]);
            array_push($ArrayEvaluation,$Array);
            $Cont++;
        }
        array_push($ArrayGeneral["GeneralItems"][1],$ArrayEvaluation);
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                         FIN GRAFICA POR ITEMS                                 ////
    //////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////
    ////                         INICIO NOMBRE DE ITEMS                               ////
    //////////////////////////////////////////////////////////////////////////////////////
    $Evaluations = $CalidadClass->getEvaluationTemplateByPerfil($PautaLeft,$MandanteLeft);
    $ArrayEvaluationNames = array();
    $ContEvaluationNames = 0;
    $Cont = 0;
    foreach($Evaluations as $Evaluation){
        $Array = array();
        $Array[0] = $Cont;
        $Array[1] = $Evaluation["Nombre"];
        $ArrayEvaluationNames[$ContEvaluationNames] = $Array;
        $ContEvaluationNames++;
        $Cont++;
    }
    $ArrayGeneral["ItemsName"]["Left"] = array();
    $ArrayGeneral["ItemsName"]["Right"] = array();
    $ArrayGeneral["ItemsName"]["Left"] = $ArrayEvaluationNames;
    if($Comparacion){
        $Evaluations = $CalidadClass->getEvaluationTemplateByPerfil($PautaRight,$MandanteRight);
        $ArrayEvaluationNames = array();
        $ContEvaluationNames = 0;
        $Cont = 0;
        foreach($Evaluations as $Evaluation){
            $Array = array();
            $Array[0] = $Cont;
            $Array[1] = $Evaluation["Nombre"];
            $ArrayEvaluationNames[$ContEvaluationNames] = $Array;
            $ContEvaluationNames++;
            $Cont++;
        }
        $ArrayGeneral["ItemsName"]["Right"] = $ArrayEvaluationNames;
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                            FIN NOMBRE DE ITEMS                               ////
    //////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                         INICIO MESES GRAFICA GENERAL                          ////
    //////////////////////////////////////////////////////////////////////////////////////
    switch($Type){
        case 'Mes':
            $ArrayMesesGeneral = array();
            for($i=1;$i<=4;$i++){
                $Array = array();
                $Array[0] = $i;
                $Array[1] = "Semana ".$i;
                array_push($ArrayMesesGeneral,$Array);
            }
            $ArrayGeneral["MesesGeneral"] = $ArrayMesesGeneral;
        break;
        case 'Historico':
            $Meses = array();
            $Meses["01"] = "Enero";
            $Meses["02"] = "Febrero";
            $Meses["03"] = "Marzo";
            $Meses["04"] = "Abril";
            $Meses["05"] = "Mayo";
            $Meses["06"] = "Junio";
            $Meses["07"] = "Julio";
            $Meses["08"] = "Agosto";
            $Meses["09"] = "Septiembre";
            $Meses["10"] = "Octubre";
            $Meses["11"] = "Noviembre";
            $Meses["12"] = "Diciembre";
            $ArrayMesesGeneral = array();
            $DateArray = $CalidadClass->getDateFromServer();
            $Now = $DateArray["date"];
            $Now = date("Ymd",strtotime ( '+1 months' , strtotime ( $Now ) )) ;
            $Now = new DateTime($Now);
            $Now->modify('first day of this month');
            $Now = $Now->format('Ym01');
            $SixMonthsAgo = strtotime ( '-6 months' , strtotime ( $Now ) ) ;
            $SixMonthsAgo = date ( 'Ym01' , $SixMonthsAgo );
            $SixMonthsAgo = new DateTime($SixMonthsAgo);
            $SixMonthsAgo->modify('first day of this month');
            $SixMonthsAgo = $SixMonthsAgo->format('Ymd');
            $Now = date("Ymd",strtotime ( '-1 months' , strtotime ( $Now ) )) ;
            $Next = $SixMonthsAgo;
            $Year = date('Y',strtotime($Next));
            $Month = date('m',strtotime($Next));
            for($i=1;$i<=6;$i++){
                $Array = array();
                $Array[0] = $i;
                $Array[1] = $Meses[$Month]." ".$Year;
                array_push($ArrayMesesGeneral,$Array);
                $Next = strtotime('+1 months',strtotime($Next));
                $Next = date('Ymd',$Next);
                $Year = date('Y',strtotime($Next));
                $Month = date('m',strtotime($Next));
            }
            $ArrayGeneral["MesesGeneral"] = $ArrayMesesGeneral;
        break;
    }
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                      FIN MESES GRAFICA GENERAL                               ////
    //////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                         INICIO HUMAN COMPARISON                              ////
    //////////////////////////////////////////////////////////////////////////////////////


    $HumanComparison = array();
    
    //Total Ejecutivos
        $Value = $CalidadClass->getTotalEjecutivosPauta($MandanteLeft,$PautaLeft,$EjecutivoLeft);
        $ArrayValue = explode("|",$Value);
        $HumanComparison["Left"][0]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
        $HumanComparison["Left"][0]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
        $HumanComparison["Left"][0]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
    //Estado Civil
        $Value = $PersonalClass->getHumanComparison_EstadoCivil($MandanteLeft,$PautaLeft,$EjecutivoLeft);
        $ArrayValue = explode("|",$Value);
        $HumanComparison["Left"][1]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
        $HumanComparison["Left"][1]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
        $HumanComparison["Left"][1]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
    //Edad
        $Value = $PersonalClass->getHumanComparison_Edad($MandanteLeft,$PautaLeft,$EjecutivoLeft);
        $ArrayValue = explode("|",$Value);
        $HumanComparison["Left"][2]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
        $HumanComparison["Left"][2]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
        $HumanComparison["Left"][2]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
    //Antiguedad
        $Value = $PersonalClass->getHumanComparison_Antiguedad($MandanteLeft,$PautaLeft,$EjecutivoLeft);
        $ArrayValue = explode("|",$Value);
        $HumanComparison["Left"][3]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
        $HumanComparison["Left"][3]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
        $HumanComparison["Left"][3]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;

    //Tipo_Contrato_PlazoFijo
        $Value = $PersonalClass->getHumanComparison_Tipo_Contrato_PlazoFijo($MandanteLeft,$PautaLeft,$EjecutivoLeft);
        $ArrayValue = explode("|",$Value);
        $HumanComparison["Left"][4]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
        $HumanComparison["Left"][4]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
        $HumanComparison["Left"][4]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;

    //Cargas
        $Value = $PersonalClass->getHumanComparison_Cargas($MandanteLeft,$PautaLeft,$EjecutivoLeft);
        $ArrayValue = explode("|",$Value);
        $HumanComparison["Left"][5]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
        $HumanComparison["Left"][5]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
        $HumanComparison["Left"][5]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
    //Sexo
        $Value = $PersonalClass->getHumanComparison_Sexo($MandanteLeft,$PautaLeft,$EjecutivoLeft);
        $ArrayValue = explode("|",$Value);
        $HumanComparison["Left"][6]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
        $HumanComparison["Left"][6]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
        $HumanComparison["Left"][6]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
    //Nacionalidad
        $Value = $PersonalClass->getHumanComparison_Nacionalidad($MandanteLeft,$PautaLeft,$EjecutivoLeft);
        $ArrayValue = explode("|",$Value);
        $HumanComparison["Left"][7]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
        $HumanComparison["Left"][7]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
        $HumanComparison["Left"][7]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
    //Tipo_Ejecutivo
        $Value = $PersonalClass->getHumanComparison_Tipo_Ejecutivo($MandanteLeft,$PautaLeft,$EjecutivoLeft);
        $ArrayValue = explode("|",$Value);
        $HumanComparison["Left"][8]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
        $HumanComparison["Left"][8]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
        $HumanComparison["Left"][8]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
    //Tipo_Contrato_Indefinido
        $Value = $PersonalClass->getHumanComparison_Tipo_Contrato_Indefinido($MandanteLeft,$PautaLeft,$EjecutivoLeft);
        $ArrayValue = explode("|",$Value);
        $HumanComparison["Left"][9]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
        $HumanComparison["Left"][9]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
        $HumanComparison["Left"][9]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
        if($EjecutivoLeft == ""){
            //Rotacion
                $Value = $PersonalClass->getHumanComparison_Rotacion($MandanteLeft,$PautaLeft,$EjecutivoLeft);
                $ArrayValue = explode("|",$Value);
                $HumanComparison["Left"][10]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
                $HumanComparison["Left"][10]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
                $HumanComparison["Left"][10]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
            //Despedidos
                $Value = $PersonalClass->getHumanComparison_Despedidos($MandanteLeft,$PautaLeft,$EjecutivoLeft);
                $ArrayValue = explode("|",$Value);
                $HumanComparison["Left"][11]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
                $HumanComparison["Left"][11]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
                $HumanComparison["Left"][11]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
            //Renuncia
                $Value = $PersonalClass->getHumanComparison_Renuncia($MandanteLeft,$PautaLeft,$EjecutivoLeft);
                $ArrayValue = explode("|",$Value);
                $HumanComparison["Left"][12]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
                $HumanComparison["Left"][12]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
                $HumanComparison["Left"][12]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
        }
    if($Comparacion){
        //Total Ejecutivos
            $Value = $CalidadClass->getTotalEjecutivosPauta($MandanteRight,$PautaRight,$EjecutivoRight);
            $ArrayValue = explode("|",$Value);
            $HumanComparison["Right"][0]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
            $HumanComparison["Right"][0]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
            $HumanComparison["Right"][0]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
        //Estado Civil
            $Value = $PersonalClass->getHumanComparison_EstadoCivil($MandanteRight,$PautaRight,$EjecutivoRight);
            $ArrayValue = explode("|",$Value);
            $HumanComparison["Right"][1]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
            $HumanComparison["Right"][1]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
            $HumanComparison["Right"][1]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
        //Edad
            $Value = $PersonalClass->getHumanComparison_Edad($MandanteRight,$PautaRight,$EjecutivoRight);
            $ArrayValue = explode("|",$Value);
            $HumanComparison["Right"][2]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
            $HumanComparison["Right"][2]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
            $HumanComparison["Right"][2]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
        //Antiguedad
            $Value = $PersonalClass->getHumanComparison_Antiguedad($MandanteRight,$PautaRight,$EjecutivoRight);
            $ArrayValue = explode("|",$Value);
            $HumanComparison["Right"][3]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
            $HumanComparison["Right"][3]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
            $HumanComparison["Right"][3]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;

        //TipoContrato
            $Value = $PersonalClass->getHumanComparison_Tipo_Contrato_PlazoFijo($MandanteRight,$PautaRight,$EjecutivoRight);
            $ArrayValue = explode("|",$Value);
            $HumanComparison["Right"][4]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
            $HumanComparison["Right"][4]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
            $HumanComparison["Right"][4]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;

        //Cargas
            $Value = $PersonalClass->getHumanComparison_Cargas($MandanteRight,$PautaRight,$EjecutivoRight);
            $ArrayValue = explode("|",$Value);
            $HumanComparison["Right"][5]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
            $HumanComparison["Right"][5]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
            $HumanComparison["Right"][5]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
        //Sexo
            $Value = $PersonalClass->getHumanComparison_Sexo($MandanteRight,$PautaRight,$EjecutivoRight);
            $ArrayValue = explode("|",$Value);
            $HumanComparison["Right"][6]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
            $HumanComparison["Right"][6]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
            $HumanComparison["Right"][6]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
        //Nacionalidad
            $Value = $PersonalClass->getHumanComparison_Nacionalidad($MandanteRight,$PautaRight,$EjecutivoRight);
            $ArrayValue = explode("|",$Value);
            $HumanComparison["Right"][7]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
            $HumanComparison["Right"][7]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
            $HumanComparison["Right"][7]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
        //Tipo_Ejecutivo
            $Value = $PersonalClass->getHumanComparison_Tipo_Ejecutivo($MandanteRight,$PautaRight,$EjecutivoRight);
            $ArrayValue = explode("|",$Value);
            $HumanComparison["Right"][8]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
            $HumanComparison["Right"][8]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
            $HumanComparison["Right"][8]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
        //Tipo_Contrato
            $Value = $PersonalClass->getHumanComparison_Tipo_Contrato_Indefinido($MandanteRight,$PautaRight,$EjecutivoRight);
            $ArrayValue = explode("|",$Value);
            $HumanComparison["Right"][9]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
            $HumanComparison["Right"][9]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
            $HumanComparison["Right"][9]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
            if($EjecutivoRight == ""){
                //Rotacion
                    $Value = $PersonalClass->getHumanComparison_Rotacion($MandanteRight,$PautaRight,$EjecutivoRight);
                    $ArrayValue = explode("|",$Value);
                    $HumanComparison["Right"][10]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
                    $HumanComparison["Right"][10]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
                    $HumanComparison["Right"][10]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
                //Despedidos
                    $Value = $PersonalClass->getHumanComparison_Despedidos($MandanteRight,$PautaRight,$EjecutivoRight);
                    $ArrayValue = explode("|",$Value);
                    $HumanComparison["Right"][11]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
                    $HumanComparison["Right"][11]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
                    $HumanComparison["Right"][11]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
                //Renuncia
                    $Value = $PersonalClass->getHumanComparison_Renuncia($MandanteRight,$PautaRight,$EjecutivoRight);
                    $ArrayValue = explode("|",$Value);
                    $HumanComparison["Right"][12]["Valor"] = $ArrayValue[0] == "" ? 0 : ceil(floatval($ArrayValue[0]));
                    $HumanComparison["Right"][12]["Etiqueta"] = !isset($ArrayValue[1]) ? "%" : $ArrayValue[1];
                    $HumanComparison["Right"][12]["Porcentaje"] = !isset($ArrayValue[1]) ? true : $ArrayValue[1] == "%" ? true : false;
            }
    }
    
    $ArrayGeneral["HumanComparison"] = $HumanComparison;
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                            FIN HUMAN COMPARISON                              ////
    //////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                         INICIO PERFIL EJECUTIVO                              ////
    //////////////////////////////////////////////////////////////////////////////////////
    $Perfil = array();
    $PerfilArray = $CalidadClass->getPerfilByUserType("1",$PautaLeft,$EjecutivoLeft);
    if($PerfilArray){
        $Perfil["Left"]["Titulo"] = utf8_encode($PerfilArray["nombre"]);
        $Perfil["Left"]["Descripcion"] = utf8_encode($PerfilArray["descripcion"]);
    }else{
        $Perfil["Left"]["Titulo"] = '';
        $Perfil["Left"]["Descripcion"] = '';
    }
    if($Comparacion){
        $PerfilArray = $CalidadClass->getPerfilByUserType("1",$PautaRight,$EjecutivoRight);
        if($PerfilArray){
            $Perfil["Right"]["Titulo"] = utf8_encode($PerfilArray["nombre"]);
            $Perfil["Right"]["Descripcion"] = utf8_encode($PerfilArray["descripcion"]);
        }else{
            $Perfil["Right"]["Titulo"] = '';
            $Perfil["Right"]["Descripcion"] = '';
        }
    }
    $ArrayGeneral["Perfil"] = $Perfil;
    ///////////////////////////////////////////////////////////////////////////////////////
    ////                            FIN PERFIL EJECUTIVO                              ////
    //////////////////////////////////////////////////////////////////////////////////////
    echo json_encode($ArrayGeneral);







?>