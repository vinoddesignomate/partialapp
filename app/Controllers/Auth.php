<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{


	public function index()
	{
		$_API_KEY = '3bd84db3e14a8028efd6afb20789f1a9';
		//$_API_KEY = '982f6c48b902f50c799fe9d1561792dd';
		$_NGROK_URL = 'https://phpstack-877186-3039039.cloudwaysapps.com/public/index.php';
		$shop = $_GET['shop'];
		//$scopes = 'read_products,write_products';

		$scopes = 'read_products,write_products,read_orders,write_orders,read_script_tags,write_script_tags,read_content,write_content,read_themes,write_themes,read_customers,write_customers,read_price_rules,write_price_rules';
		$redirect_uri = $_NGROK_URL . '/token';
		$nonce = bin2hex(random_bytes(12));
		$access_mode = 'per-user';

		$oauth_url = 'https://' . $shop . '/admin/oauth/authorize?client_id=' . $_API_KEY . '&scope=' . $scopes . '&redirect_uri=' . urlencode($redirect_uri) . '&state=' . $nonce . '&grant_options[]=' . $access_mode;

		//echo $oauth_url;
		// die();
		//header("Location: " . $oauth_url);

		return redirect()->to($oauth_url);
	}

	public function token()
	{

		$userModel = new UserModel();
		$api_key = '3bd84db3e14a8028efd6afb20789f1a9';
		//$api_key = '982f6c48b902f50c799fe9d1561792dd';
		//$secret_key = 'fc011f332cbc7f2f912f958f53ff5c1e';
		$secret_key = '72c81202c02a79b664d7e6192f7a4f0f';
		//$secret_key = '66a2d7744c33d979e94aa1c303ff3b24';
		$parameters = $_GET;
		$shop_url = $parameters['shop'];
		$hmac = $parameters['hmac'];
		$parameters = array_diff_key($parameters, array('hmac' => ''));
		ksort($parameters);
		// print_r($parameters); die();
		$new_hmac = hash_hmac('sha256', http_build_query($parameters), $secret_key);

		if (hash_equals($hmac, $new_hmac)) {

			$access_token_endpoint = 'https://' . $shop_url . '/admin/oauth/access_token';
			$var = array(
				"client_id" => $api_key,
				"client_secret" => $secret_key,
				"code" => $parameters['code']
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $access_token_endpoint);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, count($var));
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($var));
			$response = curl_exec($ch);
			curl_close($ch);

			$response = json_decode($response, true);



			$countrows = $userModel->checktokens($parameters['shop']);
			if ($countrows < 1) {
				$curdate = date('Y-m-d');
				$userId = $userModel->insert_data(array(
					"shop_url" => $parameters['shop'],
					"access_token" => $response['access_token'],
					"scope" => $response['scope'],
					"expires_in" => $response['expires_in'],
					"associated_user_scope" => $response['associated_user_scope'],
					"associated_user_id" => $response['associated_user']['id'],
					"first_name" => $response['associated_user']['first_name'],
					"last_name" => $response['associated_user']['last_name'],
					"email" => $response['associated_user']['email'],
					"locale" => $response['associated_user']['locale'],
					"account_owner" => $response['associated_user']['account_owner'],
					"created" => $curdate,
				));




				//install js lib file
			} else {
				$userId = $userModel->update_data($parameters['shop'], array(
					"access_token" => $response['access_token'],
					"scope" => $response['scope'],
					"expires_in" => $response['expires_in'],
					"associated_user_scope" => $response['associated_user_scope'],
				));
			}

			echo "<script>top.window.location='https://" . $shop_url . "/admin/apps/partial-payment-app-1'</script>";
		} else {
			echo "it is not comming from shopify";
		}
	}
	
}
// echo "200 ok";
// exit();
