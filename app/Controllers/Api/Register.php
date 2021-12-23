<?php

namespace App\Controllers\Api;

use App\Models\Guru_model;
use App\Models\Siswa_model;
use App\Controllers\BaseController;

class Register extends BaseController
{
    public function guru()
    {
        parent::setModel(Guru_model::class);
        $data = array(
            'guru_nama'     => $this->request->getVar('guru_nama'),
            'guru_nik'      => $this->request->getVar('guru_nik'),
            'guru_no_telp'  => $this->request->getVar('guru_no_telp'),
            'guru_alamat'   => $this->request->getVar('guru_alamat'),
            'guru_password' => $this->request->getVar('guru_password'));

        $validation = \Config\Services::validation();
        $validation->setRule('guru_nama', 'Nama', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('guru_nik', 'NIK', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('guru_no_telp', 'No Telp', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('guru_alamat', 'Alamat', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('guru_password', 'Password', 'required', array('required' => '{field} tidak boleh kosong.'));

        if ($validation->run($data) === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $register = $this->model->register($data);

        if (isset($register['err'])) {
            return $this->respond(array('status' => false, 'message' => $register['err'], 'data' => null));
        }

        return $this->respond(array('status' => true, 'message' => 'Sukses', 'data' => null));
    }

    public function siswa()
    {
        parent::setModel(Siswa_model::class);
        $data = [
            'siswa_nama'     => $this->request->getVar('siswa_nama'),
            'siswa_nis'      => $this->request->getVar('siswa_nis'),
            'siswa_no_telp'  => $this->request->getVar('siswa_no_telp'),
            'siswa_kelas'    => $this->request->getVar('siswa_kelas'),
            'siswa_password' => $this->request->getVar('siswa_password')
        ];

        $validation = \Config\Services::validation();
        $validation->setRule('siswa_nama', 'Nama', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('siswa_nis', 'NIS', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('siswa_no_telp', 'No Telp', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('siswa_kelas', 'Kelas', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('siswa_password', 'Password', 'required', array('required' => '{field} tidak boleh kosong.'));

        if ($validation->withRequest($this->request)->run() === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $register = $this->model->register($data);

        if (isset($register['err'])) {
            return $this->respond(array('status' => false, 'message' => $register['err'], 'data' => null));
        }

        return $this->respond(array('status' => true, 'message' => 'Sukses', 'data' => null));
    }
}
