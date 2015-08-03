<?php
namespace Windsurfdb\MatosBundle\Controller;

use Windsurfdb\MatosBundle\Entity\Board;
use Windsurfdb\MatosBundle\Entity\Image;
use Windsurfdb\MatosBundle\Entity\BoardSpec;
use Windsurfdb\MatosBundle\Form\BoardType;
use Windsurfdb\MatosBundle\Form\BoardSpecType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class BoardController extends Controller {
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$marques = $em->getRepository('WindsurfdbMatosBundle:Marque')->findByType('wb');
		$modeles = $em->getRepository('WindsurfdbMatosBundle:Board')->findAllModelesOnly();
		$annees = $em->getRepository('WindsurfdbMatosBundle:Board')->findAllAnneesOnly();

		return $this->render('WindsurfdbMatosBundle:Board:index.html.twig', array('marques'=>$marques, 'modeles'=>$modeles, 'annees'=>$annees));
	}

	public function detailAction($slug, Request $request) {
		$em = $this->getDoctrine()->getManager();
		$imgurl = $this->container->get('windsurfdb_matos.url.image');

		$board = $em->getRepository('WindsurfdbMatosBundle:Board')->getModeleWithMarque($slug);
		if (null === $board) {
			throw new NotFoundHttpException("La planche '".$slug."' n'existe pas.");
		}

		$old_bdd = false;
		if($board->getOldBdd()) {
			$old_bdd = true;
		}

		$specs = $em->getRepository('WindsurfdbMatosBundle:BoardSpec')->findByBoard($board);
		foreach ($specs as $key => $value) {
			$img = $value->getImages();
			if(isset($img)) {
				foreach ($img as $k => $val) {
					if(is_object($val)) {
						$specs[$key]->getImages()[$k]->setUrl($imgurl->validUrl($val->getUrl()));
					}
				}
			}
		}

		return $this->render('WindsurfdbMatosBundle:Board:detail.html.twig', array(
			'board' => $board,
			'specs' => $specs,
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function importAnotherYearAction($slug, Request $request) {
		$em = $this->getDoctrine()->getManager();
		$board = $em->getRepository('WindsurfdbMatosBundle:Board')->getModeleWithMarque($slug);
		if (null === $board) {
			throw new NotFoundHttpException("La planche '".$slug."' n'existe pas.");
		}

		$marque = $board->getMarque();
		$modele = $board->getModele();

		if($request->isMethod('POST')) {
			$annee = $request->request->get('annee');
			$new_board = $em->getRepository('WindsurfdbMatosBundle:Board')->findOneBy(array('modele'=>$modele, 'marque'=>$marque, 'annee'=>$annee));
			$specs = $em->getRepository('WindsurfdbMatosBundle:BoardSpec')->findByBoard($new_board);
			$new_specs = array();
			foreach ($specs as $key => $spec) {
				$new_specs[$key] = clone $spec;
				$new_specs[$key]->setBoard($board);
				$new_specs[$key]->setDate(new \Datetime());
				$em->detach($new_specs[$key]);
				$em->persist($new_specs[$key]);
			}
			$em->flush();
			$request->getSession()->getFlashBag()->add('admin_info', 'Spécifications importées avec succès !');
			return $this->redirect($this->generateUrl('windsurfdb_matos_board_detail', array('slug' => $slug)));
		}

		$boards = $em->getRepository('WindsurfdbMatosBundle:Board')->findBy(array('modele'=>$modele, 'marque'=>$marque), array('annee'=>'desc'));

		$annees = array();
		foreach ($boards as $value) {
			if($value!=$board) {
				$annees[] = $value->getAnnee();
			}
		}

		return $this->render('WindsurfdbMatosBundle:BoardImport:spec_import_another_year.html.twig', array(
			'board' => $board,
			'annees' => $annees
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function specAddAction($slug, Request $request) {
		$em = $this->getDoctrine()->getManager();

		$board = $em->getRepository('WindsurfdbMatosBundle:Board')->getModeleWithMarque($slug);
		if (null === $board) {
			throw new NotFoundHttpException("La planche '".$slug."' n'existe pas.");
		}

		/* ------------------------------------------------------ */
		$spec = new BoardSpec();
		$spec->setBoard($board);
		$form = $this->createForm(new BoardSpecType(), $spec);
		if ($form->handleRequest($request)->isValid()) {
			$em->persist($spec);
			$em->flush();
			$request->getSession()->getFlashBag()->add('admin_info', 'Spécification ajoutée avec succès !');
			//return $this->redirect($this->generateUrl('windsurfdb_matos_board_detail', array('slug' => $slug)));
			$data = $form->getData();
		}
		else {
			$data = new BoardSpec();
		}
		/* ------------------------------------------------------ */
		return $this->render('WindsurfdbMatosBundle:Board:spec_add.html.twig', array(
			'board' => $board,
			'data' => $data,
			'form' => $form->createView(),
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function specModifAction($slug, $id, Request $request) {
		$em = $this->getDoctrine()->getManager();

		$board = $em->getRepository('WindsurfdbMatosBundle:Board')->getModeleWithMarque($slug);
		if (null === $board) {
			throw new NotFoundHttpException("La planche '".$slug."' n'existe pas.");
		}

		/* ------------------------------------------------------ */
		$spec = $em->getRepository('WindsurfdbMatosBundle:BoardSpec')->findOneById($id);
		if (null === $spec) {
			throw new NotFoundHttpException("La spécification '".$spec."' n'existe pas.");
		}
		$form = $this->createForm(new BoardSpecType(), $spec);
		if ($form->handleRequest($request)->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('admin_info', 'Spécification modifiée avec succès !');
			return $this->redirect($this->generateUrl('windsurfdb_matos_board_detail', array('slug' => $slug)));
		}
		/* ------------------------------------------------------ */
		return $this->render('WindsurfdbMatosBundle:Board:spec_modif.html.twig', array(
			'board' => $board,
			'spec'=> $spec,
			'data'=> $spec,
			'form' => $form->createView(),
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function specDeleteAction($slug, $id, Request $request) {
		$em = $this->getDoctrine()->getManager();

		$board = $em->getRepository('WindsurfdbMatosBundle:Board')->getModeleWithMarque($slug);
		if (null === $board) {
			throw new NotFoundHttpException("La planche '".$slug."' n'existe pas.");
		}

		$spec = $em->getRepository('WindsurfdbMatosBundle:BoardSpec')->findOneById($id);
		if (null === $spec) {
			throw new NotFoundHttpException("La spécification '".$spec."' n'existe pas.");
		}

		$form = $this->createFormBuilder()->add('save', 'submit')->getForm();
		if ($form->handleRequest($request)->isValid()) {
			$em->remove($spec);
			$em->flush();

			$request->getSession()->getFlashBag()->add('admin_info', 'Spécification supprimée avec succès !');
			return $this->redirect($this->generateUrl('windsurfdb_matos_board_detail', array('slug' => $slug)));
		}

		return $this->render('WindsurfdbMatosBundle:Board:spec_delete.html.twig', array(
			'board' => $board,
			'spec'=> $spec,
			'form' => $form->createView(),
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function boardAddAction($slug_marque = null, Request $request) {
		$em = $this->getDoctrine()->getManager();

		$board = new Board();

		if($slug_marque != null) {
			$marque = $em->getRepository('WindsurfdbMatosBundle:Marque')->findOneBySlug($slug_marque);
			if (null !== $marque) {
				$board->setMarque($marque);
			}
		}

		$form = $this->createForm(new BoardType(), $board);
		if ($form->handleRequest($request)->isValid()) {
			$em->persist($board);
			$em->flush();
			$request->getSession()->getFlashBag()->add('admin_info', 'Planche ajoutée avec succès !');
			return $this->redirect($this->generateUrl('windsurfdb_matos_board_detail', array('slug' => $board->getSlug())));
		}

		return $this->render('WindsurfdbMatosBundle:Board:board_add.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function boardModifAction($slug, Request $request) {
		$em = $this->getDoctrine()->getManager();

		$board = $em->getRepository('WindsurfdbMatosBundle:Board')->getModeleWithMarque($slug);
		if (null === $board) {
			throw new NotFoundHttpException("La planche '".$slug."' n'existe pas.");
		}

		$form = $this->createForm(new BoardType(), $board);
		if ($form->handleRequest($request)->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('admin_info', 'Planche modifiée avec succès !');
			return $this->redirect($this->generateUrl('windsurfdb_matos_board_detail', array('slug' => $board->getSlug())));
		}

		return $this->render('WindsurfdbMatosBundle:Board:board_modif.html.twig', array(
			'form' => $form->createView(),
			'board' => $board,
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function boardDeleteAction($slug, Request $request) {
		$em = $this->getDoctrine()->getManager();

		$board = $em->getRepository('WindsurfdbMatosBundle:Board')->getModeleWithMarque($slug);
		if (null === $board) {
			throw new NotFoundHttpException("La planche '".$slug."' n'existe pas.");
		}

		$marque = $board->getMarque()->getName();

		$form = $this->createFormBuilder()->add('save', 'submit')->getForm();
		if ($form->handleRequest($request)->isValid()) {
			$em->remove($board);
			$em->flush();
			$request->getSession()->getFlashBag()->add('admin_info', 'Planche supprimée avec succès !');
			return $this->redirect($this->generateUrl('windsurfdb_matos_detail_marque', array('slug' => $marque)));
		}

		return $this->render('WindsurfdbMatosBundle:Board:board_delete.html.twig', array(
			'form' => $form->createView(),
			'board' => $board,
		));
	}


	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function importAction($marque, Request $request) {
		if($request->isMethod('POST')) {
			$em = $this->getDoctrine()->getManager();

			$freestyle = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Freestyle');
			$Freeride = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Freeride');
			$Freewave = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Freewave');
			$Freemove = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Freemove');
			$Freerace = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Freerace');
			$Slalom = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Slalom');
			$Speed = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Speed');
			$Wave = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Wave');
			$WindSUP = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('WindSUP');
			$Beginner = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Beginner');
			$Kids = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Kids');
			$Tandem = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('Tandem');
			$OneDesign = $em->getRepository('WindsurfdbMatosBundle:Programme')->findOneByName('OneDesign');

			$m = $em->getRepository('WindsurfdbMatosBundle:Marque')->findOneByName($marque);

			$dsn = 'mysql:dbname=windsurf-db;host=localhost';
			$user = 'root';
			$password = '';
			try {
				$dbh = new \PDO($dsn, $user, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			} catch (PDOException $e) {
				echo 'Connexion échouée : ' . $e->getMessage();
			}

			$sth = $dbh->prepare("SELECT DISTINCT modele, annee FROM board WHERE marque = ?");
			$sth->execute(array($marque));
			$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

			foreach ($result as $key => $value) {
				$board[$key] = new Board();
				$board[$key]->setModele($value['modele']);
				$board[$key]->setAnnee($value['annee']);
				$board[$key]->setMarque($m);
				$board[$key]->setOldBdd(true);
				$em->persist($board[$key]);
			}

			$sth = $dbh->prepare("SELECT DISTINCT modele, `s-modele`, annee, volume, `long`, `larg`, `t-voile`, resume, autre, `type` FROM board WHERE marque = ?");
			$sth->execute(array($marque));
			$result2 = $sth->fetchAll(\PDO::FETCH_ASSOC);

			$sth3 = $dbh->prepare("SELECT DISTINCT modele, `s-modele`, annee, volume, `long`, `larg`, `t-voile`, resume, autre, img, `type`, prix, prix_s, poids, tech, `box`, `fin` FROM board WHERE marque = ?");
			$sth3->execute(array($marque));
			$result3 = $sth3->fetchAll(\PDO::FETCH_ASSOC);

			var_dump($result3);

			foreach ($result2 as $key => $value) {
				$boardSpec = new BoardSpec();
				foreach ($board as $key2 => $value2) {
					if($value2->getModele()==$value['modele'] && $value2->getAnnee()==$value['annee']) {
						$boardSpec->setBoard($board[$key2]);
					}
				}
				$prix = array();
				$poids = array();
				$tech = array();
				$fin = array();
				$box = array();
				foreach ($result3 as $key3 => $value3) {
					if($value['s-modele']==$value3['s-modele'] && $value['volume']==$value3['volume'] && $value['modele']==$value3['modele'] && $value['annee']==$value3['annee']) {
						if($value3['prix'] != '') {
							$prix[] = $value3['prix'].$value3['prix_s'];
						}
						if($value3['poids'] != '') {
							$poids[] = $value3['poids'];
						}
						//if($value3['tech'] != '') {
							$tech[] = $value3['tech'];
						//}
						if($value3['fin'] != '') {
							$fin[] = $value3['fin'];
						}
						if($value3['box'] != '') {
							if($value3['box'] == 'Power Box' OR $value3['box'] == 'Power box') {
								$value3['box'] = 'Powerbox';
							}
							$box[] = $value3['box'];
						}
						if($value3['img'] != '') {
							$img = new Image();
							$img->setUrl($value3['img']);
							$boardSpec->addImage($img);
						}
					}
				}

				$boardSpec->setVolume($value['volume']);
				$boardSpec->setSmodele($value['s-modele']);
				$boardSpec->setLongueur($value['long']);
				$boardSpec->setLargeur($value['larg']);
				$boardSpec->setBox($box);
				$boardSpec->setFin($fin);
				$boardSpec->setPrix($prix);
				$boardSpec->setPoids($poids);
				$boardSpec->setTechno($tech);
				$programme = '';
				if($value['type'] != '') {
					$programme = 'Programme: '.$value['type'].' ';
				}
				$boardSpec->setInfos($programme.$value['resume'].' '.$value['autre']);
				if(!empty($value['t-voile']) && strpos($value['t-voile'], ' - ') !== false) {
					$tvoile = explode(' - ', $value['t-voile']);
					if($tvoile[0]!='x') {
						$boardSpec->setVoileMini($tvoile[0]);
					}
					if($tvoile[1]!='x') {
						$boardSpec->setVoileMaxi($tvoile[1]);
					}
				}
				if(stripos($value['type'], 'Wave')!==false) {
					$boardSpec->addProgramme($Wave);
				}
				if(stripos($value['type'], 'Freestyle')!==false) {
					$boardSpec->addProgramme($freestyle);
				}
				if(stripos($value['type'], 'Freewave')!==false) {
					$boardSpec->addProgramme($Freewave);
				}
				if(stripos($value['type'], 'Freeride')!==false || stripos($value['type'], 'Freecarve')!==false) {
					$boardSpec->addProgramme($Freeride);
				}
				if(stripos($value['type'], 'Freerace')!==false) {
					$boardSpec->addProgramme($Freerace);
				}
				if(stripos($value['type'], 'Freemove')!==false) {
					$boardSpec->addProgramme($Freemove);
				}
				if(stripos($value['type'], 'Slalom')!==false || stripos($value['type'], ' Race')!==false) {
					$boardSpec->addProgramme($Slalom);
				}
				if(stripos($value['type'], 'Speed')!==false) {
					$boardSpec->addProgramme($Speed);
				}
				if(stripos($value['type'], 'WindSUP')!==false) {
					$boardSpec->addProgramme($WindSUP);
				}
				if(stripos($value['type'], 'Beginner')!==false || stripos($value['type'], 'Débutant')!==false || stripos($value['type'], 'Debutant')!==false) {
					$boardSpec->addProgramme($Beginner);
				}
				if(stripos($value['type'], 'Kids')!==false) {
					$boardSpec->addProgramme($Kids);
				}
				if(stripos($value['type'], 'Tandem')!==false) {
					$boardSpec->addProgramme($Tandem);
				}
				if(stripos($value['type'], 'One Design')!==false) {
					$boardSpec->addProgramme($OneDesign);
				}
				$em->persist($boardSpec);
			}

			$em->flush();
			return new Response('<html><body>Ok</body></html>');
		}
		else {
			return new Response('<html><body><form method="post"><input type="submit" value="Importer"></form></body></html>');
		}
	}
}
