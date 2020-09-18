<?php

namespace App\Form;

use App\Entity\Laureat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LaureatRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
    // This form type is not defined in the example.
    $builder
        ->add('pseudo',null, [
            'attr' => [
                'placeholder' => 'Username',
                'class' => 'from-control form-control-lg'
            ],
            'label' => false
        ])
        ->add('nom',null, [
            'attr' => [
                'placeholder' => 'Nom',
            ],
            'label' => false
        ])
        ->add('prenom',null, [
            'attr' => [
                'placeholder' => 'Prenom',
            ],
            'label' => false
        ])
        ->add('email',null, [
            'attr' => [
                'placeholder' => 'Email',
            ],
            'label' => false
        ])
        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
        ->add('addresse', null, [
            'attr' => [
                'placeholder' => 'Addresse',
            ],
            'label' => false
        ])
        ->add('telephone', null, [
            'attr' => [
                'placeholder' => '06 XXX XXX XXX',
            ],
            'label' => false
        ])
        ->add('datenaissance',DateType::class,['widget'=>'single_text'])
        ->add('cinNumSejour', null, [
            'attr' => [
                'placeholder' => 'CIN',
            ],
            'label' => false
        ])
        ->add('lieuNaissance', null, [
            'attr' => [
                'placeholder' => 'Lieu de Naissance',
            ],
            'label' => false
        ])
        ->add('nationalite', null, [
            'attr' => [
                'placeholder' => 'Nationalité',
            ],
            'label' => false
        ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Laureat::class,
        ]);
    }

}
