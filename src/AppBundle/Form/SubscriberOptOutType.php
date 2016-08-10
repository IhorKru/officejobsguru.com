<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SubscriberOptOutType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
               ->add('emailaddress', EmailType::class, ['label' => false, 'required' => true, 'attr' => ['placeholder' => 'Email Address', 'pattern'=>'.{2,}','class' => 'form-control','style' => 'height:41px']])
               ->add('submit', SubmitType::class, ['label' => 'Unsubscribe', 'attr' => ['class' => 'smoothScroll btn btn-danger sb-button']]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
         $resolver->setDefaults(['data_class' => 'AppBundle\Entity\SubscriberOptOutDetails']);
    }

    /**
     * @return string
     */
    public function getName() {
        return 'unsubscriber';
    }

}
