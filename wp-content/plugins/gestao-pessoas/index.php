<?php
/*
Plugin Name: WP-Gestão de Contatos
Plugin URI: http://wpgestaocontto.wordpress.com/plugin/
Description: Objetivo deste plugin é de gerencias cadastro de pessoas!
Version: 1.0
Author: Ademar Farias Jr
Author URI: http://www.ademarfariasjr.com.br/
License: GPLv2
*/


include_once( plugin_dir_path( __FILE__ ) . 'admin/include/class.wpwls.php' );	
$wpcvf = new WpWls();

$wls_pessoas 					= $wpdb->prefix . 'wls_pessoas';
$wls_areas 						= $wpdb->prefix . 'wls_areas';
$wls_pessoas_options 			= $wpdb->prefix . 'wls_pessoas_options';

// Registamos a função para correr na ativação do plugin
register_activation_hook( __FILE__, 'wpcvf_install' );

add_action('init', 'wpcvf_create_table' ); #Cria o banco de dados e a pasta onde vai ser salvo os arquivos

add_action( 'admin_head', 'wpcvf_icone' );

add_action('wp_head','wpcvf_ajaxurl');

#shortcode que chama formulario de cadastro
add_shortcode( 'formCadastro', 'wpcvf_cadastrar');
	
#shortcode para listar os currículos
add_shortcode( 'listpessoass', 'wpcvf_listpessoass' );

add_action('admin_menu', 'wpcvf_configuracoes'); #Cria um painel do plugin no administrativo do wordpress

//Adiciona a funcao extra votos aos hooks ajax do WordPress.
add_action('wp_ajax_wpcvf_checkCpf', 'wpcvf_checkCpf');
add_action('wp_ajax_nopriv_wpcvf_checkCpf', 'wpcvf_checkCpf');

add_action('wp_ajax_wpcvf_carregar_cidade', 'wpcvf_carregar_cidade');
add_action('wp_ajax_nopriv_wpcvf_carregar_cidade', 'wpcvf_carregar_cidade');

add_action('wp_ajax_wpcvf_carregar_bairro', 'wpcvf_carregar_bairro');
add_action('wp_ajax_nopriv_wpcvf_carregar_bairro', 'wpcvf_carregar_bairro');

add_action('wp_print_styles', 'wpcvf_estilos'); #Onde é chamado os CSSs do plugin - Visual externo do plugin

register_deactivation_hook( __FILE__, 'wpcvf_unistall' );

function wpcvf_install() {
	define('DISALLOW_FILE_EDIT', true );

  // caso as versões sejam antigas, desativamos
  // o nosso plugin.
  if ( version_compare( PHP_VERSION, '5.2.1', '<' )
    or version_compare( get_bloginfo( 'version' ), '3.3', '<' ) ) {
      deactivate_plugins( basename( __FILE__ ) );
  }
}

#Função que faz instalação do banco e cria a pasta
function wpcvf_create_table() {
  include_once( plugin_dir_path( __FILE__ ) . 'install.php' );
}

function wpcvf_icone(){
	wp_enqueue_style( 'wpvcStyle', plugins_url('css/wp_pessoas_style.css', __FILE__) );
}

function wpcvf_ajaxurl() {
?>

<script type="text/javascript">

/*Função Pai de Mascaras*/
function Mascara(o,f){
		v_obj=o
		v_fun=f
		setTimeout("execmascara()",1)
}

/*Função que Executa os objetos*/
function execmascara(){
		v_obj.value=v_fun(v_obj.value)
}

/*Função que Determina as expressões regulares dos objetos*/
function leech(v){
		v=v.replace(/o/gi,"0")
		v=v.replace(/i/gi,"1")
		v=v.replace(/z/gi,"2")
		v=v.replace(/e/gi,"3")
		v=v.replace(/a/gi,"4")
		v=v.replace(/s/gi,"5")
		v=v.replace(/t/gi,"7")
		return v
}

/*Função que permite apenas numeros*/
function Integer(v){
		return v.replace(/\D/g,"")
}

/*Função que padroniza CPF*/
function Cpf(v){
		v=v.replace(/\D/g,"")                                   
		v=v.replace(/(\d{3})(\d)/,"$1.$2")         
		v=v.replace(/(\d{3})(\d)/,"$1.$2")         
																						 
		v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")

		return v
}

var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}

