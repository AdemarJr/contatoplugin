<?php
#define('DISALLOW_FILE_EDIT', true );
// Acesso ao objeto global de gestão de bases de dados
global $wpdb, $wpcvf, $wls_pessoas, $wls_contact, $wls_areas, $wls_pessoas_options;

// Vamos checar se a nova tabela existe
// A propriedade prefix é o prefixo de tabela escolhido na
// instalação do WordPress


// Se a tabela não existe vamos criá-la
	if ( $wpdb->get_var( "SHOW TABLES LIKE '".$wls_pessoas."'" ) != $wls_pessoas ) {	  
  
	  $sql = "
			
			CREATE TABLE ".$wls_pessoas."(
				  id 			int(11) 		NOT NULL AUTO_INCREMENT,
				  nome 			varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  email 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			  	  pessoas 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  status 		int(11) 		DEFAULT NULL,
				  PRIMARY KEY (id)
			);";
  }
		
   	$wpdb->get_row("ALTER TABLE ".$wls_pessoas_options." ADD assunto_cadastro_admin varchar(255) DEFAULT NULL AFTER mensagem_cadastro", ARRAY_A);
  	$wpdb->get_row("ALTER TABLE ".$wls_pessoas_options." ADD mensagem_cadastro_admin text DEFAULT NULL AFTER assunto_cadastro_admin", ARRAY_A);
	
	$sqlOp = "SELECT * FROM ".$wls_pessoas_options." where id=1";
		
	$queryOp = $wpdb->get_results( $sqlOp, ARRAY_A );
	
	foreach($queryOp as $kOp => $vOp){
		$dadosOp = $vOp;
	}
	
	if($dadosOp['id']!=1){
		$assunto_cadastro 	= "Cadastrado com sucesso!";
		$mensagem_cadastro 	= "Cadastrado com sucesso!<br/>\n";
		
		$assunto_cadastro_admin 	= "Novo cadastrado";
		$mensagem_cadastro_admin 	= "Nome: @nome <br/>
			Área de serviço: @area";
		
		
		
		$varOptions = array(
		  'assunto_cadastro' 	=> $assunto_cadastro,
		  'mensagem_cadastro' 	=> $mensagem_cadastro,
		  'assunto_cadastro_admin' 	=> $assunto_cadastro_admin,
		  'mensagem_cadastro_admin' => $mensagem_cadastro_admin,
		  
		  
		);
		
		$wpdb->insert($wls_pessoas_options, $varOptions );
	}
		
	$sql3 ="	
		CREATE TABLE ".$wls_pessoas."wls_pessoas LIKE wls_pessoas;
		";
		
// Se a tabela não existe vamos criá-la
if ( $wpdb->get_var( "SHOW TABLES LIKE '".$wls_contact."'" ) != $wls_contact ) {	  
  
	$sql = "
		  
		  CREATE TABLE ".$wls_pessoas."(
				id 			int(11) 		NOT NULL AUTO_INCREMENT,
				countrycode varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				number 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				
				PRIMARY KEY (id)
		  );";
}

  // Para usarmos a função dbDelta() é necessário carregar este ficheiro
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

  // Esta função cria a tabela na base de dados e executa as otimizações
  // necessárias.
  dbDelta( $sql );
  dbDelta( $sql1 );
  dbDelta( $sql2 );
  dbDelta( $sql3 );
  
  $upload = wp_upload_dir();
  $upload_dir = $upload['basedir'];
  $upload_dir = $upload_dir . '/pessoass';
  
  if (! is_dir($upload_dir)) {
	 mkdir( $upload_dir, 0777 );
  }

//}
?>