<?php

global $wpdb, $wpcvf, $wls_pessoas, $wls_areas, $wls_pessoas_options;

$pg = $_GET['pg'];

$buscar = $_POST['buscar'];

$msg = $_GET['msg'];

$where = "";

if($buscar){
	$where .= " and (nome LIKE  '%".$buscar."%' or descricao LIKE '%".$buscar."%')";
}


########### Função para excluir registro
if(isset($_POST['excl'])){
  $wpcvf->deleteTable($_POST['excl'], $wls_pessoas );
}

//######### INICIO Paginação
$numreg = 20; // Quantos registros por página vai ser mostrado
if (!isset($pg)) {
	$pg = 0;
}
$inicial = $pg * $numreg;

//######### FIM dados Paginação

$sql = "SELECT a.*,
			   b.area
		
		FROM ".$wls_pessoas." a
		
			left join ".$wls_areas." b
				on a.id_area = b.id
		
		where 1=1 $where order by a.nome asc LIMIT $inicial, $numreg ";
		
$query      = $wpdb->get_results( $sql );
$rowsCurr   = $wpdb->num_rows;

$sqlRow = "SELECT a.*,
				  b.area
		   
		   FROM ".$wls_pessoas." a
		   
		   		left join ".$wls_areas." b
					on a.id_area = b.id
		   
		   where 1=1 $where order by a.nome asc";
		   
$queryRow = $wpdb->get_results( $sqlRow );
$quantreg = $wpdb->num_rows; // Quantidade de registros pra paginação

wp_enqueue_style('wpcva_bootstrap', plugins_url('../css/bootstrap.min.css', __FILE__));
wp_enqueue_style('wpcva_styleAdminP', plugins_url('css/style.css', __FILE__));
wp_enqueue_style("wpcva_styleP", plugins_url('../css/wp_pessoas_style.css', __FILE__));

wp_enqueue_script('jquery');

wp_enqueue_script('wpcva_bootstrapAdminJS', plugins_url('../js/bootstrap.min.js', __FILE__));
wp_enqueue_script('wpcva_script', plugins_url('js/script.js', __FILE__));
		
?>

    <div class="container-fluid">
      <h2 style="float:left;">Listagem de Pessoas</h2>
      
      <a class="bt_novo" href="?page=formulario-admin">Novo cadastro</a>
      
      <div style="clear:both;"></div>
      
  	  <?php if(@$_GET['msg']==2){ ?>

            <div class="alert alert-success" style="text-align:center;">Atualizado com sucesso!</div>	
    
  	  <?php }elseif(@$_GET['msg']==3){ ?>

            <div class="alert alert-success" style="text-align:center;">Registro deletado com sucesso!</div>	
          
      <?php }?>
      
      <div class="link-del-reg">
      	<img src="<?php echo plugins_url('../img/cross.png', __FILE__) ?>" width="16" height="16" alt="Exportar emails em XML." style="margin-bottom: 1px;" />
        <a href="javascript:registros.submit();">Excluir registro</a>
      </div>      
      
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th width="100" style="text-align:center;">Pais</th>
            <th width="60" style="text-align:center;">Editar</th>
            <th width="30" style="text-align:center;"><input type="checkbox" id="checkAll" /></th>
          </tr>
        </thead>
        <tbody>
        <form action="?page=lista-de-pessoas-admin" name="registros" id="registros" method="post">
              <tr>
                <td><?php echo $v->nome ?></td>
                       
                        <h3><center><?php echo $v->nome ?></center></h3>                        
                        <!-- <p>
                            <strong>Nome:</strong> <?php echo $v->nome ?><br />
                            <strong>Telefone:</strong> <?php echo $v->telefone ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <strong>E-mail:</strong> <?php echo $v->email ?><br />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </p> -->
                        <p>
                        <?php echo $v->pais?><br />
                        </p>
                <td style="text-align:center;"><a href="mailto:<?php echo $v->email?>" target="_blank"><img src="<?php echo plugins_url('../img/email.png', __FILE__) ?>" width="16" height="16" alt="<?php echo $v->email?>" /></a></td>
                
                <td style="text-align:center;">
                  <?php if($v->pessoas != ""){ ?>
                            <a href="<?php echo content_url( 'uploads/pessoass/'.$v->pessoas); ?>" target="_blank" > <img src="<?php echo plugins_url('../img/page_white_text.png', __FILE__) ?>" width="16" height="16" alt="<?php echo $v->pessoas?>" /></a>
                  <?php  }else{ ?>
                            -
                  <?php  } ?>
                  
                </td>
                
                <td style="text-align:center;">
                	
                    <a href="?page=formulario-admin&id_cadastro=<?php echo $v->id?>" >
                      <img src="<?php echo plugins_url('../img/user_edit.png', __FILE__)?>" width="16" height="16" alt="<?php echo $v->nome?>" style="padding:0;" /></a><br />
                    
                </td>
                
                <td style="text-align:center;">                    
                    <input type="checkbox" name="excl[]" value="<?php echo $v->id ?>" class="check" />
                </td>
                
              </tr>
        </form>  
        </tbody>
      </table>
      
      <span style="position: relative; top: -15px;"><?php echo 'Existe <strong>' . $rowsCurr . '</strong> ' . ($rowsCurr<=1?'pessoa cadastrada.':'pessoas cadastradas.'); ?></span>

      <div style="clear:both;">

        <?php include( plugin_dir_path( __FILE__ ) . '../classes/paginacao2.php' ); ?>
      
      </div>
    </div>
	<div id="black_overlay"></div>