(function ($) {
	
	//Função que chama o lightbox para exibir as informações completa do cadastro
	$(document).ready(function(){
		$("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',autoplay_slideshow: false});
	});
	
	//Lightbox para a caixa de ediçao do cadastro
	$(document).ready(function(){
		$("a[rel^='edite']").prettyPhoto({animation_speed:'normal',autoplay_slideshow: false});
	});

	//Mascara para o campo CPF
	$(document).ready(function(){
		if($("#cpf").length || $("#cep").length ){
			$("#cpf").mask("999.999.999-99");
			$("#cep").mask("99999-999");
			$("#telefone").mask("(99)9999-9999");
			$("#celular").mask("(99)99999-9999");
		}
	});
	
	//Função que checa se já existe um cadastro com o mesmo CPF
	$(document).ready(function(){
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
				url: 'admin-ajax.php',
				data: 'action=checkCpf&cpf='+ cpf,
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
		$('#cidade').html("<option value=\"\">Carregando...</option>");
		jQuery.ajax({
			type: 'POST',
			url: 'wp-admin/admin-ajax.php',
			data: 'action=carregar_cidade&estado='+ estado,
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
			type: 'POST',
			url: 'wp-admin/admin-ajax.php',
			data: 'action=carregar_bairro&estado=' + estado + '&cidade=' + cidade,
			cache: false,
			success: function(response){
				//alert(response);
				$('#bairro').removeAttr('disabled');
				$('#bairro').html(response);
			}
		});
	}
	
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
	
	function removeCampo() { 
		$(".removerFormação").unbind("click"); 
		$(".removerFormação").bind("click", function () { 
			i=0; 
			$("div.cursoacademico").each(function () { 
				i++; 
			}); 
			
			if (i>1) { 
				$(this).parent().remove(); 
			} 
		}); 
	} 
	
	removeCampo(); 
	
	$(".adicionarNovaFormacao").click(function () { 
		novoCampo = $("div.cursoacademico:first").clone();
		novoCampo.find("input").val(""); 
		novoCampo.insertAfter("div.cursoacademico:last"); removeCampo(); 
	});
	
	

	function removeCampo2() { 
		$(".removerExperiencia").unbind("click"); 
		$(".removerExperiencia").bind("click", function () { 
			i=0; 
			
			$("div.experienciaprofissional").each(function () { 
				i++; 
			}); 
			
			if (i>1) { 
				$(this).parent().remove(); 
			} 
		}); 
	} 

	removeCampo2(); 

	$(".adicionarNovaExperiencia").click(function () { 
		novoCampo = $("div.experienciaprofissional:first").clone();
		novoCampo.find("input").val(""); 
		novoCampo.insertAfter("div.experienciaprofissional:last"); removeCampo2(); 
	});

	

	function removeCampo3() { 
		$(".removerCursoPalestra").unbind("click"); 
		$(".removerCursoPalestra").bind("click", function () { 
			i=0; 
			
			$("div.cursospalestras").each(function () { 
				i++; 
			}); 
			
			if (i>1) { 
				$(this).parent().remove(); 
			} 
		}); 
	} 

	removeCampo3(); 

	$(".adicionarNovaCursoPalestra").click(function () { 
		novoCampo = $("div.cursospalestras:first").clone();
		novoCampo.find("input").val(""); 
		novoCampo.insertAfter("div.cursospalestras:last"); removeCampo3(); 
	});

	
}(jQuery));