<?php
if( !defined('IN') ) die('bad request');
include_once( AROOT . 'controller'.DS.'app.class.php' );

class defaultController extends appController
{
	function __construct()
	{
		parent::__construct();
		if( strtolower(g('a')) != 'api' ) session_start();
	}
	
	function index()
	{
		if( !is_login() )
		{
			info_page("<a href='/?c=weibo&a=login'>请先登入</a>");
			exit;
		}

		$data['title'] = $data['top_title'] = '首页';
		$data['url'] = get_channel_url();
		render( $data , 'web' , 'ban' );
	}

	function talk()
	{
		if( !is_login() ) return ajax_echo('NOT_LOGIN');

		$data = v('data');
		if( strlen( $data ) < 1 ) return send_error( ARGS_ERROR , 'data can\'t empty' );

		$timeline = date("Y-m-d H:i:s");

		

		// clear the data to display
		$action = 'display';
		$data = z(t($data));

		$ckey_array = array('everything','talk');

		// create channel
		$channel = new SaeChannel();
		// create channel
		$channel = new SaeChannel();

		if( is_array(c('talkman')) )
		{
			foreach( c('talkman') as $uid )
			{
				$channel_name = 'nowboard-url-'.$uid;
				if( $action == 'display' ) $data = z(t($data));

				foreach( $ckey_array  as $ckey )
				{
					$message = array( 'ckey' => $ckey , 'data' => $data , 'action' => $action , 'timeline' => $timeline , 'source' => 'NowTalk' , 'wbname' => $_SESSION['uname'] );
					$channel->sendMessage( $channel_name , json_encode($message) );
				}

			}
		}

		return send_result( 'send data to * ' . $channel_name . ' # ' . $ckeys . ' from NowTalk' );

	}

	function nblog()
	{
		nblog( '保存相关信息...' , 'talk,user,test' );
	}
	
	/*
	 * API 
	 * ckeys , 逗号分割的channel名称 ， 默认为everything
	 * data ， json格式的数据 ， 不能为空
	 * action ， 用于处理的函数，默认为 display
	 */
	function api()
	{
		$request = array
		(
			'source' => v('source'),
			'data' => v('data'),
			'ckeys' => v('ckeys'),

		);

		$queue = new SaeTaskQueue('nowboard'); 
		$queue->addTask("http://" .c('site_domain'). "/?a=apido", "request=".serialize($request) );
		$ret = $queue->push();

		return ajax_echo( 'sent.'.print_r( $ret , 1 ) );

	}

	function apido()
	{
		if( !isset( $_REQUEST['request']) || !$request = unserialize( $_REQUEST['request'] )  ) return send_error( ARGS_ERROR , 'request can\'t empty' );

		

		$source = $request['source'];
		if( strlen( $source ) < 1 ) return send_error( ARGS_ERROR , 'source can\'t empty' );

		if( !isset($GLOBALS['config']['whois'][$source]) || strlen( $GLOBALS['config']['whois'][$source] ) < 1 )
			return send_error( ARGS_ERROR , 'bad source id' );

		$data = $request['data'];
		if( strlen( $data ) < 1 ) return send_error( ARGS_ERROR , 'data can\'t empty' );

		$ckeys = z(t($request['ckeys']));
		if( strlen( $ckeys ) < 1 ) $ckey_array = array("everything");
		else
		{
			 $ckey_array = array("everything");
			 $keys = explode( ',' , $ckeys );
			if( is_array( $keys  ) ) $ckey_array = array_merge( $keys , $ckey_array );
		}
		
		

		$action = v('action');
		if( strlen( $action ) < 1 ) $action = "display";

		$timeline = date("Y-m-d H:i:s");

		// create channel
		$channel = new SaeChannel();

		if( is_array(c('talkman')) )
		{
			foreach( c('talkman') as $uid )
			{
				$channel_name = 'nowboard-url-'.$uid;
				if( $action == 'display' ) $data = z(t($data));

				foreach( $ckey_array  as $ckey )
				{
					$message = array( 'ckey' => $ckey , 'data' => $data , 'action' => $action , 'timeline' => $timeline , 'source' => $GLOBALS['config']['whois'][$source] );
					$channel->sendMessage( $channel_name , json_encode($message) );
				}

			}
		}

		
		
		return send_result( 'send data to *  # ' . $ckeys . ' from ' . $GLOBALS['config']['whois'][$source] );

	}
	
	
}
	