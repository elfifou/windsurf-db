<?php
namespace Windsurfdb\BaseBundle\Trans;

class Test {
	private $_locale;
	public function __construct(\Symfony\Component\Translation\DataCollectorTranslator $translator, $localeDefault) {
		$this->_locale = $translator->getLocale();
		var_dump($this->_locale);
	}
	public function t() {
		return false;
	}
	
}
