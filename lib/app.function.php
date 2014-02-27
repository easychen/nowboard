<?php
$GLOBALS['rest_errors']['ARGS_ERROR'] = 10001;

/*
function nblog( $content , $tags = null )
{
    $baseurl = 'http://lazy.sinaapp.com/?a=api&data=' . u($content) ;

    if( $tags != null ) 
        $baseurl = $baseurl.'&ckeys='.u($tags) ;

    // SAE
    $url = $baseurl.'&source=528342357';
    // meituan
    //$url = $baseurl.'&source=33333456';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,3 ); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 5 ); 
    $response = curl_exec($ch);
    curl_close($ch);

}*/


function send_result( $data_array )
{
    $ret = array( 'errno' => 0 , 'errmsg' => 'success' , 'dataset' => $data_array );
    ajax_echo( json_encode( $ret ) );
    exit(0);
}

function send_error( $type , $info = null )
{
    $ret = array( 'errno' => $GLOBALS['rest_errors'][$type] , 'errmsg' => $info );
    ajax_echo( json_encode( $ret ) );
    exit(0);
}

function get_channel_url( $uid = null )
{
   
    if( !is_login() ) return false;
    /*
    $channel = new SaeChannel();
    $channel_name = 'NowBoard';
    $duration = 3600;
    $url = $channel->createChannel( wbuid().'.'.$channel_name,$duration);
    */
    if( $uid == null ) $uid = wbuid();
    
    $urlkey = 'nowboard-url-all';
    
    $mc = memcache_init();
    if( !$url = $mc->get($urlkey) )
    {
        $channel = new SaeChannel();
        $channel_name = $urlkey;
        $duration = 3600;
        $url = $channel->createChannel($channel_name,$duration);

        $mc->set( $urlkey , $url , 0 , 3600 );

    }
    
    return $url;
}


function wbuid()
{
    return $_SESSION['weibo_token']['uid'];
}

function atoken()
{
    return $_SESSION['weibo_token']['access_token'];
}

function is_login()
{
    return strlen( wbuid() ) > 0 && strlen( $_SESSION['uname'] ) > 0 && in_array(  wbuid() , c('user_weiboid') )  ;
}