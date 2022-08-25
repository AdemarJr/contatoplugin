<?php

// Vamos garantir que é o WordPress que chama este ficheiro
// e que realmente está a desistalar o plugin.
#if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
 	#die();
 

// Acesso ao objeto global de gestão de bases de dados
global $wpdb, $wpcvf, $wls_pessoas, $wls_areas, $wls_pessoas_options;


// Vamos checar se a nova tabela existe
// A propriedade prefix é o prefixo de tabela escolhido na
// instalação do WordPress


// Se a tabela não existe vamos criá-la

  $sql = "DROP TABLE ".$wls_pessoas;
  $wpdb->query($sql);
  
  $sql = "DROP TABLE ".$wls_areas;
  $wpdb->query($sql);
  
  $sql = "DROP TABLE ".$wls_pessoas_options;
  $wpdb->query($sql);

  $upload = wp_upload_dir();
  $upload_dir = $upload['basedir'];
  $upload_dir = $upload_dir . '/pessoas';
  
  @rmdir($upload_dir);

 
?>