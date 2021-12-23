<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Controllers\BaseController;

class Pendaftaran_model extends Model
{

    protected $table = 'tb_pendaftaran';
    protected $primaryKey = 'pendaftaran_id';
    protected $protectFields = false;
    protected $returnType = 'App\Entities\Pendaftaran';

    public function save_pendaftaran($data)
    {
        $cek = $this->countPendaftaran($data['pendaftaran_pkl_id']);
        if ($cek > 0) {
            $pendaftaran             = $this->where('pendaftaran_pkl_id', $data['pendaftaran_pkl_id'])->first();
            $data_pendaftaran_detail = array(
                'pendaftaran_detail_pendf_id'    => $pendaftaran['pendaftaran_id'],
                'pendaftaran_detail_siswa_id'    => $data['pendaftaran_siswa_id'],
                'pendaftaran_detail_tgl_mulai'   => $data['pendaftaran_tgl_mulai'],
                'pendaftaran_detail_tgl_selesai' => $data['pendaftaran_tgl_selesai'],
            );
            return $this->db->table('tb_pendaftaran_detail')->insert($data_pendaftaran_detail);
        }
        $data_pendaftaran        = array(
            'pendaftaran_pkl_id'           => $data['pendaftaran_pkl_id'],
            'pendaftaran_status'           => 'belum_penyerahan',
            'pendaftaran_kode_status'      => '2',
            'pendaftaran_penyerahan_photo' => '',
            'pendaftaran_penarikan_photo'  => '',
        );
        $result                  = $this->insert($data_pendaftaran);
        $data_pendaftaran_detail = array(
            'pendaftaran_detail_pendf_id'    => $result,
            'pendaftaran_detail_siswa_id'    => $data['pendaftaran_siswa_id'],
            'pendaftaran_detail_tgl_mulai'   => $data['pendaftaran_tgl_mulai'],
            'pendaftaran_detail_tgl_selesai' => $data['pendaftaran_tgl_selesai'],
        );
        if ($result) {
            return $this->db->table('tb_pendaftaran_detail')->insert($data_pendaftaran_detail);
        }
        return $result;
    }

    public function countPendaftaran($id)
    {
        return $this->where('pendaftaran_pkl_id', $id)->countAllResults();
    }

    public function get_pendaftaran_where($where, $value)
    {
        $this->db->where($where, $value);
        return $this->db->get('tb_pendaftaran')->row_array();
    }

    public function get_all_status($id_guru, $status)
    {
        $this->where('pkl_guru_id', $id_guru);
        $this->where('pendaftaran_kode_status', $status);
        $result = $this->join('tb_tempat_pkl', 'tb_tempat_pkl.pkl_id=tb_pendaftaran.pendaftaran_pkl_id')->get()->getResultArray();

        foreach ($result as $key => $value) {
            $result[$key]['pendaftaran_penarikan_photo']  = empty($value['pendaftaran_penarikan_photo']) ? base_url() . BaseController::path_image_pendaftaran . 'no_image.jpg' : base_url() . BaseController::path_image_pendaftaran . $value['pendaftaran_penarikan_photo'];
            $result[$key]['pendaftaran_penyerahan_photo'] = empty($value['pendaftaran_penyerahan_photo']) ? base_url() . BaseController::path_image_pendaftaran . 'no_image.jpg' : base_url() . BaseController::path_image_pendaftaran . $value['pendaftaran_penyerahan_photo'];
            $result[$key]['pkl_gambar']                   = empty($value['pkl_gambar']) ? base_url() . BaseController::path_image_pkl . 'no_image.jpg' : base_url() . BaseController::path_image_pkl . $value['pkl_gambar'];

            $result[$key]['daftar_siswa'] = $this->db->table('tb_pendaftaran_detail')
                                                     ->select('siswa_id,siswa_nama,siswa_nis,siswa_kelas,siswa_no_telp')
                                                     ->where('pendaftaran_detail_pendf_id', $value['pendaftaran_id'])
                                                     ->join('tb_siswa', 'tb_siswa.siswa_id = tb_pendaftaran_detail.pendaftaran_detail_siswa_id')->get()->getResultArray();
        }
        return $result;
    }

    public function detail($id)
    {
        $pendaftaranDetail = $this->db->table('tb_pendaftaran_detail')->where('pendaftaran_detail_siswa_id', $id)->get()->getRowArray();
        if (!$pendaftaranDetail) {
            return false;
        }
        $result = $this->select('pendaftaran_id,pendaftaran_pkl_id,pendaftaran_guru_id,pendaftaran_detail_siswa_id,pendaftaran_status,pendaftaran_kode_status,pendaftaran_penyerahan_photo,pendaftaran_penarikan_photo,pendaftaran_detail_tgl_mulai,pendaftaran_detail_tgl_selesai')
                       ->where('pendaftaran_detail_siswa_id', $id)
                       ->join('tb_pendaftaran_detail', 'tb_pendaftaran.pendaftaran_id = tb_pendaftaran_detail.pendaftaran_detail_pendf_id')->get()->getRowArray();

        if (!$result) {
            return false;
        }
        $data_siswa = $this->db->table('tb_siswa')->where('siswa_id', $id)->get()->getRowArray();
        $data_pkl   = $this->db->table('tb_tempat_pkl')->where('pkl_id', $result['pendaftaran_pkl_id'])->get()->getRowArray();

        $data_pkl['pkl_gambar'] = empty($data_pkl['pkl_gambar']) ? $this->_path_pkl . 'no_image.jpg' : base_url() . BaseController::path_image_pkl . $data_pkl['pkl_gambar'];

        $data_guru = $this->db->table('tb_guru')->where('guru_id', $data_pkl['pkl_guru_id'])->get()->getRowArray();
        if (empty($data_guru)):
            $data_guru['guru_nama']    = 'pembimbing belum ditentukan';
            $data_guru['guru_nik']     = '-';
            $data_guru['guru_no_telp'] = '-';
            $data_guru['guru_alamat']  = '-';
        endif;
        $result += array(
            'data_siswa' => $data_siswa,
            'data_pkl'   => $data_pkl,
            'data_guru'  => $data_guru,
        );
        return $result;
    }

