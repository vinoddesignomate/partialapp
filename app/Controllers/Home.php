<?php

namespace App\Controllers;
use App\Models\UserModel;
class Home extends BaseController
{

    function __construct()
    {
        helper(['form', 'url']);
        $session = \Config\Services::session();
        $this->user_model = new UserModel();
        
    }
    // public function index()
    // {
    //     $countrows = $this->user_model->get_data();
    //     print_r($countrows);
    //     return view('welcome_message');
    // }

    public function index()
    {

        $countrows = $this->user_model->checktokens($_GET['shop']);
        if ($countrows < 1) {
            return redirect()->to('https://phpstack-877186-3039039.cloudwaysapps.com/public/install?shop=' . $_GET['shop']);
        }

        $get_details = $this->user_model->get_tokens($_GET['shop']);
        $products =  $products = $this->common->rest_api('/admin/api/2021-01/products.json', array(), 'GET', $get_details->access_token, $_GET['shop']);


        $response = json_decode($products['body'], true);
        if (array_key_exists('errors', $response)) {
            echo esc("sorry but  i think there is an error. error is" . $response['errors']);

            return redirect()->to('https://phpstack-877186-3039039.cloudwaysapps.com/public/install?shop=' . $_GET['shop']);

            // header("Location: install.php?shop=" . $_GET['shop']);
            exit();
        } else {



            // echo view('templates/header');
            echo view('welcome_message');
            // echo view('templates/footer');
            //return view('welcome_message');
        }
    }
    
}
