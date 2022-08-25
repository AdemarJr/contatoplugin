(function ($) {
	
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
			$( '.wpcvcontent' ).fadeIn( "fast", function() {
    			// Animation complete
  			});
  		});
	});
	
}(jQuery));

/*
function wpcvlightbox(wpcvboxcontent, numtotal){
		
		//alert(wpcvboxcontent);

		for(var i=0; i < numtotal; i++){
			$("#curriculo_"+i).css("display","none");

		}
		
		$('#black_overlay').css('display','block');
		//$("#"+wpcvboxcontent).css("display","block");
		
		
		$( "#"+wpcvboxcontent ).animate({
		  width: [ "toggle", "swing" ],
		  height: [ "toggle", "swing" ],
		  opacity: "toggle"
		}, 500, "swing", function() {
    		//$( '.wpcvcontent' ).css( 'display', 'block' );
			$( '.wpcvcontent' ).fadeIn( "fast", function() {
    			// Animation complete
  			});
  		});
		

}*/