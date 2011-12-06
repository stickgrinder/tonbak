<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Po extends MY_Controller {

private $files_txt  = 'scan_files.txt' ; // Write file  Scan all the php files of application folder
private $files_po   = 'update_po.po' ; // Write the file with all the strings to translate
private $locales    = '/language/locales/'; // directory  where each languages are
private $ori_po     = 'yourdomain.com.po'; // po file name
private $ori_mo     = 'yourdomain.com.mo'; // po file name
private $path_lang  = array(
			    'en' => 'en_EN/LC_MESSAGES/',
			    'es' => 'es_ES/LC_MESSAGES/',
			    'fr' => 'fr_FR/LC_MESSAGES/'); // languages available
private $string_txt;
private $string_po;
private $by_mail    = 1 ; // send the PO files by email 1 = enable 
private $send_po_mail = array(
			      'from' => 'yourmail@mail.com',
			      'to'=>'yourmail@mail.com',
			      'subject'=>'PO to translate',
			      'message'=>'Please find attached the PO to translate in'); // parameters to receive PO by mails.



	function create_po(){
	
	$this->string_txt = APPPATH.$this->locales.$this->files_txt ;
	$this->string_po = APPPATH.$this->locales.$this->files_po ;
		
		
		if($this->scan_file() == 0) {
			if(file_exists($this->string_txt)) {
				if($this->do_po_file() == 0) {	
					if(file_exists($this->string_po)) {
					
					$out =	$this->merge_po_file() ;
					$this->clean_after_create();
					foreach($out as $k => $v) {
						
						if($v == 1) {
							
							echo 'Success for '.$k.'<br/>' ;
						
							if($this->by_mail == 1) { $this->send_mail_po($k); }
						}
						else
						{
							echo 'Fail for '.$k.'<br/>' ; 
						}
						
					}
					
					
					}
					else { echo 'fail merge'.$result ; }
				
					
				}
				else { echo 'fail exec po'.$result ;}
				
			}
			else { echo 'fail file txt not exist';}
		}
		else
		{ echo 'fail exec txt'.$result ; }
	
	}

      // Write a file with all the php files from application folder and subfolder
	function scan_file() {
		
		$cmd = 'find '.APPPATH.' -name "*.php"  > '.APPPATH.$this->locales.$this->files_txt ;	
		$exec = exec( $cmd ,$output ,$result);		
		
	return $result ;	
		
	}
	
	// Write .po file with all the tags _('string to translate')
	function do_po_file() {
		
		$cmd = 'xgettext -f '.$this->string_txt.' -o '.$this->string_po ;
		$exec = exec( $cmd ,$output ,$result);		
		
	return $result ;	
		
	}
	
	// Merge the update .po with the existing .po and replace it (keep the part already translated)
	function merge_po_file() {
		
		foreach($this->path_lang as $k => $v) {
			$path = APPPATH.$this->locales.$v;	
			$cmd = 'msgmerge -N '.$path.$this->ori_po.' '.$this->string_po.' > '.$path.$this->files_po ;
			$exec = exec( $cmd ,$output ,$result);
					
			if($result == 0) {
						
			$return[$k] = 1;
			$cmd ='mv '.$path.$this->files_po.' '.$path.$this->ori_po ;
			$exec = exec( $cmd ,$output ,$result);
			
			}			
			else {	
			$return[$k] = 0 ;
	
			}
					
					
		}
		

		
	return $return ;	
		
	}
	
	// Send one mail per PO with po attached
	function send_mail_po($lang_type) {
	$this->load->library('email');	
	$this->email->initialize(array('mailtype' => 'html', 'charset' => 'utf-8'));
	$this->email->clear(TRUE);
	$this->email->from($this->send_po_mail['from']);
	$this->email->to($this->send_po_mail['to']);
	$this->email->subject($this->send_po_mail['subject'].' : '.$lang_type);
	$this->email->message($this->send_po_mail['message'].' : '.$lang_type);
	$this->email->attach(APPPATH.$this->locales.$this->path_lang[$lang_type].$this->ori_po);
		
		
	$this->email->send();
	
		
		
	}
	
	// delete temporaty file 
	function clean_after_create() {
	unlink($this->string_txt);
	unlink($this->string_po);	
	}
	
	function create_mo(){
		
		foreach($this->path_lang as $k => $v) {
			$path = APPPATH.$this->locales.$v;	
			$cmd = 'msgfmt -cv -o '.$path.$this->ori_mo.' '.$path.$this->ori_po ;
			$exec = exec( $cmd ,$output ,$result);
			
			if($result == 0) {
				
				echo '.mo ok for '.$k.'<br/>';
			}
		}
		
	return $result ;
		
		
	}


}
