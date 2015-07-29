<?php
namespace Windsurfdb\MatosBundle\Controller;

use Windsurfdb\MatosBundle\Entity\Marque;
use Windsurfdb\MatosBundle\Form\MarqueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MarqueController extends Controller {
	public function detailAction($slug) {
		$em = $this->getDoctrine()->getManager();

		$marque = $em->getRepository('WindsurfdbMatosBundle:Marque')->findOneBySlug($slug);

		if (null === $marque) {
			throw new NotFoundHttpException("La marque '".$slug."' n'existe pas.");
		}

		$modeles = $em->getRepository('WindsurfdbMatosBundle:Board')->findByMarque($marque);

		return $this->render('WindsurfdbMatosBundle:Marque:detail.html.twig', array(
			'marque' => $marque,
			'modeles' => $modeles
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function listeAction() {
		$em = $this->getDoctrine()->getManager();

		$repository = $em->getRepository('WindsurfdbMatosBundle:Marque');

		$marques = $repository->findAll();

		return $this->render('WindsurfdbMatosBundle:Marque:liste.html.twig', array(
			'marques' => $marques
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function addAction(Request $request) {
		$marque = new Marque();

		$form = $this->createForm($this->container->get('windsurfdb_matos.form.contact'), $marque); // = $this->get('form.factory')->create(new MarqueType(), $marque);

		if ($form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($marque);
			$em->flush();

			$request->getSession()->getFlashBag()->add('info', 'Marque bien enregistrée.');
			return $this->redirect($this->generateUrl('windsurfdb_matos_liste_marque'));
		}

		return $this->render('WindsurfdbMatosBundle:Marque:add.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function modifAction($id, Request $request) {
		$em = $this->getDoctrine()->getManager();

		$marque = $em->getRepository('WindsurfdbMatosBundle:Marque')->find($id);

		if (null === $marque) {
			throw new NotFoundHttpException("La marque d'id ".$id." n'existe pas.");
		}

		$form = $this->createForm($this->container->get('windsurfdb_matos.form.contact'), $marque);

		if ($form->handleRequest($request)->isValid()) {
			$em->flush();

			$request->getSession()->getFlashBag()->add('info', 'Marque bien modifiée.');
			return $this->redirect($this->generateUrl('windsurfdb_matos_liste_marque'));
		}

		return $this->render('WindsurfdbMatosBundle:Marque:modif.html.twig', array(
			'form' => $form->createView(),
			'marque'=> $marque
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function deleteAction($id, Request $request) {
		$em = $this->getDoctrine()->getManager();

		$marque = $em->getRepository('WindsurfdbMatosBundle:Marque')->find($id);

		if (null === $marque) {
			throw new NotFoundHttpException("La marque d'id ".$id." n'existe pas.");
		}

		$form = $this->createFormBuilder()->getForm();

		if ($form->handleRequest($request)->isValid()) {
			$em->remove($marque);
			$em->flush();

			$request->getSession()->getFlashBag()->add('info', 'Marque bien supprimée.');
			return $this->redirect($this->generateUrl('windsurfdb_matos_liste_marque'));
		}

		return $this->render('WindsurfdbMatosBundle:Marque:delete.html.twig', array(
			'form' => $form->createView(),
			'marque'=> $marque
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function importAction(Request $request) {
		if($request->isMethod('POST')) {
			$dsn = 'mysql:dbname=windsurf-db;host=localhost';
			$user = 'root';
			$password = '';

			try {
				$dbh = new \PDO($dsn, $user, $password);
			} catch (PDOException $e) {
				echo 'Connexion échouée : ' . $e->getMessage();
			}

			$sth = $dbh->prepare("SELECT DISTINCT marque FROM board");

			$sth->execute();

			$result = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

			sort($result);

			$em = $this->getDoctrine()->getManager();

			foreach ($result as $key => $value) {
				$marque = new Marque();
				$marque->setName($value);
				$marque->setType(array('wb'));
				$em->persist($marque);
			}
			$em->flush();
			var_dump($result);
			return new Response('<html><body>Ok</body></html>');
		}
		else {
			return new Response('<html><body><form method="post"><input type="submit" value="Importer"></form></body></html>');
		}
	}
}
