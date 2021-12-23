<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        $this->authModel = new \App\Models\AuthModel;
        $this->aboutModel = new \App\Models\AboutModel;
        $this->pathImage = str_replace('/public','',base_url()).'/media/images/';

        date_default_timezone_set('Asia/Jakarta');
        parent::initController($request, $response, $logger);
    }
    public function initController()
    {
        parent::initController();
        $this->load->model('Admin_model', 'Admin');
    }
}
