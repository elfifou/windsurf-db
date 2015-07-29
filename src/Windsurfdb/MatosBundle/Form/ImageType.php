<?php
namespace Windsurfdb\MatosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('url', 'text')
			->add('alt', 'text', array('required' => false))
			->add('altLong', 'textarea', array('required' => false))
		;
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'Windsurfdb\MatosBundle\Entity\Image'
		));
	}

	public function getName() {
		return 'windsurfdb_matosbundle_image';
	}
}
