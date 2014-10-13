<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Device_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function getDimensions($id)
    {        
        $this->db->select('a.user_id,a.width,a.height,a.dimension_name,b.file_id,b.dimension_id');
        $this->db->from('splash_dimension a');
        $this->db->join('splash_dimension_image b','a.id=b.dimension_id','left');
        $this->db->where('a.user_id',$id);
        $this->db->where('a.status',1);
        $query = $this->db->get();
       // echo $this->db->last_query();
        return $query->result();
    }
    
    function checkSplash($id)
    {
        $this->db->select('a.user_id,a.width,a.height,a.dimension_name,b.file_id,b.dimension_id,c.relative_path,c.absolute_path,d.id as splash_id,c1.absolute_path as originalFilePath');
        $this->db->from('splash_dimension a');
        $this->db->join('splash_dimension_image b','a.id=b.dimension_id','left');       
        $this->db->join('files c','b.file_id=c.id','left');
        $this->db->join('splash_screen d','b.splash_id=d.id','left');
        $this->db->join('files c1','d.file_id=c1.id','left');            // code added to get actual image reference
        $this->db->where('a.user_id',$id);
        $query = $this->db->get();
        return $query->result();
    }
    

    
    function saveSplashDimension($data)
    {
       $this->db->set('created','NOW()',FALSE);
       $id = $this->db->insert('splash_dimension',$data);
        return $this->db->insert_id();;
    }
    
    function saveSplashDimensionImage($data)
    {
        $this->db->set('created','NOW()',FALSE);
        $id = $this->db->insert('splash_dimension_image',$data);
        return $this->db->insert_id();
    }

    function delete_splash_screen($id)
    {
        $this->db->delete('splash_screen', array('user_id'=>$id));
        return true;
    }
    
    function delete_splash_dimension($id)
    {
        $this->db->delete('splash_dimension', array('user_id'=>$id));
        return true;
    }
    
    function delete_splash_dimension_image($id)
    {
        $this->db->delete('splash_dimension_image', array('dimension_id'=>$id));
        return true;
    }
    
    function delete_file($id){
        $this->db->delete('files', array('id' => $id));
        return true;
    }
    
    
    function _saveSplash($data, $splash_id){
        if(isset($data['filename'])){
            ###inserting file detail data in files table and return id###
            $file['name'] = $data['filename'];
            $file['type'] = $data['type'];
            $file['minetype'] = $data['minetype'];
            $file['relative_path'] = $data['relative_path'];
            $file['absolute_path'] = $data['absolute_path'];
            $file['status'] = $data['status'];
            $file['uid'] = $data['uid'];
            $file['info'] = $data['info'];
            $this->db->set($file);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('files');
            $file_id = $this->db->insert_id();
            
            ###inserting data in videos table with contents_id and file_id###
            
            //-- splash screen table insert --//
            if($file_id>0){
                $data_splash['file_id'] = $file_id;
                $data_splash['user_id'] = $data['uid'];   
                $data_splash['status'] = 1;
                if($splash_id){
                    $this->Device_model->_saveSplashScreen($data_splash, $splash_id);				     
                }else{
                   
                    $splash_id = $this->Device_model->_saveSplashScreen($data_splash);                                    
                }
            }
        }
        return $splash_id;
    }
    
    function _saveSplashScreen($data, $splash_id=false) {
        if(isset($splash_id) && $splash_id !=''){
            $this->db->where(array('id'=> $splash_id, 'user_id' => $data['user_id']));
            $id = $this->db->update('splash_screen', $data);
            
        } else {
            $this->db->set('created','NOW()',FALSE);
            $id = $this->db->insert('splash_screen',$data);
            return $this->db->insert_id();;
        }
    }
    
    function _saveDimension($data){
        $splash_id = $data['splash_id'];
        if(isset($data['filename'])){
            ###inserting file detail data in files table and return id###
            $file['name'] = $data['filename'];
            $file['type'] = $data['type'];
            $file['minetype'] = $data['minetype'];
            $file['relative_path'] = $data['relative_path'];
            $file['absolute_path'] = $data['absolute_path'];
            $file['status'] = $data['status'];
            $file['uid'] = $data['uid'];
            $file['info'] = $data['info'];
            $this->db->set($file);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('files');
            $file_id = $this->db->insert_id();
            
            if ($file_id>0) {
                //-- insert splash dimenstions --//
                $splash_dimension['user_id'] = $data['uid'];
                $splash_dimension['width'] = $data['width'];
                $splash_dimension['height'] = $data['height'];
                $splash_dimension['dimension_name'] = $data['key'];
                $splash_dimension['status'] = 1;
                $dimension_id = $this->saveSplashDimension($splash_dimension);
                    
                //-- insert dimension image --// 
                if($dimension_id>0){
                    $dimension_image['splash_id'] = $splash_id;
                    $dimension_image['dimension_id'] = $dimension_id;
                    $dimension_image['file_id'] = $file_id;
                    $dimension_image['status'] = 1;
                    $this->Device_model->saveSplashDimensionImage($dimension_image);
               }
                    //------------------------------//
            }
        }
    }

}