function wpcvf_configuracoes() {
	
	#Cria um menu dentro do menu options
	add_menu_page('Gestão de Pessoas', 'Gestão de Pessoas', 'manage_options', 'gestao_pessoas', 'gestao_pessoas', '');
	
	#Submenu de INformações.
	add_submenu_page('gestao_pessoas', 'Informações', 'Informações', 'manage_options', 'gestao_pessoas','gestao_pessoas', '');
	
	#Submenu para fazer um novo cadastro
	add_submenu_page('gestao_pessoas', 'Novo cadastro', 'Novo cadastro', 'manage_options', 'formulario-admin', 'wpcvf_formulario_admin');
	
	#Submenu que exibe a lista de Pessoas cadastrados
	add_submenu_page('gestao_pessoas', 'Lista de Pessoas', 'Lista de Pessoas', 'manage_options', 'lista-de-pessoas-admin', 'wpcvf_lista_pessoas_admin');


}

function gestao_pessoas() {
	include_once( plugin_dir_path( __FILE__ ) . 'admin/informativo.php' );
}


function wpcvf_formulario_admin() {
	include_once( plugin_dir_path( __FILE__ ) . 'admin/formulario.php' );
}

function wpcvf_lista_pessoass_admin() {
	include_once( plugin_dir_path( __FILE__ ) . 'admin/lista_pessoas.php' );
}

function wpcvf_estilos() {
	include_once( plugin_dir_path( __FILE__ ) . 'style.php' );
}

function wpcvf_checkCpf()
{
	global $wpdb;
	global $_POST;
		
	$cpf = $_POST['cpf'];

	$sqlCheckCpf = "SELECT cpf FROM ".$wpdb->prefix."wls_pessoas where cpf = '".$cpf."'";
    $queryCheckCpf = $wpdb->get_results( $sqlCheckCpf );
	
	$check = array();
	
	echo sizeof($queryCheckCpf);
	
}

function wpcvf_carregar_cidade(){
	
	global $wpdb;
	global $_POST;
		
	$estado = $_POST['estado'];
	
	$optionCidade = "";
	$optionCidade .= "<option value=\"\">Selecione a cidade</option>";
	
	$sqlCidade = "SELECT cidade FROM ".$wpdb->prefix."wls_pessoas where estado = '".$estado."' group by cidade";
	
	$queryCidade = $wpdb->get_results( $sqlCidade );
	
	foreach($queryCidade as $kC => $vC){
         $optionCidade .= "<option value=\"".$vC->cidade."\">".$vC->cidade."</option>";
	}
	
	echo $optionCidade;
	die();
	
}

function wpcvf_carregar_bairro(){
	
	global $wpdb;
	global $_POST;
		
	$estado = $_POST['estado'];
	$cidade = $_POST['cidade'];
	
	$optionBairro = "";
	$optionBairro .= "<option value=\"\">Selecione o bairro</option>";
	
	$sqlBairro = "SELECT bairro FROM ".$wpdb->prefix."wls_pessoas where estado = '".$estado."' and cidade = '".$cidade."' group by bairro";
	$queryBairro = $wpdb->get_results( $sqlBairro );
	foreach($queryBairro as $kB => $vB){
         $optionBairro .= "<option value=\"".$vB->bairro."\">".$vB->bairro."</option>";
	}
	
	echo $optionBairro;	
	die();
	
}

// function wpcvf_verificarArquivo()
// {
// 	global $wpdb;
// 	global $_POST;
		
// 	$arquivo = $_POST['arquivo'];
	
// 	$array 	= explode("\\", $arquivo);
// 	$ext 	= explode(".", $array[count($array)-1]);
	
// 	#print_r($array);
// 	echo $ext[count($ext)-1];
	
// }

// function wpcvf_editArea()
// {
// 	global $wpdb;
// 	global $_POST;
		
// 	$id 	= $_POST['rel'];
// 	$texto 	= $_POST['texto'];
			
// 	$var = array(
// 			  'area' 		=> $texto,	
// 	);

// 	// Guardar os valores na tabela
// 	$qry = $wpdb->update( $wpdb->prefix."wls_areas", $var, array('id' => $id), $format = null, $where_format = null );
	
// 	if($qry == false && $qry != 0) { 
		
// 		$wpdb->show_errors(); 
		
// 		$wpdb->print_error();
		
// 		exit;
		
// 	}else{	
// 		echo 1;
// 		echo $id;
// 		echo $texto;
// 	}
		
// }

function wpcvf_fecharSessao() {
    session_destroy ();
}

function wpcvf_unistall(){
	include_once( plugin_dir_path( __FILE__ ) . 'uninstall.php' );
}

#funcão que faz o cadastro
function wpcvf_cadastrar() {
	ob_start();
	  
	include_once( plugin_dir_path( __FILE__ ) . 'formulario.php' );
	  
	$formCadastro = ob_get_contents();
	ob_end_clean();
	
	return $formCadastro;
}

function wpcvf_listpessoass() {
	
	ob_start();
	
	include_once( plugin_dir_path( __FILE__ ) . 'lista_pessoas.php' );
	
	$listpessoass = ob_get_contents();
	
	ob_end_clean();
	
	return $listpessoass;
}

?>