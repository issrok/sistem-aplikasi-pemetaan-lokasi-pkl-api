<?php

namespace App\Controllers\Api;

use App\Models\Pendaftaran_model;
use App\Controllers\BaseController;

class Pendaftaran extends BaseController
{
    protected $modelName = Pendaftaran_model::class;

    public function create()
    {
        $data = array(
            'pendaftaran_pkl_id'      => $this->request->getVar('pendaftaran_pkl_id'),
            'pendaftaran_siswa_id'    => $this->request->getVar('pendaftaran_siswa_id'),
            'pendaftaran_tgl_mulai'   => $this->request->getVar('pendaftaran_tgl_mulai'),
            'pendaftaran_tgl_selesai' => $this->request->getVar('pendaftaran_tgl_selesai')
        );

        $validation = \Config\Services::validation();
        $validation->setRule('pendaftaran_siswa_id', 'Siswa', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pendaftaran_pkl_id', 'Tempat PKL', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pendaftaran_tgl_mulai', 'Tanggal mulai', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pendaftaran_tgl_selesai', 'Tanggal selesai', 'required', array('required' => '{field} tidak boleh kosong.'));

        if ($validation->withRequest($this->request)->run() === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $countSiswa = $this->model->pendaftaran_detail_count((int)$data['pendaftaran_siswa_id']);

        if ($countSiswa > 0) {
            return $this->respond(array('status' => false, 'message' => 'Anda sudah mendaftar', 'data' => null));
        }

        $result = $this->model->save_pendaftaran($data);
        if (!$result) {
            $this->respond(array('status' => false, 'message' => 'Gagal', 'data' => null));
        }

        return $this->respond(array('status' => true, 'message' => 'Sukses', 'data' => $result));
    }

    public function show($id = null)
    {
        $result = $this->model->detail($id);

        if (!$result) {
            return $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
        }
        return $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
    }
}
