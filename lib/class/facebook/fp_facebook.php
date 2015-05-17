<?php

/*
*
*	@class: MyFacebook 
*	@description: All the facebook related function 
*	@author: Pramod Jodhani
*	@date: 15-Apr-2014
*	@Dependency: Facebook graph API (PHP) 
*
*
*/

require "facebook.php";

class MyFacebook
{

	private $app_id;
	private $app_secret;
	private $permissions;
	 
	public $exception;
	public $facebook;



	public function MyFacebook($access_token="")
	{
		global $fp_settings;
		$this->permissions 	= 'basic_info,publish_actions,publish_stream,manage_pages,email';
		$this->exception 	= "exception";
		$this->app_id 		= $fp_settings['ptf_app_id'];
		$this->app_secret 	= $fp_settings['ptf_api_key'];
		$this->config 		= array(
								  'appId' => $this->app_id,
								  'secret' => $this->app_secret,
								  'fileUpload' => true, // optional
								  'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
								);

		$this->facebook = new Facebook($this->config);

		if(empty($access_token)) {	
				$fp_settings['access_token'];
				$this->facebook->setAccessToken($fp_settings['access_token']);
				$this->facebook->getAccessToken();
				//pre($this->facebook);
				$this->fb_post("testing");
		}
		else {

				$this->facebook->setAccessToken($access_token);
		
		}	

	}

	function get_permissions()
	{
		return $this->permissions;
	}


	function fb_post($message , $picture = "" , $description ="" , $link = "")
	{
		global $fp_settings;
		try
		{
			//pre($this->config);
			$facebook = new Facebook($this->config);
			$facebook->setAccessToken($fp_settings['access_token']);
			$message = array(
	            'message' => $message,
	            'picture' => "",
	            'description' => "",
	            'link' => ""
	           );
			$result = $facebook->api('/me/feed','POST',$message);
			return $result['id'];
		}
		catch(Exception $e)
		{
			$this->exception = $e->getMessage();
			//pre($e);
		}
	}
	
	function fb_get_pages($fbuser = "")
	{

		global $facebook;
		if(empty($fbuser)) {	$fbuser = $facebook->getUser(); }

		$fql_query = 'SELECT page_id, name, page_url FROM page WHERE page_id IN (SELECT page_id FROM page_admin WHERE uid='.$fbuser.')';
		$postResults = $facebook->api(array( 'method' => 'fql.query', 'query' => $fql_query ));
		return $postResults;
	
	}

	function post_to_page($page_id )
	{
		
		global $facebook;

		$user = $facebook->getUser();

		if ($user) 
		{
			try 
			{
				$page_info = $facebook->api("/$page_id?fields=access_token");

				if( !empty($page_info['access_token']) ) 
				{
					$args = array('access_token'  	=> $page_info['access_token'],);
					
					if(isset($_GET['message']))
					{
						$args['message'] 				= $_GET['message'];
					}
					if(isset($_GET['url']))
					{
						$args['link'] 					= $_GET['url'];
					}					
					if(isset($_GET['datetime']))
					{
						//date_default_timezone_set("Asia/Calcutta"); 
						$d1=new DateTime($_GET['datetime']);
						$d1->setTimezone(new DateTimeZone('Asia/Calcutta'));
						$timestamp = $d1->getTimestamp();
						$timestamp -= (5.5)*60*60;
						
						//exit(0);
						//$combinedDT = date('Y-m-d H:i:s', strtotime("$date $time"));
						$args['scheduled_publish_time'] = $timestamp;
						$args['published'] = false;
					}
					if(isset($_GET['datetime_relative']))
					{
						$duration 	= $_GET['datetime_relative'];
						$difference = $duration*60*60; //hour to second
						$timestamp 	= time();
						$timestamp += $difference;
						//$timestamp -= (5.5)*60*60;
						$timestamp;
						//exit(0);
						//$combinedDT = date('Y-m-d H:i:s', strtotime("$date $time"));
						$args['scheduled_publish_time'] = $timestamp;
						$args['published'] = false;
					}

					
					
					if(isset($_GET['picture']))
					{
						
						$args['url'] = $_GET['picture'];
						$post_id = $facebook->api("/$page_id/photos","post",$args);
					}
					else
					{
						$post_id = $facebook->api("/$page_id/feed","post",$args);
					}
					if(!empty($post_id))
					{
						if(isset($_GET['savepost']))
						{
							save_fb_post();
						}
					}
					return $post_id;
				}
			}
			catch(Exception $e)
			{
				$this->exception = $e->getMessage();
				wp_redirect( site_url("post/?error=".urlencode($this->exception)) );
			} 
		}


	}


	public function show_error()
	{
		echo $this->exception;
	}

	function validate_permission()
	{
		global $fp_settings;
		$this->facebook->setAccessToken($fp_settings['access_token']);
		//pre($this->facebook);
		/*if(!$this->facebook->getAccessToken()) {
			return false;
		}*/
		try
		{
			$permissions = $this->facebook->api('/me/permissions');
			$required_permission = explode( ',' , $this->permissions); //explode(delimiter, string)
			$return = true;
			foreach($required_permission as $reqp)
			{
				if(!array_key_exists( $reqp , $permissions['data'][0]))
				{
					$return = false;
				}
			}
			//pre($permissions);
			return $return;
			
		} catch (Exception $e) {
			//echo $e->getMessage();
			//wp_redirect( site_url( "/?retry=unauthorized" ) );
			//pre($e);
			return false;
		}
	}



}

$fp_facebook = new MyFacebook();