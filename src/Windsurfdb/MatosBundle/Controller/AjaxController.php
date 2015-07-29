<?php
namespace Windsurfdb\MatosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AjaxController extends Controller {
	private function boardIndexActionArrayReturn($boards_id, $boards_a) {
		$em = $this->getDoctrine()->getManager();
		$specs = $em->getRepository('WindsurfdbMatosBundle:BoardSpec')->findByBoards($boards_id);

		$imgurl = $this->container->get('windsurfdb_matos.url.image');

		$spec_array = array();
		foreach ($specs as $key => $value) {
			$spec_array[$key] = $value->ajaxOutput(array(
				'id',
				'smodele',
				'volume',
				'longueur',
				'largeur',
				'fin',
				'box',
				'poids',
				'techno',
				'prix',
				'voileMini',
				'voileMaxi',
				'infos'
			));
			$programmes = '';
			foreach ($value->getProgrammes() as $k => $v) {
				if($k != 0) {
					$programmes .= ' / ';
				}
				$programmes .= $v->getName();
			}
			$spec_array[$key]['programme'] = $programmes;
			$spec_array[$key]['annee'] = $boards_a[$value->getBoard()->getId()]->getAnnee();
			$spec_array[$key]['modele'] = $boards_a[$value->getBoard()->getId()]->getModele();
			$spec_array[$key]['marque'] = $boards_a[$value->getBoard()->getId()]->getMarque()->getName();
			$img = $value->getImages()[0];
			$spec_array[$key]['url'] = '';
			$spec_array[$key]['alt'] = '';
			if(is_object($img)) {
				$spec_array[$key]['url'] = $imgurl->validUrl($img->getUrl());
				$spec_array[$key]['alt'] = (string) $img->getAlt();
			}
		}
		return $spec_array;
	}
	public function boardIndexAction(Request $request) {
		$em = $this->getDoctrine()->getManager();

		if(null !== $request->request->get('marque_id')) {
			$id = (int) $request->request->get('marque_id');
			if($id != 0) {
				$marque = $em->getRepository('WindsurfdbMatosBundle:Marque')->findOneById($id);
				if (null === $marque) {
					throw new NotFoundHttpException("La marque '".$id."' n'existe pas.");
				}
			}
		}
		if(null !== $request->request->get('modele_name')) {
			$mn = $request->request->get('modele_name');
			if($mn != '0') {
				$modele_name = $mn;
			}
		}
		if(null !== $request->request->get('annee')) {
			$an = $request->request->get('annee');
			if($an != '0') {
				$annee = $an;
			}
		}

		if(isset($mn) || isset($an)) {
			if(isset($marque, $modele_name, $annee)) {
				$boards = $em->getRepository('WindsurfdbMatosBundle:Board')->findAllByAnneeAndModeleAndMarque($annee, $modele_name, $marque);
			}
			elseif(isset($marque, $modele_name)) {
				$boards = $em->getRepository('WindsurfdbMatosBundle:Board')->findAllByModeleAndMarque($modele_name, $marque);
			}
			elseif(isset($marque, $annee)) {
				$boards = $em->getRepository('WindsurfdbMatosBundle:Board')->findAllByAnneeAndMarque($annee, $marque);
			}
			elseif(isset($modele_name, $annee)) {
				$boards = $em->getRepository('WindsurfdbMatosBundle:Board')->findAllByAnneeAndModele($annee, $modele_name);
			}
			elseif(isset($marque)) {
				$boards = $em->getRepository('WindsurfdbMatosBundle:Board')->findAllByMarque($marque);
			}
			elseif(isset($modele_name)) {
				$boards = $em->getRepository('WindsurfdbMatosBundle:Board')->findAllByModele($modele_name);
			}
			elseif(isset($annee)) {
				$boards = $em->getRepository('WindsurfdbMatosBundle:Board')->findAllByAnnee($annee);
			}
			else {
				$boards = $em->getRepository('WindsurfdbMatosBundle:Board')->findAll();
			}
		}

		if(isset($id, $mn, $an)) {
			$boards_id = array();
			foreach ($boards as $value3) {
				$boards_a[$value3->getId()] = $value3;
				$boards_id[] = $value3->getId();
			}

			$spec_array = $this->boardIndexActionArrayReturn($boards_id, $boards_a);

			$response = array('spec' => $spec_array);
		}
		elseif (isset($id, $mn)) {
			$boards_id = array();
			$annees_array = array();
			foreach ($boards as $value3) {
				$boards_a[$value3->getId()] = $value3;
				$boards_id[] = $value3->getId();
				$annees_array[] = $value3->getAnnee();
			}

			$annees_array = array_unique($annees_array);
			rsort($annees_array);

			$spec_array = $this->boardIndexActionArrayReturn($boards_id, $boards_a);

			$response = array('spec' => $spec_array, 'annees'=>$annees_array);
		}
		elseif(isset($id)) {
			if(isset($marque)) {
				$modeles = $em->getRepository('WindsurfdbMatosBundle:Board')->findModelesAndAnneesByMarque($marque);
			}
			else {
				$modeles = $em->getRepository('WindsurfdbMatosBundle:Board')->findModelesAndAnnees();
			}

			$modeles_array = array();
			$annees_array = array();
			foreach ($modeles as $value) {
				$modeles_array[] = $value->getModele();
				$annees_array[] = $value->getAnnee();
			}
			$modeles_array = array_unique($modeles_array);
			sort($modeles_array);
			$annees_array = array_unique($annees_array);
			rsort($annees_array);

			$response = array('modeles' => $modeles_array, 'annees'=>$annees_array);
		}

		if(isset($response)) {
			return new JsonResponse($response);
		}
		else {
			throw new NotFoundHttpException("404");
		}
	}
}
