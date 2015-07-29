<?php
namespace Windsurfdb\MatosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgrammeType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', 'text')
      ->add('description', 'textarea', array('required' => false))
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Windsurfdb\MatosBundle\Entity\Programme'
    ));
  }

  public function getName()
  {
    return 'windsurfdb_matosbundle_programme';
  }
}
