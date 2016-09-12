<?php

namespace AppBundle\Twig;

class FileExtension extends \Twig_Extension{
    
	public function getFilters(){
        return array(
			new \Twig_SimpleFilter('getIconMimeType', array($this, 'getIconMimeType')),
			new \Twig_SimpleFilter('getFormatBytes', array($this, 'getFormatBytes'))
		);
    }	
	
	/*
	* @param String $mimeType
	* @return String
	*/
    public function getIconMimeType($mimeType){
		$icono = "";
		switch($mimeType){
			case "text/plain":
				$icono = "txt.png";
				break;
			case "application/pdf":
				$icono = "pdf.png";
				break;
			case "application/msword":
			case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
				$icono = "doc.png";
				break;
			case "application/vnd.google-apps.document":
				$icono = "google_doc.png";
				break;
		}
		return $icono;
	}
	
	/**
	* @param string $size
	* @param int $precision
	* @return string
	*/
	public function getFormatBytes($size, $precision = 0) { 
		$base = log($size, 1024);
		$suffixes = array('B','KB','MB','GB','TB');
		return round(pow(1024, $base - floor($base)), $precision) ." ". $suffixes[floor($base)] ." (".number_format($size,0,",",".")." bytes)"; 
	}

    public function getName(){
        return 'app_extension';
    }
}