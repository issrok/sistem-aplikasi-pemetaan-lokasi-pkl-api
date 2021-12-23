<?php

namespace App\Models;

use CodeIgniter\Model;

class Jarak_model extends Model
{
    protected $table = 'tb_tempat_pkl';
    protected $primaryKey = 'pkl_id';
    protected $protectFields = false;

    public function semua()
    {
        return $this->db->get('tb_tempat_pkl')->result_array();
    }

    public function semua_where($kode)
    {
        $this->db->where('pkl_kode_status', $kode);
        $this->db->join('tb_guru', 'tb_guru.guru_id=tb_tempat_pkl.pkl_guru_id', 'left');
        return $this->db->get('tb_tempat_pkl')->result_array();
    }

    public function jarak_pkl_limit($id)
    {
        return $this->getWhere(['pkl_kode_status' => '1', 'pkl_guru_id' => $id], 4)->getResultArray();
    }
}
