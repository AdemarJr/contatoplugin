<?php 

class WpWls{

	public $id;
	public $table;
	public $link;
	public $tipo;
	public $qtde;

	static $subTables;

	public function deleteTable($id, $table=""){
		global $wpdb;
	
		$proto = strtolower(preg_replace('/[^a-zA-Z]/','',$_SERVER['SERVER_PROTOCOL'])); //pegando só o que for letra 
		$location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		$array_url = explode("&", $location);

		$path = $this->removeMsg($location).'&msg=3';
		
		$path = $array_url[0];
				
		foreach($id as $regExcl){
			$delete = "DELETE FROM ".$table. " WHERE id = ".$regExcl." ";
			
			$wpdb->query($delete);
		}
		
		echo "<script>location.href='".$path."".$msg."'</script>";
	}


	public function deleteSub($id, $table=""){
		global $wpdb;
		
		$proto = strtolower(preg_replace('/[^a-zA-Z]/','',$_SERVER['SERVER_PROTOCOL'])); //pegando só o que for letra 
		$location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		$array_url = explode("&", $location);

		$path = $this->removeMsg($location).'&msg=3';
		
		$path = $array_url[0];

		foreach($id as $regExcl){
		
			$delete = "DELETE FROM ".$table. " WHERE id_cadastro = ".$regExcl." ";
			$wpdb->query($delete);
		}	
		
		echo "<script>location.href='".$path."".$msg."'</script>";
		
	}

	public function removeMsg($link){
		$return  = str_replace('&msg=1','', str_replace('&msg=2','', str_replace('&msg=3','', str_replace('&msg=4','', $link))));		
		return $return;
	}

	public function dataHora($dataHora, $tipo){
		/*
			tipo = 1 igual dia/mês/ano às hora:min:seg
			tipo = 2 igual dia/mês/ano
			tipo = 3 igual hora:min:seg
			tipo = 4 igual dia/mês/ano às hora:min
		*/
		
		$array = explode(" ", $dataHora);
		
		if($tipo==5 || $tipo==6){
			
			$dataArray = explode("/", $array[0]);
			
			$ano = $dataArray[2];
			$mes = $dataArray[1];
			$dia = $dataArray[0];
			
		}else{
			
			$dataArray = explode("-", $array[0]);
			
			$ano = $dataArray[0];
			$mes = $dataArray[1];
			$dia = $dataArray[2];
			
		}
		
		$horaArray = explode(":", $array[1]);
		
		$hora 	= $horaArray[0];
		$min 	= $horaArray[1];
		$seg 	= $horaArray[2];
		
		$anoAtual = date("Y");
		
		switch($tipo){
			case 1:{
				
				$data = $dia . "/" . $mes . "/" . $ano;
				$horario = $hora . ":"  . $min;
				
				$return = $data . " &agrave;s "  . $horario . " hrs";
				
				break;
			}
			case 2:{
				
				$data = $dia . "/" . $mes . "/" . $ano;
				$return = $data;
				
				break;
			}
			case 3:{
				
				$horario = $hora . ":"  . $min . " hrs";
				$return = $data . " &agrave;s "  . $horario;
				
				break;
			}
			case 4:{
				
				$data = $dia . "/" . $mes . "/" . str_replace("20", "", $ano);
				$horario = $hora . ":"  . $min;
				
				$return = $data . "<br/>"  . $horario . " hrs";
				
				break;
			}
			case 5:{
				
				$data = $ano . "-" . $mes . "-" . $dia;
				$return = $data;
				break;
			}
			case 6:{

				$data = $anoAtual - $ano;
				$return = $data;
				break;
			}
			default:{
				
				$return = $dataHora;
				
				break;
			}
		}
		
		return $return;
	}

	public function zerarEdit($table, $id_cadastro){
	
		global $wpdb;

		$sql 	= "SELECT * FROM ".$table." where id_cadastro = '".$id_cadastro."'";
		$query 	= $wpdb->get_results( $sql );

		foreach($query as $k => $v){
			$var = array(
					'edit' 	=> 0,
						  
			);

			$wpdb->update($table, $var, array('id' => @$v->id), $format = null, $where_format = null );
		}
		
		
	}

	public function deletarZero($table, $id_cadastro){
		
		global $wpdb;
		$iT = 0;

		for($iT==0;$iT<count($table);$iT++){

			$sqlCT 		= "SELECT * FROM ".$table[$iT]." where id_cadastro = '".$id_cadastro."' and edit = 0";
			$queryCT 	= $wpdb->get_results( $sqlCT );

			foreach($queryCT as $kCT => $vCT){
				$wpdb->get_row("DELETE FROM ".$table[$iT]." WHERE edit = 0", ARRAY_A);
				#$wpdb->query( $wpdb->prepare( "DELETE FROM ".$tabela." WHERE edit = %d" , array('edit' => 0) ) );
			}

		}
			
	}


}

?>