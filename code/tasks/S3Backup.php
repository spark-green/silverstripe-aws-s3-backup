<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of S3Backup
 *
 * @author julian.smith
 */
class S3Backup extends BuildTask {
	
     protected $title = "S3 Backup";
     protected $description = "Backup Assets and Database to AWS S3";
	 protected $dir = ASSETS_PATH;
	 private $AccessKey;
	 private $SecretKey;
	 private $AWSFolder;
	 
     function run($request)
     {
		
		$this->AccessKey = SiteConfig::current_site_config()->AWSAccessKey;
		$this->SecretKey = SiteConfig::current_site_config()->AWSSecretKey;
		$this->AWSFolder = SiteConfig::current_site_config()->AWSFolder;
		 
		$files = $this->scan_dir($this->dir);
		foreach($files as $file){
			$extn = pathinfo(ASSETS_PATH.DIRECTORY_SEPARATOR.$file, PATHINFO_EXTENSION);
			if ( $extn == 'gz' ) {
				$this->S3copy($file);
			}
		}        
     }
	 
	private function scan_dir($dir) {
		$ignored = array('.', '..', '.svn', '.htaccess');

		$files = array();    
		foreach (scandir($dir) as $file) {
			if (in_array($file, $ignored)) continue;
			$files[$file] = filemtime($dir . '/' . $file);
		}

		arsort($files);
		$files = array_keys($files);

		return ($files) ? $files : false;
	}
	
	private function S3copy($file){
		
		$fileok = true;
		$s3 = new S3($this->AccessKey, $this->SecretKey);
		$list = $s3->getBucket($this->AWSFolder);
		foreach($list as $existing){
			if($existing['name'] === $file){
				$fileok = false;
			}
		}
		
		if($fileok){
			$put = $s3->putObject($s3->inputFile(ASSETS_PATH.DIRECTORY_SEPARATOR.$file),$this->AWSFolder,$file, S3::ACL_PRIVATE);
			if($put){
				echo $file." transferred to S3<br>"."\r\n";
			} else {
				echo $file." unable to be transferred to S3<br>"."\r\n";
			}
		} else {
			echo $file." already in S3<br>"."\r\n";
		}
		
		
	}
	 
}