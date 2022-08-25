<?php
// Devido � quantidade de dados que esta fun��o poderia gerar,
// vamos apenas atualizar a base de dados de 10 em 10 minutos.
// Desta forma, se um usu�rio permanecer no site por 30 minutos,
// ser� registado tr�s vezes na tabela.
global $wpdb, $wpcvf, $wls_pessoas, $wls_areas, $wls_pessoas_options;
 
$nome					= @$_POST['nome']; 
$email					= @$_POST['email']; 

#exit;
// Checamos se n�o existe nenhum registo procedemos

// Registar os IPs na base de dados
$var = array(

  'nome' 					=> $nome,
  'email' 					=> $email,
  
			
);

$proto = strtolower(preg_replace('/[^a-zA-Z]/','',$_SERVER['SERVER_PROTOCOL'])); //pegando s� o que for letra 

if($_GET['id_formulario']){

	$location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&";
	
}else{
	
	$location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."?";
	
}

$id = 1;

echo $qry = $wpdb->update($wls_pessoas_options, $var, array('id' => $id), $format = null, $where_format = null );

$msg = "?msg=1";

if($qry == false && $qry != 0) { 
	
	$wpdb->show_errors(); 
	
	$wpdb->print_error();
	
	exit;
	
} else { 

	
	@header("Location:?page=configuracao-emails&msg=".$msg."");	

}

?>