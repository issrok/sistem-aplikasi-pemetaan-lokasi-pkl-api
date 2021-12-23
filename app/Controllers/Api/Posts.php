<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends REST_Controller {

	public function index_get($name='')	{
        //
        // dengan parameter => ?name=namasaya
        // $name = $this->get('name');
        //
		$this->response(array(
            'data' => 'get '.$name
        ));
    }
    
	public function index_post()	{
        $name = $this->post('name');
		$this->response(array(
            'data' => 'post '. $name
        ));
    }
    
    public function index_put()	{
        $name = $this->put('name');
		$this->response(array(
            'data' => 'put '.$name
        ));
    }
    
    public function index_patch(){        
        $name = $this->patch('name');
		$this->response(array(
            'data' => 'patch '.$name
        ));
    }
    
    // parameter $name ==>  [api]/index/$name
    // from url param
    public function index_delete($name=''){     
        // // dengan parameter => ?name=namasay
        // $name = $this->delete('name');
		$this->response(array(
            'data' => 'delete '.$name
        ));
	}
}
