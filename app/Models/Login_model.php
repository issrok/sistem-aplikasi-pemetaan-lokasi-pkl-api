<?php

namespace App\Models;

use CodeIgniter\Model;

class Login_model extends Model
{
    public function login_siswa($data)
    {
        return $this->db->table('tb_siswa')->getWhere(['siswa_nis' => $data['nis'], 'siswa_password' => md5($data['password'])])->getRowArray();
    }

    public function login_admin($data)
    {
        return $this->db->table('tb_admin')->getWhere(['admin_username' => $data['username'], 'admin_password' => md5($data['password'])])->getRowArray();
    }

    public function login_guru($data)
    {
        return $this->db->table('tb_guru')->getWhere(['guru_nik' => $data['nik'], 'guru_password' => md5($data['password'])])->getRowArray();
    }
}
