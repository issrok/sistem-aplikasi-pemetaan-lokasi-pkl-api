<?php

namespace App\Models;

use CodeIgniter\Model;

class Siswa_model extends Model
{
    protected $table = 'tb_siswa';
    protected $primaryKey = 'siswa_id';
    protected $protectFields = false;

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
        $param['siswa_password'] = md5($param['siswa_password']);

        if ($this->getNisCount($param['siswa_nis']) > 0) {
            return ['err' => 'NIS Sudah digunakan'];
        }

        $save = $this->insert($param);

        if (!$save) {
            return ['err' => 'Gagal'];
        }
        return $save;
    }

    public function getNisCount($param)
    {
        return $this->getWhere(['siswa_nis' => $param])->getNumRows();
    }
}
