<?php

namespace App\Controllers\Api;

use App\Models\Jarak_model;
use App\Controllers\BaseController;

class Jarak extends BaseController
{
    protected $modelName = Jarak_model::class;

    public function jarak_pkl()
    {
        $lat  = $this->request->getVar('lat');
        $long = $this->request->getVar('long');

        $result = $this->model->where("pkl_kode_status", "1")->findAll();
        if (!$result) {
            return $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
        }

        foreach ($result as $key => $value) {
            $result[$key]['pkl_jarak'] = $this->jarak($lat, $long, $value['pkl_latitude'], $value['pkl_longitude'])['jarak'];
            $result[$key]['pkl_waktu'] = $this->jarak($lat, $long, $value['pkl_latitude'], $value['pkl_longitude'])['waktu'];
        }
        return $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
    }

    public function jarak($lat1, $long1, $lat2, $long2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $lat1 . "," . $long1 . "&destinations=" . $lat2 . "," . $long2 . "&mode=driving&language=pl-PL&key=AIzaSyD9Vo4aSy47VJdikqwNpqYj9SYIcHTZmIc";
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $respond = curl_exec($ch);
        curl_close($ch);
        $respond_a = json_decode($respond, true);
        $dist      = $respond_a['rows'][0]['elements'][0]['distance']['text'];
        $time      = $respond_a['rows'][0]['elements'][0]['duration']['text'];

        return array('jarak' => $dist, 'waktu' => $time);
    }

    public function jarak_pkl_limit($id_guru)
    {

        $lat    = $this->request->getVar('lat');
        $long   = $this->request->getVar('long');
        $result = $this->model->jarak_pkl_limit($id_guru);
        if (!$result) {
            return $this->respond(array("status" => false, "message" => "tidak ditemukan", 'data' => null));
        }

        foreach ($result as $key => $value) {
            $result[$key]['pkl_jarak'] = $this->jarak($lat, $long, $value['pkl_latitude'], $value['pkl_longitude'])['jarak'];
            $result[$key]['pkl_waktu'] = $this->jarak($lat, $long, $value['pkl_latitude'], $value['pkl_longitude'])['waktu'];
        }

        return $this->respond(array("status" => true, "message" => "sukses", "data" => $result));
    }

}
