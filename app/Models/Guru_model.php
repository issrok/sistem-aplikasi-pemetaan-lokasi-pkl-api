<?php

namespace App\Models;

use CodeIgniter\Model;

class Guru_model extends Model
{
    protected $table = 'tb_guru';
    protected $primaryKey = 'guru_id';
    protected $protectFields = false;

    public function get_pkl($id_guru)
    {
        $pklModel = new Pkl_model();
        return $pklModel->where('pkl_guru_id', $id_guru)->findAll();
    }

    public function getCustomer($id)
    {
        $query = $this->get_by_id($id);
        return $query;
    }

    public function change($data, $key, $cust_id)
    {
        $oldData     = $this->get_by_id($cust_id);
        $cekUsername = $this->get_where('siswa_username', $data['siswa_username']);
        if (count($cekUsername) <= 0) {
            $this->status = true;
        } else {
            if (strtolower($oldData['siswa_username']) == strtolower($data['siswa_username'])) {
                $this->status = true;
            } else {
                $this->status         = false;
                $this->respon['data'] = array('status' => false, 'message' => 'Username sudah ada');
            }
        }
        if ($this->status) {
            $valid = $this->retrieveDataModel($data, $key);
            if ($valid['status']) {
                $dtValid = array(
                    'siswa_nama'     => $data['siswa_nama'],
                    'siswa_alamat'   => $data['siswa_alamat'],
                    'siswa_telp'     => $data['siswa_telp'],
                    'siswa_username' => $data['siswa_username'],
                    'siswa_password' => md5($data['siswa_password']),
                );
                $cek     = $this->update($dtValid, $cust_id);
                if ($cek) {
                    $this->respon['data'] = array('status' => true, 'message' => 'Success');
                } else {
                    $this->respon['data'] = array('status' => false, 'message' => 'Unauthorized');
                }
            } else {
                $this->respon['data'] = array('status' => false, 'message' => $this->replaceStr('siswa_', '', $valid['message']));
            }
        }
        return $this->respon;
    }

    /*
     * register
     */
    public function register($param)
    {
        $param['guru_password'] = md5($param['guru_password']);

        if ($this->getNikCount($param['guru_nik']) > 0) {
            return ['err' => 'NIK Sudah digunakan'];
        }

        $save = $this->insert($param);

        if (!$save) {
            return ['err' => 'Gagal'];
        }
        return $save;
    }

    public function getNikCount($param)
    {
        return $this->getWhere(['guru_nik' => $param])->getNumRows();
    }
}
