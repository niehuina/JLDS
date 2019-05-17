<?php  namespace modules\doc;
use file;
use str;
use models\yf_goods_common;
/*
 * @desc 文件上传
 */
class upload{
    /*
     * @desc 上传图片
     */
    public function doupload(){
        /**
         * This is just an example of how a file could be processed from the
         * upload script. It should be tailored to your own requirements.
         */
        // Only accept files with these extensions
        $whitelist = array('jpg','jpeg', 'png', 'gif');
        $name      = null;
        $error     = 'No file uploaded.';
        if (isset($_FILES)) {
            if (isset($_FILES['file'])) {

                $tmp_name = $_FILES['file']['tmp_name'];
                $name     = basename($_FILES['file']['name']);
                $error    = $_FILES['file']['error'];
                $uri = '/upload/'.date('Y').'/'.date('m');
                $dir = WEB.$uri;
                if(!is_dir($dir)) mkdir($dir,0777,true);
                $ns = '/'.str::rand().'.'.file::ext($name);
                $new = $dir. $ns;
                $file_url = $uri.$ns;
                if ($error === UPLOAD_ERR_OK) {
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    if (!in_array($extension, $whitelist)) {
                        $error = 'Invalid file type uploaded.';
                    } else {
                        move_uploaded_file($tmp_name, $new);

                    }
                }


            }
        }

        echo json_encode(array(
            'name'  => base_url().$file_url,
            'error' => $error,
        ));


        die();
    }
    /*
     * @desc 删除图片
     */
    public function delete(){
        $id = get_data('id');
        $img = substr(post_data('img'),18);

        $query = yf_goods_common::where('id',$id);
        $model = $query->first();

        if($model && $model->file){
            @unlink(WEB.$model->file);
            $query->update(['file'=>'']);

        }else{
            @unlink(WEB.$img);
        }
    }

}




