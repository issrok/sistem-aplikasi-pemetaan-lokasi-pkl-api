<?php

namespace App\Models;

use CodeIgniter\Model;

class Monitoring_model extends Model
{
    protected $table = 'tb_monitoring';
    protected $primaryKey = 'monitoring_id';
    protected $protectFields = false;
    // public function __construct()
    // {
    //     $this->set_table("tb_monitoring");
    //     $this->set_key_id("monitoring_id");
    //     $path              = str_replace('/api', '', FCPATH);
    //     $this->_path_image = $path . 'media/monitoring/';
    //     parent::__construct();
    // }

    public function upload_gambar($data, $id)
    {

        $get_photo_name = $this->upload_image($this->_path_image, 'monitoring_photo');

        $get_photo_exist = $this->get_by_id($id);

        if (!(empty($get_photo_exist['pkl_gambar'])) &&
            ($get_photo_exist['pkl_gambar'] !== 'no_image.jpg') &&
            file_exists($this->_path_image . $get_photo_exist['pkl_gambar']) &&
            (!empty($get_photo_name))
        ) {
            unlink($this->_path_image . $get_photo_exist['pkl_gambar']);
        }

        $get_photo_name = !empty($get_photo_name) ? $get_photo_name : $get_photo_exist['pkl_gambar'];

        $data['pkl_gambar'] = $get_photo_name;

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
