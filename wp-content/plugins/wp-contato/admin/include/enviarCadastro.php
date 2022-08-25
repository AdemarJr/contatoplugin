<?php
	// Devido à quantidade de dados que esta função poderia gerar,
	// vamos apenas atualizar a base de dados de 10 em 10 minutos.
	// Desta forma, se um usuário permanecer no site por 30 minutos,
	// será registado três vezes na tabela.
	 
global $wpdb, $wpcvf, $wls_pessoas, $wls_areas, $wls_pessoas_options;

$sqlV = "SELECT a.*
		   
		   FROM ".$wls_pessoas." a
		   
		   		where a.id = '".$_POST["id_cadastro"]."'";
			
			
$queryV = $wpdb->get_results( $sqlV );
foreach($queryV as $kV => $vV){
	$dadosV = $vV;
}

foreach ($_POST as $key=>$value){
	${$key} = $value;
}

/*echo "nome: ". $nome. "</br>";
echo "email: ". $email. "</br>";

exit;*/

if($_POST["area"]){
	
	$area 		= $_POST["area"];

	// A Hora a que o usuário acessou
	$current_time = current_time( 'mysql' );
	
	// Checamos se não existe nenhum registo procedemos
	$var2 = array(
	  'area' 		=> $area,
	);
	
	$wpdb->insert($wls_areas, $var2 );
	
	$id_area = $wpdb->insert_id;
}

#echo "<br/>";
#echo "senha atualizado: ".$senha;
//exit;

// A Hora a que o usuário acessou
$current_time = current_time( 'mysql' );

if($_FILES['pessoas']['name']){
			  
	$uploaddir = dirname(__FILE__)."/../../../../../wp-content/uploads/pessoas/";
	
	$tipoArquivo 	= explode(".", $_FILES['pessoas']['name']);
	$nome2 			= str_replace(" ", "", $nome);
	
	if(@$_SESSION['tipo']=="site"){
		
		@unlink("wp-content/uploads/pessoas/".@$_SESSION['pessoas']);
		$_SESSION['pessoas'] = $nome2.".".$tipoArquivo[1];
		
	}elseif(@$_POST['tipo']=="admin"){
		
		@unlink("wp-content/uploads/pessoas/".@$dado['pessoas']);
		$dado['pessoas'] = $nome2.".".$tipoArquivo[1];
		
	}
	
	$pessoas = $nome2.".".$tipoArquivo[1];
	
	#echo $uploaddir. $pessoas;
	#exit;
	move_uploaded_file($_FILES['pessoas']['tmp_name'], $uploaddir. $pessoas);
		
}elseif($_FILES['pessoas']['name'] == "" && $pessoasCar != ""){
	
	$tipoArquivo = explode(".", @$pessoasCar);
	$nomeNovo = $nome2.".".@$tipoArquivo[1];
	
	rename(@$uploaddir.@$pessoasCar, @$uploaddir.@$nomeNovo);
	//exit;
	$pessoas = $nomeNovo;

}else{
	$pessoas = "";
}


###################################################################################################

###################################################################################################

// Checamos se não existe nenhum registo procedemos
#if (!$cpf ) {
  // Registar os IPs na base de dados
  
  
  $var = array(
	'id_area' 		=> $id_area,
	'nome' 			=> $nome,
	'email' 		=> $email,
	'pais' 		=> $pais,
	'pessoas' 	=> $pessoas,
  );
  
  $proto = strtolower(preg_replace('/[^a-zA-Z]/','',$_SERVER['SERVER_PROTOCOL'])); //pegando só o que for letra 
  
  if($_GET){
  
	  $location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&";
	  
  }else{
	  
	  $location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."?";
	  
  }
  
  	  $path = $wpcvf->removeMsg($location);
  
  if(@$_POST['mod']=="new"){
	  
	  $qry = $wpdb->insert($wls_pessoas, $var );
	  $id_cadastro = $wpdb->insert_id;
	  
	  include(dirname(__FILE__)."/../../emails/cadastro.php");
	  include(dirname(__FILE__)."/../../emails/cadastro_admin.php");
	  
  }elseif(@$_POST['mod']=="edit"){
	  
	  if(@$_POST['excluirConta']==1){
		  
		  $qry = $wpdb->query( $wpdb->prepare( "DELETE FROM ".$wls_pessoas." WHERE id = %d" , array('id' => $_SESSION['id_cadastro']) ) );
		  
	  }
	  
	  if($_POST['id_cadastro']){
		  
		  $qry = $wpdb->update($wls_pessoas, $var, array('id' => $_POST['id_cadastro']), $format = null, $where_format = null );		
		  $id_cadastro = $_POST['id_cadastro'];
		  
	  }
  }
  
  if($qry == false && $qry != 0) { 
		
	  $wpdb->show_errors(); 
	  
	  $wpdb->print_error();
	  
	  exit;
	  
  } else {
	  
	  if(@$_POST['tipo']=="admin"){
		  
		  if(@$_POST['mod']=="new"){
			
			$msg = "&msg=1";
	  	  	echo "<script>location.href='?page=formulario-admin&id_cadastro=".@$id_cadastro."".$msg."'</script>";  
			
		  }elseif(@$_POST['mod']=="edit"){
			$msg = "&msg=2";
		  	echo "<script>location.href='?page=formulario-admin&id_cadastro=".@$id_cadastro."".$msg."'</script>";  
			
		  }
		  
	  }elseif(@$_SESSION['logado']==1){
		  if(@$_POST['excluirConta']==1){
			  $msg = "&msg=3";
			  echo "<script>location.href='".$path."&logout=3".$msg."'</script>";
		  }elseif(@$_POST['mod']=="new"){
			  $msg = "&msg=1";
			  echo "<script>location.href='".$path."&logout=1".$msg."'</script>";
		  }elseif(@$_POST['mod']=="edit"){
			  $msg = "&msg=2";
			  echo "<script>location.href='".$path."".$msg."'</script>";
		  }
		  
	  }
	  
	  
  }	
  
?>