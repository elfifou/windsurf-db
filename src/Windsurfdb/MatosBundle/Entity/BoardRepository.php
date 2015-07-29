<?php
namespace Windsurfdb\MatosBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BoardRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BoardRepository extends EntityRepository
{
	public function getModeleWithMarque($slug) {
		$qb = $this
			->createQueryBuilder('a')
			->where('a.slug = :slug')
			->leftJoin('a.marque', 'b')
			->addSelect('b')
			->setParameter('slug', $slug)
		;

		return $qb
			->getQuery()
			->getOneOrNullResult()
		;
	}
	public function findAllModelesOnly() {
		$qb = $this
			->createQueryBuilder('a')
			->select('DISTINCT a.modele')
			->orderBy('a.modele')
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findAllAnneesOnly() {
		$qb = $this
			->createQueryBuilder('a')
			->select('DISTINCT a.annee')
			->orderBy('a.annee')
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findModelesAndAnneesByMarque($marque) {
		$qb = $this
			->createQueryBuilder('a')
			->select('partial a.{id, modele, annee}')
			->where('a.marque = :marque')
			->setParameter('marque', $marque->getId())
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findModelesAndAnnees() {
		$qb = $this
			->createQueryBuilder('a')
			->select('partial a.{id, modele, annee}')
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findAllByAnneeAndModeleAndMarque($annee, $modele, $marque) {
		$qb = $this
			->createQueryBuilder('a')
			->leftJoin('a.marque', 'b')
			->addSelect('b')
			->where('a.marque = :marque')
			->andWhere('a.modele = :modele')
			->andWhere('a.annee = :annee')
			->setParameter('marque', $marque->getId())
			->setParameter('modele', $modele)
			->setParameter('annee', $annee)
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findAllByModeleAndMarque($modele, $marque) {
		$qb = $this
			->createQueryBuilder('a')
			->leftJoin('a.marque', 'b')
			->addSelect('b')
			->where('a.marque = :marque')
			->andWhere('a.modele = :modele')
			->setParameter('marque', $marque->getId())
			->setParameter('modele', $modele)
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findAllByAnneeAndMarque($annee, $marque) {
		$qb = $this
			->createQueryBuilder('a')
			->leftJoin('a.marque', 'b')
			->addSelect('b')
			->where('a.marque = :marque')
			->andWhere('a.annee = :annee')
			->setParameter('marque', $marque->getId())
			->setParameter('annee', $annee)
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findAllByAnneeAndModele($annee, $modele) {
		$qb = $this
			->createQueryBuilder('a')
			->leftJoin('a.marque', 'b')
			->addSelect('b')
			->where('a.modele = :modele')
			->andWhere('a.annee = :annee')
			->setParameter('modele', $modele)
			->setParameter('annee', $annee)
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findAllByAnnee($annee) {
		$qb = $this
			->createQueryBuilder('a')
			->leftJoin('a.marque', 'b')
			->addSelect('b')
			->where('a.annee = :annee')
			->setParameter('annee', $annee)
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findAllByModele($modele) {
		$qb = $this
			->createQueryBuilder('a')
			->leftJoin('a.marque', 'b')
			->addSelect('b')
			->where('a.modele = :modele')
			->setParameter('modele', $modele)
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findAllByMarque($marque) {
		$qb = $this
			->createQueryBuilder('a')
			->leftJoin('a.marque', 'b')
			->addSelect('b')
			->where('a.marque = :marque')
			->setParameter('marque', $marque->getId())
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
	public function findByMarque($marque) {
		$qb = $this
			->createQueryBuilder('a')
			->where('a.marque = :marque')
			->setParameter('marque', $marque)
			->orderBy('a.annee', 'DESC')
		;

		return $qb
			->getQuery()
			->getResult()
		;
	}
}