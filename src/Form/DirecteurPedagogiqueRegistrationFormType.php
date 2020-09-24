<?php

namespace App\Form;

use App\Entity\DirecteurPedagogique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints as Assert;

class DirecteurPedagogiqueRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo',null, [
                'attr' => [
                    'placeholder' => 'Username',
                    'class' => 'from-control form-control-lg',
                    'required' => 'required'
                ],
                'label' => false
            ])
            ->add('nom',null, [
                'attr' => [
                    'placeholder' => 'Nom',
                    'required' => 'required'
                ],
                'label' => false
            ])
            ->add('prenom',null, [
                'attr' => [
                    'placeholder' => 'Prenom',
                    'required' => 'required'
                ],
                'label' => false
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\Email([
                        'message' => 'Veuillez entrer une adresse email valide Ex: Aicha@gmail.com'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Email Address'
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
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'attr' => [
                    'required' => 'required'
                ]
            ])
            ->add('telephone', TelType::class, [
                'attr' => [
                    'placeholder' => '+212 XXX XXX XXX',
                ],
                'label' => false
            ])
            ->add('S\'inscrire', SubmitType::class, [
                'attr' => ['class' => 'btn btn-widest btn-tall btn-primary rounded-0 shadow-sm'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DirecteurPedagogique::class,
        ]);
    }
}
