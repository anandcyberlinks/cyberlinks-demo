<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'controllers/apis/apis.php';

class Category extends Apis{
    
    function getlist_get(){
        $qString = $this->get();
        $total_query = sprintf('select count(id) as tot from categories where u_id = %d',$this->app->id);
        $dataset_count = $this->db->query($total_query)->result();
        $dataset_count = isset($dataset_count[0]->tot) ? $dataset_count[0]->tot : 0;
        
        $query = sprintf('select
                            c.id,c.category,c.parent_id,
                            c.description,c.color,
                            sum(if(contents.id > 0,1,0)) as total,
                            f.relative_path
                            from categories c
                            left join contents on contents.category = c.id
                            left join files f on f.id = c.file_id
                            where c.u_id  = "%d" group by c.id ',$this->app->id);

        $dataset = $this->db->query($query)->result();
        foreach($dataset as $key=>$val){
            if(file_exists($val->relative_path)){
                $base_url = strpos('http://',$val->relative_path) > 0 ? '' : base_url();
                $dataset[$key]->relative_path = $base_url.$val->relative_path;
                $filename = end(explode('/',$val->relative_path));
                $dataset[$key]->thumbs = array('small'=>sprintf('%s',base_url().CATEGORY_SMALL_PATH.$filename),
                                         'medium'=>sprintf('%s',base_url().CATEGORY_MEDIUM_PATH.$filename),
                                         'large'=>sprintf('%s',base_url().CATEGORY_LARGE_PATH.$filename));
                
            }
        }
        $response = array('tr'=>$dataset_count,'result'=>$dataset);
        $this->response($response);
    }
}