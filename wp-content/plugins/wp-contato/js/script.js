(function ($) {
	$(document).ready(function(){

		


		/*alert($(".entry-content").width());*/

		if($(".entry-content").width() <= 480){
			/*alert("sim");*/
			$(".col-md-4").css('width', '27%');
			$(".col-md-5").css('width', '43.3%');
			$(".col-md-8").css('width', '70%');
		}else if($(".entry-content").width() <= 584){
			$(".col-md-4").css('width', '28%');
			$(".col-md-5").css('width', '44.7%');
			$(".col-md-8").css('width', '72.3%');
			
		}else if($(".entry-content").width() <= 610){
			$(".col-md-4").css('width', '33.3%');
			$(".col-md-5").css('width', '50%');
			$(".col-md-8").css('width', '83.3%');
			
		}else if($(".entry-content").width() <= 640){
			$(".col-md-4").css('width', '28.5%');
			$(".col-md-5").css('width', '45%');
			$(".col-md-8").css('width', '73.3%');
		}
		else if($(".entry-content").width() <= 968){
			$(".col-md-4").css('width', '30.2%');
			$(".col-md-5").css('width', '46.8%');
			$(".col-md-8").css('width', '77%');
		}
		
		$("span#wpcvf_linkEsqueceu").click(function(){
					
			$("#wpcvf_formLoginSenha").fadeOut('slow');
			$("#wpcvf_formEsqueceuSenha").delay( 430 ).fadeIn('slow');
			
		});
		
		$("span#wpcvf_linkLogin").click(function(){
			
			$("#wpcvf_formEsqueceuSenha").fadeOut('slow');
			$("#wpcvf_formLoginSenha").delay( 430 ).fadeIn('slow');
			
		});
			
		//Mascara para o campo CPF
		if($("#cpf").length || $("#cep").length ){

			$("#cpf").mask("999.999.999-99");
			$("#cep").mask("99999-999");
			$("#telefone").mask("(99)9999-9999");
			$("#idade").mask("99/99/9999");
		}
		
		function str_replace(busca,subs,valor){
            var ret=valor;
            var pos=ret.indexOf(busca);
            while(pos!=-1){
                ret=ret.substring(0,pos)+subs+ret.substring(pos+busca.length,ret.length);
                pos=ret.indexOf(busca);
            }
            return ret;
        }
		function mascara(valor,masc){
		var res=valor,mas=str_replace("?","",str_replace("9","",masc));
		for(var i=0;i<mas.length;i++){
			res=str_replace(mas.charAt(i),"",res);
			mas=str_replace(mas.charAt(i),"",mas);
		}
		var ret="";
		for(var i=0;i<masc.length&&res!="";i++){
			switch(masc.charAt(i)){
				case"?":
					ret+=res.charAt(0);
					res=res.substring(1,res.length);
					break;
				case"9":
					while(res!=""&&(res.charCodeAt(0)>57||res.charCodeAt(0)<48))res=res.substring(1,res.length);
					if(res!=""){
						ret+=res.charAt(0);
						res=res.substring(1,res.length);
					}
					break;
				default:
					ret+=masc.charAt(i);
				}
			}
			return ret;
		}

		$("#celular").keyup(function(){
			if($(this).val().length <= 13)
				$(this).val( mascara($(this).val(), '(99)9999-9999') );
			else
				$(this).val( mascara($(this).val(), '(99)99999-9999') );
		})

	
		//Função que checa se já existe um cadastro com o mesmo CPF

		$('#cpf').keyup(cpf_check); 
	});
	
	function cpf_check(){

		var cpf = $('#cpf').val();
		if(cpf == '' || cpf.length < 14){
			$('#cpf').css('border', '3px #CCC solid');
			$('#tick').hide();
		}else{
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: 'action=wpcvf_checkCpf&cpf='+ cpf,
				cache: false,
				success: function(response){
					//alert(response);
					if(response >= 10){

						$('#cpf').css('border', '3px #C33 solid');
						$('#tick').hide();
						$('#cross').fadeIn();
						$('#msgCpf').fadeIn();
						$('#cadastrar').prop('disabled', true);
						
						
					}else{
						
						$('#cpf').css('border', '3px #090 solid');
						$('#cross').hide();
						$('#msgCpf').hide();
						$('#tick').fadeIn();
						$('#cadastrar').prop('disabled', false);
					}
				}
			});
		}
	
	}
	
	
	
	//Preenche o o endereço com o cep preenchido 
	$(document).ready(function(){
		$('#cep').keyup(getEndereco); 
		
		$('input#curriculo').change(function(){
		
			var arquivo = $('input#curriculo').val();
			//var id_registro = $('#id_registro').val();
			//var nomeUser = $('#nomeUser_'+num).html();
			$("#msgFile").fadeOut('slow');
					
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: 'action=wpcvf_verificarArquivo&arquivo=' + arquivo,
					cache: true,
					success: function(response){
						
						//alert(response.substring(0,response.length - 1));
						var retorno = response.substring(0,response.length - 1)
						response = retorno;
						$("#ext").html(response);
						if(response=="pdf" || response=="doc" || response=="docx"){
							//alert("correto");
						}else{
							//alert("errado");
							$("#msgFile").fadeIn('slow');
						}
						
						
					}
				});
			
		});
		
	});
	
	
	
	function getEndereco() {
		// Se o campo CEP não estiver vazio
		if($.trim($("#cep").val()) != ""){
			/*
			Para conectar no serviço e executar o json, precisamos usar a função
			getScript do jQuery, o getScript e o dataType:"jsonp" conseguem fazer o cross-domain, os outros
			dataTypes não possibilitam esta interação entre domínios diferentes
			Estou chamando a url do serviço passando o parâmetro "formato=javascript" e o CEP digitado no formulário
			http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val()
			*/
			$.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val(), function(){
				// o getScript dá um eval no script, então é só ler!
				//Se o resultado for igual a 1
				if (resultadoCEP["tipo_logradouro"] != '') {
					if (resultadoCEP["resultado"]) {
						// troca o valor dos elementos
						$("#rua").val(unescape(resultadoCEP["tipo_logradouro"]) + " " + unescape(resultadoCEP["logradouro"]));
						$("#bairro").val(unescape(resultadoCEP["bairro"]));
						$("#cidade").val(unescape(resultadoCEP["cidade"]));
						$("#estado").val(unescape(resultadoCEP["uf"]));
						$("#numero").focus();
					}
				}
			});
		}
	}
	
	//carregar o bairro baseando na cidade que foi escolhida
	$(document).ready(function(){
		
		$('#estado').change(carregar_cidade);
		$('#cidade').change(carregar_bairro); 
		
	});
	
	function carregar_cidade(){
		var estado = $('#estado').val();
		//alert(estado);
		$('#cidade').html("<option value=\"\">Carregando...</option>");
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: 'action=wpcvf_carregar_cidade&estado='+ estado,
			cache: false,
			success: function(response){
				//alert(response);
				$('#cidade').removeAttr('disabled');
				$('#cidade').html(response);
			}
		});
	}
	
	function carregar_bairro(){
		var estado = $('#estado').val();
		var cidade = $('#cidade').val();
		$('#bairro').html("<option value=\"\">Carregando...</option>");
		jQuery.ajax({
			type: 	'POST',
			url:ajaxurl,
			data: 	'action=wpcvf_carregar_bairro&estado=' + estado + '&cidade=' + cidade,
			cache: 	false,
			success: function(response){
				//alert(response);
				$('#bairro').removeAttr('disabled');
				$('#bairro').html(response);
			}
		});
	}
	
	$("#black_overlay").click(function(){
		$('.wpcvf_lightbox_content').css('display','none');
		$('#black_overlay').css('display','none');
	})
	
	$("a.abrirDescricao").click(function(){
		var rel = $(this).attr('rel');
		
		$('#black_overlay').css('display','block');
		//$("#"+wpcvboxcontent).css("display","block");
		
		$( "#curriculo_"+rel ).animate({
//		  width: [ "toggle", "swing" ],
//		  height: [ "toggle", "swing" ],
		  opacity: "toggle"
		}, 500, "swing", function() {
			//$( '.wpcvcontent' ).css( 'display', 'block' );
			$( "#curriculo_"+rel ).fadeIn( "fast", function() {
				// Animation complete
			});
		});
	});
	
	
	
	
	
	function dirname (path) {
	  // http://kevin.vanzonneveld.net
	  // +   original by: Ozh
	  // +   improved by: XoraX (http://www.xorax.info)
	  // *     example 1: dirname('/etc/passwd');
	  // *     returns 1: '/etc'
	  // *     example 2: dirname('c:/Temp/x');
	  // *     returns 2: 'c:/Temp'
	  // *     example 3: dirname('/dir/test/');
	  // *     returns 3: '/dir'
	  return path.replace(/\\/g, '/').replace(/\/[^\/]*\/?$/, '');
	}
	
	$('#tipo_envio').change(function(){
		
		if($('#tipo_envio').val() == 2){
			
			$('#personalizarSMTP').fadeIn('slow');
			
		}else{
			
			$('#personalizarSMTP').fadeOut('slow');
			
		}
	});
	
	$('#autenticacao').click(function(){
		if($("#autenticacao").is(':checked')){
			$('#usuarioSenha').fadeIn('slow');
		}else{
			$('#usuarioSenha').fadeOut('slow');
		}
	});
	
	
	

}(jQuery));

