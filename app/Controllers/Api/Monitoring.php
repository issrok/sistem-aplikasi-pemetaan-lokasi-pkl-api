<?php

namespace App\Controllers\Api;

use App\Models\Monitoring_model;
use App\Models\Pendaftaran_model;
use App\Controllers\BaseController;

class Monitoring extends BaseController
{
    protected $modelName = Monitoring_model::class;

    // public function __construct()
    // {
    //     parent::__construct();
    //     $path                   = str_replace('/api', '', FCPATH);
    //     $this->_path_image      = $path . 'media/monitoring/';
    //     $this->_path_monitoring = str_replace('/api', '', base_url()) . 'media/monitoring/';
    //     $this->load->model('Siswa_model', 'Siswa');
    //     $this->load->model('Guru_model', 'Guru');
    //     $this->load->model('Pendaftaran_model', 'Pendaftaran');
    //     $this->load->model('Pkl_model', 'Pkl');
    //     $this->load->model('Monitoring_model', 'Monitoring');
    // }

    public function index_get($id_pendaftaran)
    {
        $result = $this->model->where('monitoring_pendaftaran_id', $id_pendaftaran)->findAll();
        foreach ($result as $key => $value) {
            $result[$key]['monitoring_photo'] = empty($value['monitoring_photo']) ? base_url() . BaseController::path_image_monitoring . 'no_image.jpg' : base_url() . BaseController::path_image_monitoring . $value['monitoring_photo'];
        }
        if (!$result) {
            return $this->respond(array("status" => false, "message" => "tidak ditemukan", "data" => null));
        }
        return $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
    }

    public function pkl($id_guru, $status)
    {
        $model  = new Pendaftaran_model();
        $result = $model->get_all_status($id_guru, $status);

        if ($result) {
            return $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
        }

        return $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
    }

    public function index_post($id_pendaftaran)
    {
        $data = array(
            'monitoring_pendaftaran_id' => $id_pendaftaran,
            'monitoring_tanggal'        => $this->request->getVar('monitoring_tanggal'),
            'monitoring_photo'          => $this->request->getVar('monitoring_photo'),
            'monitoring_keterangan'     => $this->request->getVar('monitoring_keterangan'));

        $validation = \Config\Services::validation();
        $validation->setRule('monitoring_tanggal', 'Tanggal', 'required', array('required' => '{field} tidak boleh kosong.'));
        $validation->setRule('monitoring_keterangan', 'Keterangan', 'required', array('required' => '{field} tidak boleh kosong.'));

        if ($validation->withRequest($this->request)->run() === false) {
            return $this->respond(['status' => false, 'message' => $this->errorToArray($validation->getErrors()), 'data' => null]);
        }

        $fileImage = $this->request->getFile('monitoring_photo');
        $newName   = $fileImage->getRandomName();
        if ($fileImage->isValid() && !$fileImage->hasMoved()) {
            $fileImage->move(ROOTPATH . self::path_image_pendaftaran, $newName);
            $data['monitoring_photo'] = $newName;
        }

        $save = $this->model->save($data);
        if (!$save) {
            return $this->respond(array('status' => false, 'message' => 'Gagal', 'data' => null));
        }
        return $this->respond(array('status' => true, 'message' => 'Sukses', 'data' => null));
    }

    public function status($id, $kode_status)
    {
        $model  = new Pendaftaran_model();
        $result = $model->status_change($id, $kode_status);
        if ($result) {
            return $this->respond(array("status" => true, "message" => "sukses", "data" => null));
        }

        return $this->respond(array("status" => false, "message" => "tidak ditemukan", "data" => null));
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
            $fileImage->move(ROOTPATH . self::path_image_monitoring, $newName);
            $data['monitoring_photo'] = $newName;
        }

        $get_photo_exist = $this->model->where('monitoring_id', $id)->get()->getRowArray();
        if (!(empty($get_photo_exist['monitoring_photo'])) && file_exists(ROOTPATH . self::path_image_monitoring . $get_photo_exist['monitoring_photo'])) {
            unlink(ROOTPATH . self::path_image_monitoring . $get_photo_exist['monitoring_photo']);
        }

        $data['monitoring_id'] = $id;
        $save                  = $this->model->save($data);
        if (!$save) {
            return $this->respond(array('status' => false, 'message' => 'Gagal', 'data' => null));
        }
        return $this->respond(array('status' => true, 'message' => 'Sukses', 'data' => null));
    }
}
