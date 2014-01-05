<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class weiboController extends appController
{
    function __construct()
    {
        parent::__construct();
        include( 'saetv2.ex.class.php' );
        session_start();
    }

    function login()
    {
        $o = new SaeTOAuthV2( c('weibo_akey') , c('weibo_skey') );
        $code_url = $o->getAuthorizeURL( 'http://'.c('site_domain').'/?c=weibo&a=callback' , 'code'  );

        header('Location: ' . $code_url );
    }

    function callback()
    {
        $o = new SaeTOAuthV2( c('weibo_akey') , c('weibo_skey') );

        if (isset($_REQUEST['code'])) 
        {
            
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = 'http://'.c('site_domain').'/?c=weibo&a=callback' ;
            
            try 
            {
                $token = $o->getAccessToken( 'code', $keys ) ;
            } 
            catch (OAuthException $e) 
            {}

            $_SESSION['weibo_token'] = $token;

            // get user info
            $c = new SaeTClientV2( c('weibo_akey') , c('weibo_skey') , atoken());
            
            $info=$c->show_user_by_id(wbuid());

            if(strlen($info['name'])<1) return info_page('登入失败，请去吃点零食后重试');

            $_SESSION['weibo_uid'] = $info['name'];
            
            $_SESSION['uname'] = $info['name'];
            $_SESSION['avatar'] = $info['profile_image_url'];
            
            //print_r( $_SESSION );
            header("Location: /?a=index" );
        }   
    }

    function logout()
    {
        foreach( $_SESSION as $k => $v )
        {
            unset( $_SESSION[$k] );
        }

        return info_page('Logout');
    }
}
