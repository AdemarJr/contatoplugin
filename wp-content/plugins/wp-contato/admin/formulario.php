<?php
global $wpdb, $wpcvf, $wls_pessoas, $wls_areas, $wls_pessoas_options;

$id_cadastro = @$_GET['id_cadastro'];

#print_r($_POST);
if(isset($_POST['cadastrar'])){
	include_once( plugin_dir_path( __FILE__ ) . 'include/enviarCadastro.php' );
}

$dado = $wpdb->get_row("SELECT a.*,
								 b.area
						  
						  FROM ".$wls_pessoas." a
						  
							  left join ".$wls_areas." b
								  on a.id_area = b.id
						  
						  where a.id = '".@$id_cadastro."'", ARRAY_A);
						  
wp_enqueue_style('wpcva_bootstrap', plugins_url('../css/bootstrap.min.css', __FILE__));
wp_enqueue_style('wpcva_style', plugins_url('css/style.css', __FILE__));

wp_enqueue_script('jquery');	
wp_enqueue_script('wpcva_bootstrapJS', plugins_url('../js/bootstrap.min.js', __FILE__));
wp_enqueue_script('wpcva_scriptMask', plugins_url('../js/jquery.maskedinput-1.1.4.pack.js', __FILE__));
wp_enqueue_script('wpcva_scriptAreaJS', plugins_url('../js/scriptArea.js', __FILE__));
wp_enqueue_script('wpcva_script', plugins_url('js/script.js', __FILE__));
?>

<div class="container-fluid">
  <?php if($id_cadastro){ ?>
  	<h2>Editar Cadastro</h2>  
  <?php }else{ ?>
  	<h2>Novo Cadastro</h2>  
  <?php } ?>
  
  <?php if(@$_GET['msg']==2){ ?>
  		
        <div style="clear:both;"></div>
    	<div class="alert alert-success" style="text-align:center;">Pessoa Atualizada com sucesso!</div>	

  <?php }elseif(@$_GET['msg']==1){ ?>
  	
    	<div style="clear:both;"></div>
      	<div class="alert alert-success" style="text-align:center;">Pessoa cadastrada com sucesso!</div>	
      
  <?php }?>
  
  <form id="formCadastro" name="formCadastro" method="post" enctype="multipart/form-data">
  	
    <input type="hidden" name="tipo" value="admin" />
    
	<?php if($dado['id']) { ?>
    	<input type="hidden" name="mod" value="edit" />
        <input type="hidden" name="id_cadastro" value="<?php echo $dado['id']; ?>" />
    <?php }else{ ?>
  		<input type="hidden" name="mod" value="new" />
    <?php }?>
    
   
    <div class="container-fluid">
        	<?php /*?><h4><b>Dados Pessoais</b></h4><? */ ?>
            <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label class="control-label">Nome:</label>
                    <div class="controls">
                      <input type="text" class="form-control" name="nome" value="<?php echo @$dado['nome']?>" />
                    </div>
                  </div>
                </div>
            </div>
            <div class='row'>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="control-label">Telefone:</label>
                      <div class="controls">
                        <input type="text" class="form-control" placeholder=" (  )    -     " name="telefone" id="telefone" value="<?php echo @$dado['telefone']?>" />
                      </div>
                    </div>
                  </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                  <label class="control-label">Pais:</label>
                  <div class="controls">
                      <select class="form-control-label" name="pais" id="pais" value="<?php echo @$dado['pais']?>">País:
                      <option>Selecionar Pais </option></select>
                  </div>
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">E-mail:</label>
                    <div class="controls">
                      <input type="text" class="form-control" name="email" value="<?php echo @$dado['email']?>" />
                    </div>
                  </div>
                </div>
            </div>
        	  
              <div style="clear:both;"></div>
              <?php if($dado['pessoas']){ ?>
              	  <input type="hidden" name="pessoasCar" value="<?php echo @$dado['pessoas'];?>" />
                  <div class="container-fluid">
                  	  <label class="control-label">Arquivo já salvo:</label>	
                      <div class="well">
                          <a href="<?php echo content_url( 'uploads/pessoass/'.$dado['pessoas']); ?>" target="_blank" > <?php echo @$dado['pessoas'] ?></a>
                      </div>
                  </div>
                  
              <?php } ?>        
    	</div>
      <div class="row">
          <?php if($id_cadastro){ ?>
            <button type="submit" name="cadastrar" id="cadastrar" class="btn btn-primary">Atualizar</button>  
          <?php }else{ ?>
            <button type="submit" name="cadastrar" id="cadastrar" class="btn btn-primary">Cadastrar</button>
          <?php } ?>
      </div>
	
      
  </form>

</div>
