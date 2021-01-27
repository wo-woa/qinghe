<?php
namespace Common\Model;

use Think\Model;

// class MemberModel extends Model

class AdminModel extends Model
{

    /**
     * 获取随机位数数字
     * @param  integer $len 长度
     * @return string
     */
    protected static function _randString($len = 6)
    {
        $chars = str_repeat('0123456789', $len);
        $chars = str_shuffle($chars);
        $str   = substr($chars, 0, $len);
        return $str;
    }

    public function __construct()
    {
        parent::__construct();
        $this->merid  = 23329;
        $this->url    = 'http://test.openapi.1688sup.cn/';
        $this->seckey = '8db16e8cc8363ed4eb4c14f9520bcc32';
    }

    public function toorder($atype = 1, $telqq = '15890300373', $proid = 106, $ordno = 'ktest003')
    {
        $url      = $this->url . 'recharge/order';
        $postData = [
            'merchantId'      => $this->merid,
            'outTradeNo'      => $ordno,
            'productId'       => $proid,
            'rechargeAccount' => $telqq, //手机号
             'accountType'     => $atype, //1:手机号 2:QQ号
             'number'          => 1,
            'timeStamp'       => time(),
            'notifyUrl'       => WEBROOTURL . '/Lanse/callb',
        ];
        $sign = $this->makeSign($postData, $key);

        $postData['sign'] = $sign;

        $res = $this->cpost($url, $postData);
        $arr = kjsond($res);
        // dump($arr);
        // dump($res);die;

        if ($arr['code'] === '2000') {
            echo '下单成功，不代表充值成功';
            return true;
        } else {
            F(CONTROLLER_NAME . '_err' . ACTION_NAME . time() . rand(100000, 999999), $arr);
            return false;
        }
    }
    public function info()
    {
        //查询余额
        $url = $this->url . 'recharge/info';

        $postData = [
            'merchantId' => $this->merid,
            'timeStamp'  => time(),
        ];
        $sign = $this->makeSign($postData);

        $postData['sign'] = $sign;

        $res = $this->cpost($url, $postData);
        $arr = kjsond($res);
        if ($arr['code'] === '0000') {

            $amt = (floatval($arr['balance']));
            return D('Lsamt')->setamt($amt);
            echo 'succ';
        } else {

            F(CONTROLLER_NAME . '_err' . ACTION_NAME . time() . rand(100000, 999999), $arr);

            return false;
        }
    }
    public function product()
    {
        //产品
        $url = $this->url . 'recharge/product';

        $postData = [
            'merchantId' => $this->merid,
            'timeStamp'  => time(),
        ];
        $sign = $this->makeSign($postData);

        $postData['sign'] = $sign;

        $res = $this->cpost($url, $postData);
        $arr = kjsond($res);
        // dump($arr);

        if ($arr['code'] === '0000') {
            return ($arr['products']);
            // echo 'succ';
        } else {
            F(CONTROLLER_NAME . '_err' . ACTION_NAME . time() . rand(100000, 999999), $arr);
        }
    }

    public function cpost($url, $postData)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => http_build_query($postData),
            CURLOPT_HTTPHEADER     => array(
                "Content-Type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function makeSign($array)
    {
        ksort($array);
        $str = '';
        foreach ($array as $key => $value) {
            $str .= sprintf('%s=%s&', $key, $value);
        }
        return strtoupper(md5($str . 'key=' . $this->seckey));
    }

}
