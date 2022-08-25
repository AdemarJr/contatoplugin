<?php

global $wpdb, $wpcvf, $wls_pessoas, $wls_areas, $wls_pessoas_options;

if(isset($_POST['cadastrar'])){
	include_once( plugin_dir_path( __FILE__ ) . 'admin/include/enviarCadastro.php' );
}

$sqlF = "SELECT * FROM ".$wls_pessoas." where id = '".@$_SESSION['id_cadastro']."'";
			
$queryF = $wpdb->get_results( $sqlF, ARRAY_A );
foreach($queryF as $kF => $vF){
	$dadosF = $vF;
}

?>
	
<div class="container-fluid">

  <?php if(@$_GET['msg']==1){ ?>
  
  	  <div class="alert alert-success" style="text-align:center; color: #3c763d;">pessoas cadastrado com sucesso!</div>	
      
  <?php }elseif(@$_GET['msg']==2){ ?>
  
  	  <div class="alert alert-success" style="text-align:center; color: #3c763d;">pessoas Atualizado com sucesso!</div>	
  
  <?php }elseif(@$_GET['msg']==3){ ?>
      
      <div class="alert alert-success" style="text-align:center; color: #3c763d;">Conta excluido com sucesso!</div>	
      
  <?php }?>
  
  <form name="wp-pessoas-cadastro" method="post" enctype="multipart/form-data" onsubmit="">
  	<input type="hidden" name="tipo" value="site" />
    
	<?php if(@$_SESSION['logado']==1) { ?>
    	<input type="hidden" name="mod" value="edit" />
        <input type="hidden" name="id_cadastro" value="<?php echo @$dadosF['id']; ?>" />
        <div class="form-group">
          <div class="controls">
            <span style="font-size:14px;">Excluir a conta</span> <input type="checkbox" name="excluirConta" value="1" style="margin-top:-2px;"> 
          </div>
        </div>
    <?php }else{ ?>
  		<input type="hidden" name="mod" value="new" />
        <input type="hidden" name="excluirConta" value="0" />
    <?php }?>
    
    <div class="form-group">
      <label class="control-label">Nome:</label>
      <div class="controls">
        <input type="text" name="nome" class="form-control" value="<?php echo @$dadosF['nome']?>" > 
      </div>
    </div>
 
    <div class="row">
        <div class="col-md-5">
        	<div class="form-group">
              <label class="control-label">Telefone:</label>
              <div class="controls">
                <input type="text" name="telefone" id="telefone" value="<?php echo @$dadosF['telefone']?>" class="form-control"> 
              </div>
            </div>
        </div>
 
	
    <div class="row">
    	<div class="col-md-5">
        	<div class="form-group">
              <label class="control-label">CPF:</label>
              <div class="controls">
              
              	<div class="row">
                	  <div class="col-md-7">
                    	<input type="text" name="cpf" id="cpf" value="<?php echo @$dadosF['cpf']?>" class="form-control">
                    </div>
                    <div class="col-md-1">
                    	<img id="tick" src="<?php echo plugins_url('img/tick.png', __FILE__) ?>" class="pull-left" width="16" height="16"/>
                		<img id="cross" src="<?php echo plugins_url('img/cross.png', __FILE__) ?>" class="pull-left" width="16" height="16"/>
                    </div>
                </div>
               
                <span id="msgCpf">CPF já está cadastrado.</span>
              </div>
            </div>
        </div>
      </div>
   
       <div class="col-md-2">
        	<div class="form-group estado">
              <label class="control-label">Pais:</label>
              <div class="controls">
                <input type="text" name="estado" id="estado" value="<?php echo @$dadosF['pais']?>" class="form-control" /> 
              </div>
            </div>
        </div>
    </div>
   
    <div style="clear:both;"></div>
  
	<?php if($dadosF['id']){ ?>
      <button type="submit"  id="cadastrar" name="cadastrar" class="btn btn-primary">Atualizar</button>
    <?php }else{ ?>
      <button type="submit"  id="cadastrar" name="cadastrar" class="btn btn-primary">Cadastrar</button>
    <?php } ?>
      
  </form>
</div>

<?php wp_enqueue_script('scriptAreaJS', plugins_url('js/scriptArea.js', __FILE__)); ?>