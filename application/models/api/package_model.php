<?php
class package_model extends CI_Model{
       
    function __construct()
    {
       parent::__construct();	
       $this->load->database();
       $parent=array();
    }
    
    
    function getpackagelist($data)
    {
       if($data['device'] !=''){
	 $this->db->where('fl.flavor_name',$data['device']);
	}
	
        $this->db->select('pk.name as package_name,pk.id as package_id,c.title,c.description,c.type,c.content_type,fl.device_name,fv.path as video_path,ct.id as category_id,ct.category as category_name,v.duration,v.views as total_view, c.id as content_id, f.name as thumbnail_path');	
	$this->db->from('package pk');	
	$this->db->join('package_video pkv','pkv.package_id=pk.id' ,'inner');
	$this->db->join('contents c','pkv.content_id=c.id','INNER');
	$this->db->join('categories ct','c.category=ct.id','INNER');
	$this->db->join('videos v','v.content_id=c.id' ,'LEFT');
	$this->db->join('video_thumbnails vt','vt.content_id=c.id' ,'LEFT');
	$this->db->join('files f','vt.file_id=f.id and vt.default_thumbnail=1' ,'LEFT');
	$this->db->join('flavored_video fv','fv.content_id = c.id' ,'LEFT');
	$this->db->join('video_flavors vf','vf.id = fv.flavor_id' ,'LEFT');
	$this->db->join('flavors fl','vf.flavor_id = fl.id' ,'LEFT');	
	$this->db->where('pk.uid',$this->owner_id);
	$this->db->where('pk.status',1);	
        $this->db->order_by('pk.id asc');
        $query = $this->db->get();
	//echo '<pre>'. $this->db->last_query();
	return $query->result();
    }
    
}
    
    ?>