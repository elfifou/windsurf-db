<?php
namespace Windsurfdb\MatosBundle\Url;

class ImageUrl {
	public function __construct($baseUrl) {
		$this->baseUrl = $baseUrl;
	}
	public function validUrl($url) {
		if(!filter_var($url, FILTER_VALIDATE_URL)) {
			return $this->baseUrl.$url;
		}
		return $url;
	}
}
