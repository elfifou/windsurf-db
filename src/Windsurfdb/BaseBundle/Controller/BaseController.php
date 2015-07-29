<?php
namespace Windsurfdb\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller {
	public function indexAction() {
		return $this->render('WindsurfdbBaseBundle:Base:index.html.twig');
	}
	public function contactAction() {
		return $this->render('WindsurfdbBaseBundle:Base:contact.html.twig');
	}
	public function planDuSiteAction() {
		return $this->render('WindsurfdbBaseBundle:Base:plan_du_site.html.twig');
	}
}
