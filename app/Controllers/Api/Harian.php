<?php

namespace App\Controllers\Api;

use App\Models\Harian_model;
use App\Controllers\BaseController;

class Harian extends BaseController
{
    protected $modelName = Harian_model::class;

    public function show($id_siswa = null)
    {
        $result = $this->model->where('harian_siswa_id', (int)$id_siswa)->get()->getResultArray();

        if (!$result) {
            return $this->respond(array("status" => false, "message" => "tidak ditemukan", "data" => null));
        }
        return $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
    }

    public function create()
    {
        $data = array(
            'harian_tgl'            => $this->request->getVar('harian_tgl'),
            'harian_kegiatan'       => $this->request->getVar('harian_kegiatan'),
            'harian_pendaftaran_id' => $this->request->getVar('harian_pendaftaran_id'),
            'harian_siswa_id'       => $this->request->getVar('harian_siswa_id'),
        );

        $validation = \Config\Services::validation();
        $validation->setRule('harian_tgl', 'Tanggal', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('harian_kegiatan', 'Kegiatan', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('harian_pendaftaran_id', 'ID Pendaftaran', 'required', array('required' => '{field} tidak boleh kosong.'));

        if ($validation->withRequest($this->request)->run() === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $result = $this->model->save($data);
        if (!$result) {
            return $this->respond(array('status' => false, 'message' => 'Gagal', 'data' => null));
        }
        return $this->respond(array('status' => true, 'message' => 'Sukses', 'data' => null));
    }
}
