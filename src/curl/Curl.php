<?php
namespace LwtHelper\curl;

class Curl
{

    /**
     * 签名规则
     * @param type $appkey
     * @param type $appsecret
     * @param type $params
     * @param type $method
     * @param type $requestUri
     * @param type $authTimestamp
     * @return type
     */
    protected function generateSign($appkey, $appsecret, $params = array(), $method = '', $requestUri = '', $authTimestamp = 0)
    {
        $sign = $method . '#' . $requestUri . '#' . $authTimestamp . '#';
        ksort($params, SORT_STRING);
        $sign .= ($appkey . '#omg#' . $appsecret . '#');
        foreach ($params as $k => $v)
        {
            $sign .= ($k . '=' . $v);
        }
        return md5($sign);
    }

    public static function sendApiPost($appkey, $appsecret, $url, $token = '', $data = array(), $headers = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);        //设置url
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   //设置开启重定向支持
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //最大超时30s
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $tHeaders = array();

        unset($headers['Auth-Appkey']);
        $tHeaders[] = 'Auth-Appkey:' . $appkey;
        unset($headers['Auth-Timestamp']);
        $timestamp  = time();
        $tHeaders[] = 'Auth-Timestamp:' . $timestamp;
        unset($headers['Auth-Sign']);
        $info       = parse_url($url);
        $tHeaders[] = 'Auth-Sign:' . self::generateSign($appkey, $appsecret, $data, 'POST', $info['path'], $timestamp);
        unset($headers['Auth-Token']);
        $tHeaders[] = 'Auth-Token:' . $token;

        foreach ($headers as $k => $v)
        {
            $tHeaders[] = $k . ":" . $v;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $tHeaders);

        curl_setopt($ch, CURLOPT_POST, 1);
        $output = curl_exec($ch);  //执行
        curl_close($ch);
        return $output;
    }

    public static function sendApiGet($appkey, $appsecret, $url, $token = '', $headers = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);        //设置url
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   //设置开启重定向支持
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //最大超时30s
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

        $tHeaders = array();

        unset($headers['Auth-Appkey']);
        $tHeaders[] = 'Auth-Appkey:' . $appkey;
        unset($headers['Auth-Timestamp']);
        $timestamp  = time();
        $tHeaders[] = 'Auth-Timestamp:' . $timestamp;
        unset($headers['Auth-Sign']);
        $info       = parse_url($url);
//    var_dump($info);
        parse_str($info['query'], $data);
        $tHeaders[] = 'Auth-Sign:' . self::generateSign($appkey, $appsecret, $data, 'GET', $info['path'], $timestamp);
        unset($headers['Auth-Token']);
        $tHeaders[] = 'Auth-Token:' . $token;

        foreach ($headers as $k => $v)
        {
            $tHeaders[] = $k . ":" . $v;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $tHeaders);

        $output = curl_exec($ch);  //执行
        curl_close($ch);
        return $output;
    }

    public static function sendGet($url, $headers = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);        //设置url
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   //设置开启重定向支持
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //最大超时30s
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

        foreach ($headers as $k => $v)
        {
            $tHeaders[] = $k . ":" . $v;
        }
        if (!empty($tHeaders)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $tHeaders);
        }


        $output = curl_exec($ch);  //执行
        curl_close($ch);
        return json_decode($output, true);
    }

    public static function sendPost($url, $data = array(), $postDataJson = false, $headers = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);        //设置url
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   //设置开启重定向支持
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //最大超时30s
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

        if (!empty($data)) {
            $postData = $postDataJson == true ? json_encode($data) : http_build_query($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }

        $tHeaders = array();
        if (!empty($headers)) {
            foreach ($headers as $k => $v)
            {
                $tHeaders[] = $k . ":" . $v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $tHeaders);
        }

        curl_setopt($ch, CURLOPT_POST, 1);

        $output = curl_exec($ch);  //执行
        curl_close($ch);
        return json_decode($output, true);
    }

    public static function rawHttpPostRequest($url, $host, $data = array(), $headers = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: " . $host));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, 58);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        if (!empty($headers)) {
            $tHeaders = array();
            foreach ($headers as $k => $v)
            {
                $tHeaders[] = $k . ":" . $v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $tHeaders);
        }

        // curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = curl_exec($ch);
        return $result;
    }

    public static function createUrl($parmas = array())
    {
        $data = http_build_query($parmas);
        return $data;
    }

}
