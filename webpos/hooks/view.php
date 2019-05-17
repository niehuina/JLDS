<?php
 
hook::add('view.after_render',function(&$data){
		if(config('app.minify_html')){
			$data = cs\minify::html($data);
		}
 		
 		$js = minify_js();
 		$css = minify_css();
 		if($css && $js){
 			echo preg_replace_callback('|.*</head>|',function()use($js,$css){
			
				return $css."\n".$js."\n</head>";
				
			}, $data);
 		}else if($css){
 			echo preg_replace_callback('|.*</head>|',function()use($css){ 
				return $css."\n</head>";
				
			}, $data);
 		}else if($js){
 			echo preg_replace_callback('|.*</head>|',function()use($js){
			
				return $js."\n</head>";
				
			}, $data);
 		}

 		
 		 

 		
 		
});