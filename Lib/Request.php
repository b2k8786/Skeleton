<?php

namespace lib;

class Request
{
    protected static function init()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        return $curl;
    }
    /**
     * Generate a post request to given URL
     * 
     * @param string $url
     * @param array $params
     * @param callable $success_callback   
     * @param callable $error_callback
     */
    static function post($url, $params, $success_callback = null, $error_callback = null)
    {
        $curl = self::init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $res = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if (!empty($err) && is_callable($error_callback))
            return $error_callback($err);
        else
            return $success_callback($res);
    }

    /**
     * Generate a get request to given URL
     * 
     * @param string $url
     * @param array $params
     * @param callable $success_callback   
     * @param callable $error_callback
     */
    static function get($url, $params, $success_callback = null, $error_callback = null)
    {
        $curl = self::init();
        curl_setopt($curl, CURLOPT_URL, $url . http_build_query($params));

        $res = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if (!empty($err) && is_callable($error_callback))
            return $error_callback($err);
        else
            return $success_callback($res);
    }

    /**
     * Generate a multipart post request with files included to given URL
     * 
     * @param string $url
     * @param array $params
     * @param array $files
     * @param callable $success_callback   
     * @param callable $error_callback
     */
    static function multipartPost($url, $params, $files = null, $success_callback = null, $error_callback = null)
    {
        if (!empty($files)) {
            foreach ($files as $key => $file) {
                $params[$key] = curl_file_create(
                    $file['tmp_name'],
                    $file['type'],
                    $file['name']
                );
            }
        }

        $curl = self::init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $res = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if (!empty($err) && is_callable($error_callback))
            return $error_callback($err);
        else
            return $success_callback($res);
    }
}
