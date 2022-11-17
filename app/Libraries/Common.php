<?php

namespace App\Libraries;

class Common
{
    public function __construct()
    {
        //$this->CI = &get_instance();
        //load library
        // $this->CI->load->library('form_validation');
        // $this->CI->load->library('session');
        // $this->CI->load->library('email');
        // $this->CI->load->library('pagination');
        //load model

        //$this->CI->load->model('common_model');
    }

    public function rest_api($api_endpoint, $query = array(), $method = 'GET', $access_token, $shop_url)
    {
        $url = 'https://' . $shop_url . $api_endpoint;

        if (in_array($method, array('GET', 'DELETE')) && !is_null($query)) {

            $url = $url . '?' . http_build_query($query);
        }
        //echo "curl=".$url;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        $headers[] = "";
        //if (!is_null($access_token)) {
        $headers[] = "X-Shopify-Access-Token: " . $access_token;
        // $headers[] = "X-Shopify-Access-Token: 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";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //}

        if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
            if (is_array($query)) $query = http_build_query($query);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        }
        $response = curl_exec($curl);
        // echo "response=".$response;
        $error = curl_errno($curl);
        $error_msg  = curl_error($curl);
        curl_close($curl);
        if ($error) {
            return $error_msg;
        } else {
            $response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);

            $headers = array();
            $headers_content = explode("\n", $response[0]);
            $headers['status'] = $headers_content[0];

            array_shift($headers_content);

            foreach ($headers_content as $conent) {
                // $data = explode(":", $conent);
                $data = explode(":", $conent, 2);
                $headers[trim($data[0])] = trim($data[1]);
            }
            return array("headers" => $headers, "body" => $response[1]);
        }
    }
    public function graphql_api($query = array(), $shop_url, $acc_token)
    {

        $url = 'https://' . $shop_url . '/admin/api/2022-04/graphql.json';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $headers[] =  "";
        $headers[] =  "Content-Type: application/json";
        if ($acc_token) $headers[] = "X-Shopify-Access-Token: " . $acc_token;

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($query));
        curl_setopt($curl, CURLOPT_POST, true);

        $response = curl_exec($curl);
        $error = curl_errno($curl);
        $error_msg  = curl_error($curl);
        curl_close($curl);
        if ($error) {
            return $error_msg;
        } else {
            $response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);

            $headers = array();
            $headers_content = explode("\n", $response[0]);
            $headers['status'] = $headers_content[0];

            array_shift($headers_content);

            foreach ($headers_content as $conent) {
                $data = explode(":", $conent);
                $headers[trim($data[0])] = trim($data[1]);
            }
            return array("headers" => $headers, "body" => $response[1]);
        }
    }
    function str_btwn($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    function upload_size_chart($postdata)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://shopifyapp.lyfsize.me/cgcolors/v2/createStore",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postdata),
            CURLOPT_HTTPHEADER => array(
                "authorization: -",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 7756231e-2a41-9daa-32d1-56703b369959"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return  $err;
        } else {
            return $response;
        }
    }

    function update_sizechart_api($updatepostdata)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://shopifyapp.lyfsize.me/cgcolors/v2/updateSizeChart",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($updatepostdata),
            CURLOPT_HTTPHEADER => array(
                "authorization: -",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 7756231e-2a41-9daa-32d1-56703b369959"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return  $err;
        } else {
            return $response;
        }
    }

    function call_curl_api($method, $url, $data, $send_type)
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    if ($send_type == 'json') {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                    } else {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    }
                }

                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $result;
    }
    function get_size_chart_api($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://shopifyapp.lyfsize.me/cgcolors/v2/generateUserSize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"emailId\"\r\n\r\n" . $data['emailId'] . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"storeId\"\r\n\r\n" . $data['storeId'] . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"vendorId\"\r\n\r\n" . $data['vendorId'] . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"sizeChartId\"\r\n\r\n" . $data['sizeChartId'] . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"productId\"\r\n\r\n" . $data['productId'] . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: 3b288ef7-fcce-409a-f9a8-93fe38196ac1"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }



    function html_escape_char($var, $double_encode = TRUE)
    {
        if (empty($var)) {
            return $var;
        }

        if (is_array($var)) {
            foreach (array_keys($var) as $key) {
                $var[$key] = $this->html_escape_char($var[$key], $double_encode);
            }

            return $var;
        }

        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8', $double_encode);
    }

    function get_qr_token($postparms)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://shopifyapp.lyfsize.me/cgcolors/v2/generateQRToken?emailId=".$postparms['email']."&heightInCms=".$postparms['height']."&gender=".$postparms['gender']."",
           // CURLOPT_URL => 'https://shopifyapp.lyfsize.me/cgcolors/v2/generateQRToken?emailId=test@gmail.com&heightInCms=144&gender=male',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
       // $return_response = json_decode($response);
       // return $return_response;

        // if ($err) {
        //     echo  $err;
        // } else {
        //     echo "response=" . $response;
        //     $return_response = json_decode($response);
        //     return $return_response;
        // }
    }
}
