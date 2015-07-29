<?php
namespace Windsurfdb\MatosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarqueType extends AbstractType
{
	private $_locale, $_trad1, $_trad2;
	public function __construct(\Symfony\Component\Translation\DataCollectorTranslator $translator) {
		$this->_locale = $translator->getLocale();
		$this->_trad1 = $translator->trans('windsurfdb.matos.form.marque.type_choice.board');
		$this->_trad2 = $translator->trans('windsurfdb.matos.form.marque.type_choice.voile');
	}
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('name', 'text')
		->add('description', 'textarea', array('required' => false))
		->add('url', 'collection', array(
			'type'         => 'text',
			'allow_add'    => true,
			'allow_delete' => true,
			'options' => array('required' => false, 'label' => false)
		))
		->add('type', 'choice', array(
			'choices' => array('wb' => $this->_trad1, 'ws' => $this->_trad2),
			'expanded' => true,
			'multiple' => true
		))
		->add('exist', 'checkbox', array('required' => false))
		->add('save', 'submit');
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Windsurfdb\MatosBundle\Entity\Marque'
		));
	}

	public function getName()
	{
		return 'windsurfdb_matosbundle_marque';
	}
}
