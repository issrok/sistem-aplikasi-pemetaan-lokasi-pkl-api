<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Controllers\BaseController;

class Pkl extends Entity
{
    public function getPklGambar()
    {
        $img = $this->attributes['pkl_gambar'];
        return empty($img) ? base_url() . BaseController::path_image_pkl . 'no_image.jpg' : base_url() . BaseController::path_image_pkl . $img;
    }
}