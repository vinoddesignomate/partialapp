<?php

namespace App\Controllers;
use App\Models\UserModel;

class Datastore extends BaseController
{
    
	 public function index()
    {
		
		
		/* $_API_KEY = 'c848a59d8fb41393b8da720493fbfd47';
		$_NGROK_URL = 'https://bigthinxapp.herokuapp.com/public/index.php';
		$shop = $_GET['shop'];
		$scopes = 'read_products,write_products,read_orders,write_orders,read_script_tags,write_script_tags,read_content,write_content,read_themes,write_themes';
		$redirect_uri = $_NGROK_URL . '/token';
		$nonce = bin2hex( random_bytes( 12 ) );
		$access_mode = 'per-user';

		$oauth_url = 'https://'.$shop.'/admin/oauth/authorize?client_id='.$_API_KEY.'&scope='.$scopes.'&redirect_uri='.urlencode($redirect_uri).'&state='.$nonce.'&grant_options[]='.$access_mode;

		//echo $oauth_url;
		// die();
		//header("Location: " . $oauth_url);

		return redirect()->to($oauth_url);  */



    }
	
	public function collectionsync()
    {
		
        

    }
	
	public function rest_api($api_endpoint, $query = array(), $method = 'GET')
    {
        $url = 'https://' . $this->shop_url . $api_endpoint;

        if (in_array($method, array('GET', 'DELETE')) && !is_null($query)) {

            $url = $url . '?' . http_build_query($query);
        }
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
        if (!is_null($this->access_token)) {
            $headers[] = "X-Shopify-Access-Token: " . $this->access_token;
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
            if (is_array($query)) $query = http_build_query($query);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        }
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
}
