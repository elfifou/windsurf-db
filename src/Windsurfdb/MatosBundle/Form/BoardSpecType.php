<?php
namespace Windsurfdb\MatosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardSpecType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('smodele', 'text', array('required' => false))
		->add('volume', 'text', array('required' => false))
		->add('longueur', 'text', array('required' => false))
		->add('largeur', 'text', array('required' => false))
		->add('poids', 'collection', array(
			'type'         	=> 'text',
			'allow_add'    	=> true,
			'allow_delete' 	=> true,
			'options' 		=> array('required' => false, 'label' => false)
		))
		->add('techno', 'collection', array(
			'type'         	=> 'text',
			'allow_add'   	=> true,
			'allow_delete' 	=> true,
			'options' 		=> array('required' => false, 'label' => false)
		))
		->add('prix', 'collection', array(
			'type'        	=> 'text',
			'allow_add'   	=> true,
			'allow_delete'	=> true,
			'options'		=> array('required' => false, 'label' => false)
		))
		->add('voile_mini', 'text', array('required' => false))
		->add('voile_maxi', 'text', array('required' => false))
		->add('programmes', 'entity', array(
			'class'    		=> 'WindsurfdbMatosBundle:Programme',
			'choice_label'	=> 'name',
			'expanded' 		=> true,
			'multiple' 		=> true
		))
		->add('infos', 'textarea', array('required' => false))
		->add('box', 'collection', array(
			'type'         	=> 'text',
			'allow_add'   	=> true,
			'allow_delete' 	=> true,
			'options' 		=> array('required' => false, 'label' => false)
		))
		->add('fin', 'collection', array(
			'type'         	=> 'text',
			'allow_add'   	=> true,
			'allow_delete' 	=> true,
			'options' 		=> array('required' => false, 'label' => false)
		))
		->add('images', 'collection', array(
				'type' => new ImageType(),
				'allow_add'		=> true,
				'allow_delete'	=> true,
				'options' => array('label' => false)
			)
		)
		->add('save', 'submit');
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Windsurfdb\MatosBundle\Entity\BoardSpec'
		));
	}

	public function getName()
	{
		return 'windsurfdb_matosbundle_board_spec';
	}
}
