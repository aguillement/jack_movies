<?php

namespace App;

class IMDbapi {
    public $result = array('status'=>'false','message'=>'Unknown error');
    private $api_key = '';
    private $url = 'http://imdbapi.net/api';
    private $proxy = 'http://proxy-sh.ad.campus-eni.fr:8080';

    public function __construct($api = false)
    {
        $this->api_key = $api;
    }

    public function get($id = false,$type = 'json')
    {
        $param = array(
            'key'=>$this->api_key,
            'id'=>$id,
            'type'=>$type
        );
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($param));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST  , 2);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function title($title = false,$type = 'json')
    {
        $param = array(
            'key'=>$this->api_key,
            'title'=>$title,
            'type'=>$type
        );
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($param));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST  , 2);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function search($keyword = '', $year = '',$page = 0,$type = 'json')
    {
        $param = array(
            'key'=>$this->api_key,
            'id'=>$keyword,
            'year'=>$year,
            'page'=>$page,
            'type'=>$type
        );
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($param));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST  , 2);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}