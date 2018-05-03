<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; 

class UserType extends AbstractType {

    public function buildInscriptionForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username', TextType::class, array('label' => 'Pseudo '))
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Mot de passe '),
                    'second_options' => array('label' => 'Vérif mot de passe'),
                ))
                ->add('email', EmailType::class, array('label' => 'Email '))
        ;
    }
    
        public function buildChangerMdpForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Mot de passe '),
                    'second_options' => array('label' => 'Vérif mot de passe'),
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

}
