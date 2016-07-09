<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UnsubscriberType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
               ->add('emailaddress', EmailType::class, [
                    'label' => false,
                    'required' => true,
                    'attr' => array(
                        'placeholder' => 'Email Address',
                        'pattern'     => '.{2,}', //minlength
                        'class' => 'form-control',
                        'style' => 'height:41px'
                    )])
               ->add('submit', SubmitType::class, array(
                    'label' => 'Unsubscribe', 
                    'attr' => array(
                        'class' => 'smoothScroll btn btn-danger sb-button'
                    )))
                ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
         $resolver->setDefaults(array(
               'data_class' => 'AppBundle\Entity\Unsubscriber'
           ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'unsubscriber';
    }

}
