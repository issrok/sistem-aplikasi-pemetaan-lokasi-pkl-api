<?php

namespace App\Models;

use CodeIgniter\Model;

class Pkl_model extends Model
{
    protected $table = 'tb_tempat_pkl';
    protected $primaryKey = 'pkl_id';
    protected $protectFields = false;
    protected $returnType = 'App\Entities\Pkl';

    public function semua($kode)
    {
        if ($kode !== 0) {
            $this->where('pkl_kode_status', $kode);
        }
        return $this->join('tb_guru', 'tb_guru.guru_id=tb_tempat_pkl.pkl_guru_id', 'left')->findAll();
    }

    public function cek_pembimbing($id_guru)
    {
        return $this->db->table('tb_tempat_pkl')->where("pkl_guru_id", $id_guru)->countAllResults();
    }

    public function detail($id)
    {
        $this->where('pkl_id', $id);
        return $this->join('tb_guru', 'tb_guru.guru_id=tb_tempat_pkl.pkl_guru_id', 'left')->first();
    }

    public function upload_gambar(int $id)
    {

        $get_photo_exist = $this->where('pkl_id', $id)->get()->getRowArray();
        // unlink($this->_path_image . $get_photo_exist['pkl_gambar']);
        return $get_photo_exist;
    }
}
