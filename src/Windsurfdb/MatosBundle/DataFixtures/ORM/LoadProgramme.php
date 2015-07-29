<?php
namespace Windsurfdb\MatosBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Windsurfdb\MatosBundle\Entity\Programme;

class LoadProgramme implements FixtureInterface {
	public function load(ObjectManager $manager) {
		$names = array(
			'Freerace',
			'Freeride',
			'Freemove',
			'Freestyle',
			'Freewave',
			'Slalom',
			'Speed',
			'Wave',
			'WindSUP',
			'Foil',
			'Beginner',
			'Kids',
			'Tandem',
			'One Design'
		);

		foreach ($names as $name) {
			$programme = new Programme();
			$programme->setName($name);
			$manager->persist($programme);
		}

		$manager->flush();
	}
}
