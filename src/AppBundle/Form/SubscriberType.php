<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class SubscriberType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
            ->add('firstname', TextType::class, [
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => array(
                    'placeholder' => 'First Name',
                    'class' => 'form-control'
                )])
            ->add('lastname', TextType::class, [
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => array(
                    'placeholder' => 'Last Name',
                    'class' => 'form-control'
                )])
            ->add('emailaddress', EmailType::class, [
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => [
                    'placeholder' => 'Email Address',
                    'pattern'     => '.{2,}',//minlength
                    'class' => 'form-control'
                    ]])  
            ->add('phone', TextType::class, [
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => [
                    'placeholder' => 'Mobile Phone',
                    'pattern'     => '.{2,}', //minlength
                    'class' => 'form-control'
                    ]])
            ->add('age', TextType::class, [
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => [
                    'placeholder' => 'Age',
                    'class' => 'form-control'
                    ]])
            ->add('agreeterms', CheckboxType::class, ['label' => '','required' => true])
            ->add('agreeemails', CheckboxType::class, ['label' => '','required' => true])
            ->add('agreepartners', CheckboxType::class, ['label' => '','required' => true])
            ->add('submit', SubmitType::class, [
                'label' => 'Sign Up',
                'attr' => [
                    'class' => 'smoothScroll btn btn-danger sb-button',
                    'style' => 'margin-bottom:2em;'
                    ]
        ])
             ;
    }
    
    /**
    * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Subscriber']);
    }
    /**
     * @return string
     */
    public function getName() {
        return 'subscriber';
    }
}
