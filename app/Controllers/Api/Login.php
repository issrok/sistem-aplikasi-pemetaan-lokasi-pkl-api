<?php

namespace App\Controllers\Api;

use App\Models\Login_model;
use App\Controllers\BaseController;

class Login extends BaseController
{
    protected $modelName = Login_model::class;

    public function admin()
    {
        $data = array(
            'username' => $this->request->getVar('username'),
            'password' => $this->request->getVar('password')
        );

        $validation = \Config\Services::validation();
        $validation->setRule('username', 'Username', 'required', ['required' => '{field} tidak boleh kosong']);
        $validation->setRule('password', 'Password', 'required', ['required' => '{field} tidak boleh kosong']);

        if ($validation->run($data) === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $login = $this->model->login_admin($data);
        if (!$login) {
            return $this->respond(['status' => false, 'message' => 'username atau password \nsalah', 'data' => null]);
        }
        return $this->respond(['status' => true, 'message' => 'Sukses', 'data' => $login]);
    }

    public function guru()
    {
        $validation = \Config\Services::validation();
        $validation->setRule('nik', 'NIK', 'required', ['required' => '{field} tidak boleh kosong']);
        $validation->setRule('password', 'Password', 'required', ['required' => '{field} tidak boleh kosong']);

        $data = array(
            'nik'      => $this->request->getVar('nik'),
            'password' => $this->request->getVar('password')
        );

        if ($validation->run($data) === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $login = $this->model->login_guru($data);
        if (!$login) {
            return $this->respond(['status' => false, 'message' => 'username atau password \nsalah', 'data' => null]);
        }
        return $this->respond(['status' => true, 'message' => 'Sukses', 'data' => $login]);
    }

    public function siswa()
    {
        $validation = \Config\Services::validation();
        $validation->setRule('nis', 'NIS', 'required', ['required' => '{field} tidak boleh kosong']);
        $validation->setRule('password', 'Password', 'required', ['required' => '{field} tidak boleh kosong']);

        $data = array(
            'nis'      => $this->request->getVar('nis'),
            'password' => $this->request->getVar('password')
        );

        if ($validation->run($data) === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $login = $this->model->login_siswa($data);
        if (!$login) {
            return $this->respond(['status' => false, 'message' => 'nis atau password \nsalah', 'data' => null]);
        }
        return $this->respond(['status' => true, 'message' => 'Sukses', 'data' => $login]);
    }
}
