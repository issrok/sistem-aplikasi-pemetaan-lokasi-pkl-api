<?php

namespace App\Controllers\Api;

use App\Models\Pendaftaran_model;
use App\Controllers\BaseController;

class Penyerahan extends BaseController
{
    protected $modelName = Pendaftaran_model::class;

    public function index_get($id_guru, $status)
    {
        $result = $this->model->get_all_status($id_guru, $status);

        if ($result) {
            return $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
        }

        return $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
    }


    public function status_post($id, $kode_status)
    {
        $result = $this->model->status_change($id, $kode_status);
        if ($result) {
            return $this->respond(array("status" => true, "message" => "sukses", 'data' => null));
        }

        return $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
    }

    public function upload_gambar($id)
    {
        $validation = \Config\Services::validation();
        $validation->setRule('gambar', 'Gambar', 'uploaded[gambar]|is_image[gambar]');

        if ($validation->withRequest($this->request)->run() === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $fileImage = $this->request->getFile('gambar');
        $newName   = $fileImage->getRandomName();
        if ($fileImage->isValid() && !$fileImage->hasMoved()) {
            $fileImage->move(ROOTPATH . self::path_image_pendaftaran, $newName);
            $data['pendaftaran_penyerahan_photo'] = $newName;
        }

        $get_photo_exist = $this->model->where('pendaftaran_id', $id)->get()->getRowArray();
        if (!(empty($get_photo_exist['pendaftaran_penyerahan_photo'])) && file_exists(ROOTPATH . self::path_image_pendaftaran . $get_photo_exist['pendaftaran_penyerahan_photo'])) {
            unlink(ROOTPATH . self::path_image_pendaftaran . $get_photo_exist['pendaftaran_penyerahan_photo']);
        }

        $data['pendaftaran_id'] = $id;
        $save                   = $this->model->save($data);
        if (!$save) {
            return $this->respond(array('status' => false, 'message' => 'Gagal', 'data' => null));
        }
        return $this->respond(array('status' => true, 'message' => 'Sukses', 'data' => null));
    }
}
