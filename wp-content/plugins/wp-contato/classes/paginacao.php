<div class="wp-curriculo-pagination" >
      <ul class="unstyled" style="box-shadow:none !important; -webkit-box-shadow:none !important;">
<?php
$quant_pg = ceil($quantreg/$numreg);
$quant_pg++;

$server = $_SERVER['SERVER_NAME']; 
$endereco = $_SERVER ['REQUEST_URI'];

$parametro = "?";

$page = "page=".$_GET['page'];
$page_id = "page_id=".$_GET['page_id'];
$pg = $_GET['pg'];

if($_GET['page']){
	$parametro .= $page."&";
}

if($_GET['page_id']){
	$parametro .= $page_id."&";
}

if($_GET['pg']){
	$parametro .= $pg."&";
}


$active = $i_pg2==@$pg?"class=\" active \"":"";

// Verifica se esta na primeira página, se nao estiver ele libera o link para anterior
if ( $pg > 0) {
	
          
        
echo "<li><a href='".$PHP_SELF.@$parametro."pg=".($pg-1) ."'class=pg><b>&laquo; anterior</b></a></li>";
} else {
echo "<li class=\"active\"><a href='#'><b>&laquo; anterior</b></a></li>";
}

// Faz aparecer os numeros das página entre o ANTERIOR e PROXIMO
for($i_pg=1;$i_pg<$quant_pg;$i_pg++) {
// Verifica se a página que o navegante esta e retira o link do número para identificar visualmente
if ($pg == ($i_pg-1)) {
echo "<li class=\"active\">&nbsp;<a href='#'><b>$i_pg</b></a>&nbsp;</li>";
} else {
$i_pg2 = $i_pg-1;
echo "<li>&nbsp;<a href=\" ".$PHP_SELF.@$parametro."&pg=$i_pg2 \"><b>$i_pg</b></a>&nbsp;</li>";
}
}

// Verifica se esta na ultima página, se nao estiver ele libera o link para próxima
if (($pg+2) < $quant_pg) {
echo "<li><a href=".$PHP_SELF.@$parametro."&pg=".($pg+1)."><b>próximo &raquo;</b></a></li>";
} else {
echo "<li class=\"active\"><a href='#'><b>próximo &raquo;</b></a></li>";
}
?>
</ul>
    </div>
    