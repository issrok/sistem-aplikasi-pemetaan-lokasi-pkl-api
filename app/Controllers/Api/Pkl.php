<?php

namespace App\Controllers\Api;

use App\Models\Pkl_model;
use App\Controllers\BaseController;

class Pkl extends BaseController
{
    protected $modelName = Pkl_model::class;

    public function index()
    {
        $result = $this->model->semua(1);

        if (!$result) {
            return $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
        }

        return $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
    }

    public function detail($id)
    {
        $result = $this->model->detail($id);

        if (!$result) {
            return $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
        }

        return $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
    }

    public function status(int $kode_status)
    {
        $result = $this->model->semua($kode_status);

        if (!$result) {
            return $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
        }

        return $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
    }

    public function pendaftaran($kode_pendfataran)
    {
        if ($kode_pendfataran == 0):
            $result = $this->Pkl->get_all();
        else:
            $result = $this->Pkl->get_all_where("pkl_kode_status", $kode_pendfataran);
        endif;

        foreach ($result as $key => $value) {
            $result[$key]['pkl_gambar'] = empty($value['pkl_gambar']) ? $this->path_image_pkl . 'no_image.jpg' : $this->path_image_pkl . $value['pkl_gambar'];
        }

        if ($result) {
            $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
        } else {
            $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
        }
    }

    public function status_post($id_pkl, $kode_status)
    {
        if ($kode_status === '1'):
            $status = 'layak';
        elseif ($kode_status === '2'):
            $status = 'tidak_layak';
        elseif ($kode_status === '3'):
            $status = 'belum_validasi';
        endif;

        $data = array(
            "pkl_id"          => $id_pkl,
            "pkl_status"      => $status,
            "pkl_kode_status" => $kode_status,
        );

        $result = $this->model->save($data);
        if (!$result) {
            return $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
        }

        return $this->respond(array("status" => true, "message" => "sukses", 'data' => null));
    }

    public function tambah()
    {
        $data = [
            'pkl_nama'        => $this->request->getVar('pkl_nama'),
            'pkl_pemilik'     => $this->request->getVar('pkl_pemilik'),
            'pkl_no_telp'     => $this->request->getVar('pkl_no_telp'),
            'pkl_latitude'    => $this->request->getVar('pkl_latitude'),
            'pkl_longitude'   => $this->request->getVar('pkl_longitude'),
            'pkl_alamat'      => $this->request->getVar('pkl_alamat'),
            'pkl_deskripsi'   => $this->request->getVar('pkl_deskripsi'),
            'pkl_maks_siswa'  => $this->request->getVar('pkl_maks_siswa'),
            'pkl_status'      => 'belum_validasi',
            'pkl_kode_status' => '3'
        ];

        $validation = \Config\Services::validation();
        $validation->setRule('pkl_nama', 'Nama Tempat', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pkl_pemilik', 'Nama Pemilik', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pkl_no_telp', 'No Telp', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pkl_latitude', 'Latitude', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pkl_longitude', 'Longitude', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pkl_alamat', 'Alamat', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('gambar', 'Gambar', 'is_image[gambar]');

        if ($validation->withRequest($this->request)->run() === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $fileImage = $this->request->getFile('gambar');
        $newName   = $fileImage->getRandomName();
        if ($fileImage->isValid() && !$fileImage->hasMoved()) {
            $fileImage->move(ROOTPATH . self::path_image_pkl, $newName);
            $data['pkl_gambar'] = $newName;
        }

        $save = $this->model->save($data);
        if (!$save) {
            return $this->respond(array('status' => false, 'message' => 'Gagal', 'data' => null));
        }
        return $this->respond(array('status' => true, 'message' => 'Sukses', 'data' => null));
    }

    public function ubah($id_pkl)
    {
        $result_by_id = $this->model->find($id_pkl);

        $data = array(
            'pkl_id'         => $id_pkl,
            'pkl_nama'       => $this->request->getVar('pkl_nama'),
            'pkl_pemilik'    => $this->request->getVar('pkl_pemilik'),
            'pkl_no_telp'    => $this->request->getVar('pkl_no_telp'),
            'pkl_latitude'   => $this->request->getVar('pkl_latitude'),
            'pkl_longitude'  => $this->request->getVar('pkl_longitude'),
            'pkl_alamat'     => $this->request->getVar('pkl_alamat'),
            'pkl_deskripsi'  => $this->request->getVar('pkl_deskripsi'),
            'pkl_maks_siswa' => $this->request->getVar('pkl_maks_siswa'));

        $validation = \Config\Services::validation();
        $validation->setRule('pkl_nama', 'Nama Tempat', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pkl_pemilik', 'Nama Pemilik', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pkl_no_telp', 'No Telp', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pkl_latitude', 'Latitude', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pkl_longitude', 'Longitude', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('pkl_alamat', 'Alamat', 'required', array('required' => '{field} tidak boleh kosong.'));

        if ($result_by_id->pkl_kode_status === "1") {
            $validation->setRule('pkl_maks_siswa', 'Jumlah Siswa Maksimal', 'required', array('required' => '{field} tidak boleh kosong.'));
        }

        if ($validation->withRequest($this->request)->run() === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $result = $this->model->save($data);
        if (!$result) {
            return $this->respond(array('status' => false, 'message' => 'Gagal', 'data' => null));
        }
        return $this->respond(array('status' => true, 'message' => 'Sukses', 'data' => null));
    }

    public function upload_gambar($id_pkl)
    {
        $validation = \Config\Services::validation();
        $validation->setRule('gambar', 'Gambar', 'uploaded[gambar]|is_image[gambar]');

        if ($validation->withRequest($this->request)->run() === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $fileImage = $this->request->getFile('gambar');
        $newName   = $fileImage->getRandomName();
        if ($fileImage->isValid() && !$fileImage->hasMoved()) {
            $fileImage->move(ROOTPATH . self::path_image_pkl, $newName);
            $data['pkl_gambar'] = $newName;
        }

        $get_photo_exist = $this->model->where('pkl_id', $id_pkl)->get()->getRowArray();
        if (!(empty($get_photo_exist['pkl_gambar'])) && file_exists(ROOTPATH . self::path_image_pkl . $get_photo_exist['pkl_gambar'])) {
            unlink(ROOTPATH . self::path_image_pkl . $get_photo_exist['pkl_gambar']);
        }

        $data['pkl_id'] = $id_pkl;
        $save           = $this->model->save($data);
        if (!$save) {
            return $this->respond(array('status' => false, 'message' => 'Gagal', 'data' => null));
        }
        return $this->respond(array('status' => true, 'message' => 'Sukses', 'data' => null));
    }

    public function pilih_pembimbing($id, $id_guru)
    {
        $data = ['pkl_id'      => $id,
                 'pkl_guru_id' => $id_guru];

        if ($this->model->cek_pembimbing($id_guru) >= 2) {
            return $this->respond(array("status" => false, "message" => "melebihi batas maksimal", 'data' => null));
        }

        $result = $this->model->save($data);
        if (!$result) {
            return $this->respond(array("status" => false, "message" => "gagal", 'data' => null));
        }
        return $this->respond(array("status" => true, "message" => "sukses", "data" => null));
    }
}
