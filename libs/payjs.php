<?php
class Payjs{
    private $url = 'https://cashier.ylwind.cn/qrcode.php';
    private $key = '';
    private $mchid = '';
    public function __construct($data=null,$mchid="",$key,$notify_url="") {
        $this->data = $data;
        $this->mchid = $mchid;
        $this->key = $key;
        $this->notify_url = $notify_url;
    }
    public function pay(){
        $data = $this->data;
        $data['type']='union';
        $data['pid'] = $this->mchid;
        $data['notify_url'] = $this->notify_url;
        $data['sign'] = $this->sign($data);
        $data['sign_type']='MD5';
        return $this->post($data, $this->url);
    }
    public function post($data, $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $rst = curl_exec($ch);
        curl_close($ch);
        return $rst;
    }
    public function sign($params)
    {
        ksort($params);

        $keyStr = '';
        foreach ($params as $key => $val) {
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            $keyStr .= "$key=$val&";
        }
        $keyStr = trim($keyStr,'&');

        $sign = md5($keyStr . $this->key);

        return $sign;
    }
}
?>