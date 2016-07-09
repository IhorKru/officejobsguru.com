<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => [
                    'placeholder' => 'Name',
                    'class' => 'form-control'
                    ]])
            ->add('emailaddress', EmailType::class, [
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => [
                    'placeholder' => 'Email Address',
                    'pattern'     => '.{2,}',//minlength
                    'class' => 'form-control'
                    ]])
            ->add('subject', TextType::class, [
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => [
                    'placeholder' => 'Subject',
                    'class' => 'form-control'
                ]
            ])
            ->add('message', TextType::class, ['label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'message',
                    'placeholder' => 'Message',
                    'style' => 'height: 7.4em;'
                )])
            ->add('submit', SubmitType::class, [
                'label' => 'Send Message',
                'attr' => array(
                    'class' => 'btn btn-default submit-btn form_submit'
                )])
             ;
    }
    
    /**
    * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'contact';
    }
}

