<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Controllers\BaseController;

class Pendaftaran extends Entity
{
    public function getPendaftaranPenarikanPhoto()
    {
        $img = $this->attributes['pendaftaran_penarikan_photo'];
        return empty($img) ? base_url() . BaseController::path_image_pendaftaran . 'no_image.jpg' : base_url() . BaseController::path_image_pendaftaran . $img;
    }

    public function getPendaftaranPenyerahanPhoto()
    {
        $img = $this->attributes['pendaftaran_penyerahan_photo'];
        return empty($img) ? base_url() . BaseController::path_image_pendaftaran . 'no_image.jpg' : base_url() . BaseController::path_image_pendaftaran . $img;
    }
}