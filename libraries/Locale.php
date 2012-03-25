<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
Should be in : /application/librairies
Must be declare in /application/config/autoload.php in the 
$autoload['libraries'] = array('your_other libraires','locale');
*/

class CI_Locale
{
		
	var $lang_allowed = array('en' => 'English','fr' => 'French','es' => 'Spanish'); // Languages translated
	var $lang_default = 'en'; // default language
	var $domain = 'yourdomain.com'; // name of the po files (I use the domain name , but It can be anything)
	var $lang ='';

		
		function __construct() {
		
		$CI =& get_instance();
		$CI->load->library('form_validation');
		$CI->load->library('session');
		$CI->form_validation->set_rules('lang_select', 'lang_select', 'exact_length[2]');
		$this->lang_default = 'en';
		
		
		
		if($CI->input->post('lang_select')) {
			
			if( $CI->form_validation->run() == TRUE)
			{
			$this->lang = $CI->input->post('lang_select')	;
			}
			else
			{
			$this->lang = 	$this->lang_default ;
			}
			
		$CI->session->set_userdata(array('lang' => $this->lang , 'lang_txt' => $this->lang_allowed[$this->lang]));
			
		}
		else
		{
		
			if(!$CI->session->userdata('lang')) {
				
				$this->lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0 , 2 );
				
				
				if(array_key_exists($this->lang,$this->lang_allowed)) {
		
				$CI->session->set_userdata(array('lang' => $this->lang , 'lang_txt' => $this->lang_allowed[$this->lang]));	
			
				}
				else
				{
				$CI->session->set_userdata(array('lang' => $this->lang_default , 'lang_txt' => $this->lang_allowed[$this->lang_default]));	
				}
				
			} 
		
		}
		

		

		
		
		
		$del = array($CI->session->userdata('lang') => $CI->session->userdata('lang_txt'));	
		$CI->session->set_userdata('lang_allowed',array_diff($this->lang_allowed,$del));
		
		setlocale(LC_ALL, $CI->session->userdata('lang').'_'.strtoupper($CI->session->userdata('lang')).'.UTF-8');
		setlocale(LC_NUMERIC, $CI->session->userdata('lang').'_'.strtoupper($CI->session->userdata('lang')).'.UTF-8');
		bindtextdomain(strtolower($this->domain),  APPPATH.'/language/locales/');
		textdomain(strtolower($this->domain));
		
	}	
}