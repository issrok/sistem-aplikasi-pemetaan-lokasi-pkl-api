<?php

namespace App\Models;

use CodeIgniter\Model;

class Harian_model extends Model
{
    protected $table = 'tb_laporan_harian';
    protected $primaryKey = 'harian_id';
    protected $protectFields = false;
}
