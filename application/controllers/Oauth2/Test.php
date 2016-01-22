<?php
/**
* @package     Test
* @author      xiaocao
* @link        http://homeway.me/
* @copyright   Copyright(c) 2015
* @version     15.6.25
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
    function __construct(){
        @session_start();
        parent::__construct();
    }
//oauth.local/oauth2/authorize?response_type=code&client_id=testclient&redirect_uri=http://oauth.local/oauth2/test&state=ae5f8c93dc51d856d6536aec528c31c6f6450458scope=file node userinfo cloud
    function index(){
    	$this->load->view("oauth2/api");
    }


    
    /*
     请求用户授权Token
     参数说明
                    必选   类型及范围    说明
     client_id      true    string  申请应用时分配的AppKey。
     redirect_uri   true    string  授权回调地址，应用需与设置的回调地址一致。
     scope          false   string  申请scope权限所需参数，可一次申请多个scope权限，用空格分隔。 系统有默认权限
     state          false   string  用于保持请求和回调的状态，在回调时，会在Parameter中回传该参数。开发者可以用这个参数验证请求有效性，也可以记录用户请求授权页前的位置。这个参数可用于防止跨站请求伪造（CSRF）攻击
    */
    function get_code()
    {
        $_GET['scope'] = "file node userinfo cloud";
        $_GET['state'] = "123456";
        $_GET['client_id'] = "testclient";
        $_GET['redirect_uri'] = "http://oauth.local/oauth2/test";
        $_GET['response_type'] = "code";
        
        $this->load->library("Server", "server");
        $scope = $this->input->get("scope");
        $state = $this->input->get("state");
        $client_id = $this->input->get("client_id");
        $redirect_uri = $this->input->get("redirect_uri");
        $response_type = $this->input->get("response_type");
        if(!isset($_POST["authorized"])){
            $this->server->check_client_id();
            $data = array(
                "scope" => $scope,
                "state" => $state,
                "client_id" => $client_id,
                "redirect_uri" => $redirect_uri,
                "response_type" => $response_type,
            );
            $this->load->view("oauth2/authorize", $data);
        }else{
            $authorized = $this->input->post("authorized");
            if($authorized === "yes"){
                $this->server->authorize(($authorized === "yes"));
            }else{
                echo "error";
            }
        }
    }

    function token(){
        $this->server->authorization_code("yes");
    }
}
