<?php
namespace Windsurfdb\MatosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('modele', 'text')
			->add('marque', 'entity', array(
					'class'			=> 'WindsurfdbMatosBundle:Marque',
					'choice_label'	=> 'name'
				)
			)
			->add('annee', 'text', array('required' => false))
			->add('version', 'text', array('required' => false))
			->add('infos', 'textarea', array('required' => false))
			->add('save', 'submit')
		;
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'Windsurfdb\MatosBundle\Entity\Board'
		));
	}

	public function getName() {
		return 'windsurfdb_matosbundle_board';
	}
}
