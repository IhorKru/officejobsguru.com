<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SubscriberOptInType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
            ->add('agreeterms', CheckboxType::class, ['label' => '','required' => true])
            ->add('agreeemails', CheckboxType::class, ['label' => '','required' => true])
            ->add('agreepartners', CheckboxType::class, ['label' => '','required' => true])
                ;
    }
    
    /**
    * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\SubscriberOptInDetails']);
    }
    /**
     * @return string
     */
    public function getName() {
        return 'subscriber';
    }
}