<?php

namespace App\Controllers\Api;

use App\Models\Guru_model;
use App\Controllers\BaseController;

class Guru extends BaseController
{
    protected $modelName = Guru_model::class;

    public function index()
    {
        $result = $this->model->findAll();
        foreach ($result as $key => $value) {
            $result[$key]['guru_tempat_pkl'] = $this->model->get_pkl($value['guru_id']);
        }
        if (!$result) {
            return $this->respond(array("status" => false, "message" => "Data Not Found"));
        }
        return $this->respond(array("status" => true, "message" => "Data Found", "data" => $result));
    }

    public function index_post()
    {
        $key    = array('customer_nama', 'customer_alamat', 'customer_telp', 'customer_username', 'customer_password');
        $data   = array(
            'customer_nama'     => $this->post('nama'),
            'customer_alamat'   => $this->post('alamat'),
            'customer_telp'     => $this->post('telp'),
            'customer_username' => $this->post('username'),
            'customer_password' => $this->post('password'));
        $return = $this->Guru->verify($data, $key);
        $this->respond($return['data'], $return['data']['code']);
    }

    public function update_post($cust_id)
    {
        $key    = array('customer_nama', 'customer_alamat', 'customer_telp', 'customer_username', 'customer_password');
        $data   = array(
            'customer_nama'     => $this->post('nama'),
            'customer_alamat'   => $this->post('alamat'),
            'customer_telp'     => $this->post('telp'),
            'customer_username' => $this->post('username'),
            'customer_password' => $this->post('password'));
        $return = $this->Guru->change($data, $key, $cust_id);
        $this->respond($return['data'], $return['data']['code']);
    }
}
