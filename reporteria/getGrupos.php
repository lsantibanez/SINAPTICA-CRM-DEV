<?php
      require_once('../class/db/DB.php');

      $db = new DB();
      $output = '';
      $grupos = $db->select("SELECT idGrupo, Nombre from grupos");

      foreach($grupos as $row){
            $output .= '<option value="'.$row["idGrupo"].'">'.$row["Nombre"].'</option>';
      }
      echo $output;
 ?>