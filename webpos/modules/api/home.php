<?php 
namespace modules\api;
use Crada\Apidoc\Builder;
use Crada\Apidoc\Exception;
class home{
	public function index(){
			$classes = array(
			    'cs\acl',
			   	'cs\TestClass',
			);

			$output_dir  = BASE.'/apidocs';
			$output_file = 'index.html'; // defaults to index.html

			try {
			    $builder = new Builder($classes, $output_dir, 'Api开发文档', $output_file);
			    $builder->generate();
			} catch (Exception $e) {
			    echo 'There was an error generating the documentation: ', $e->getMessage();
			}
	}
}