    public function pendaftaran_detail_count(int $id)
    {
        return $this->db->table('tb_pendaftaran_detail')->where('pendaftaran_detail_siswa_id', $id)->countAllResults();
    }

    public function status_change($id, $kode_status)
    {
        if ($kode_status == '1'):
            $status = 'sudah_penyerahan';
        elseif ($kode_status == '2'):
            $status = 'belum_penyerahan';
        elseif ($kode_status == '3'):
            $status = 'sudah_ditarik';
        elseif ($kode_status == '4'):
            $status = 'belum_ditarik';
        endif;

        $data = array(
            "pendaftaran_id"      => $id,
            "pendaftaran_status"      => $status,
            "pendaftaran_kode_status" => $kode_status,
        );

        return $this->save($data);
    }

    public function cek_detail($id)
    {
        $this->db->where('pendaftaran_pkl_id', $id);
        $detail = $this->db->get('tb_pendaftaran')->row_array();
        return $detail;
    }

    public function upload_gambar_monitoring($data, $id)
    {

        $get_photo_name = $this->upload_image($this->_path_image, 'gambar');

        $get_photo_exist = $this->get_by_id($id);

        if (!(empty($get_photo_exist['pendaftaran_monitoring_photo'])) &&
            ($get_photo_exist['pendaftaran_monitoring_photo'] !== 'no_image.jpg') &&
            file_exists($this->_path_image . $get_photo_exist['pendaftaran_monitoring_photo']) &&
            (!empty($get_photo_name))
        ) {
            unlink($this->_path_image . $get_photo_exist['pendaftaran_monitoring_photo']);
        }

        $get_photo_name = !empty($get_photo_name) ? $get_photo_name : $get_photo_exist['pendaftaran_monitoring_photo'];

        $data['pendaftaran_monitoring_photo'] = $get_photo_name;

        if (empty($get_photo_name['msg_error'])) {
            $query = $this->update($data, $id);
            if ($query) {
                $result = array('status' => true, 'message' => 'Sukses');
            } else {
                $result = array('status' => false, 'message' => 'Gagal');
            }
        } else {
            $result = array('status' => false, 'message' => $get_photo_name['msg_error']);
        }
        return $result;
    }

    public function upload_gambar_penarikan($data, $id)
    {

        $get_photo_name = $this->upload_image($this->_path_image, 'gambar');

        $get_photo_exist = $this->get_by_id($id);

        if (!(empty($get_photo_exist['pendaftaran_penarikan_photo'])) &&
            ($get_photo_exist['pendaftaran_penarikan_photo'] !== 'no_image.jpg') &&
            file_exists($this->_path_image . $get_photo_exist['pendaftaran_penarikan_photo']) &&
            (!empty($get_photo_name))
        ) {
            unlink($this->_path_image . $get_photo_exist['pendaftaran_penarikan_photo']);
        }

        $get_photo_name = !empty($get_photo_name) ? $get_photo_name : $get_photo_exist['pendaftaran_penarikan_photo'];

        $data['pendaftaran_penarikan_photo'] = $get_photo_name;

        if (empty($get_photo_name['msg_error'])) {
            $query = $this->update($data, $id);
            if ($query) {
                $result = array('status' => true, 'message' => 'Sukses');
            } else {
                $result = array('status' => false, 'message' => 'Gagal');
            }
        } else {
            $result = array('status' => false, 'message' => $get_photo_name['msg_error']);
        }
        return $result;
    }

    public function upload_gambar($data, $id)
    {

        $get_photo_name = $this->upload_image($this->_path_image, 'gambar');

        $get_photo_exist = $this->get_by_id($id);

        if (!(empty($get_photo_exist['pendaftaran_penyerahan_photo'])) &&
            ($get_photo_exist['pendaftaran_penyerahan_photo'] !== 'no_image.jpg') &&
            file_exists($this->_path_image . $get_photo_exist['pendaftaran_penyerahan_photo']) &&
            (!empty($get_photo_name))
        ) {
            unlink($this->_path_image . $get_photo_exist['pendaftaran_penyerahan_photo']);
        }

        $get_photo_name = !empty($get_photo_name) ? $get_photo_name : $get_photo_exist['pendaftaran_penyerahan_photo'];

        $data['pendaftaran_penyerahan_photo'] = $get_photo_name;

        if (empty($get_photo_name['msg_error'])) {
            $query = $this->update($data, $id);
            if ($query) {
                $result = array('status' => true, 'message' => 'Sukses');
            } else {
                $result = array('status' => false, 'message' => 'Gagal');
            }
        } else {
            $result = array('status' => false, 'message' => $get_photo_name['msg_error']);
        }
        return $result;
    }
}
