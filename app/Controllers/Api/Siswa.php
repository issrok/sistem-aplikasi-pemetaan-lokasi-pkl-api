<?php

namespace App\Controllers\Api;

use App\Models\Siswa_model;
use App\Controllers\BaseController;

class Siswa extends BaseController
{
    protected $modelName = Siswa_model::class;

    public function index()
    {
        $result = $this->model->findAll();
        if (!$result) {
            return $this->respond(array("status" => false, "message" => "Data Not Found"));
        }
        return $this->respond(array("status" => true, "message" => "Data Found", "data" => $result));
    }
}
