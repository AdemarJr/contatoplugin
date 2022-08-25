<?php

wp_enqueue_style("wpcvf_lightboxcssa", plugins_url('css/wpcvf_lightbox.css', __FILE__) );
wp_enqueue_style("wpcvf_style", plugins_url('css/wp_curriculo_style.css', __FILE__));
wp_enqueue_style('wpcvf_bootstrap', plugins_url('css/bootstrap.min.css', __FILE__));





wp_enqueue_script('jquery');
wp_enqueue_script('wpcvf_script', plugins_url('js/script.js', __FILE__));
wp_enqueue_script('wpcvf_bootstrapJSa', plugins_url('js/bootstrap.min.js', __FILE__));
wp_enqueue_script('wpcvf_scriptMask', plugins_url('js/jquery.maskedinput-1.1.4.pack.js', __FILE__));
#wp_enqueue_script('scriptAreaJS', plugins_url('js/scriptArea.js', __FILE__));


?>