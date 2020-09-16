<?php

namespace App\Form;

use App\Entity\Laureat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LaureatRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['flow_step']) {
			case 1:
				$builder
                    ->add('pseudo', null, [
                        'attr' => [
                            'placeholder' => 'Username',
                        ],
                        'label' => false
                    ])
                    ->add('nom', null, [
                        'attr' => [
                            'placeholder' => 'Last Name',
                        ],
                        'label' => false
                    ])
                    ->add('prenom', null, [
                        'attr' => [
                            'placeholder' => 'First Name',
                        ],
                        'label' => false
                    ])
                    ->add('email', null, [
                        'attr' => [
                            'placeholder' => 'Email Address',
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
                        'attr' => [
                            'placeholder' => 'Email Address',
                        ],
                        'label' => false
                    ]);
				break;
			case 2:
				// This form type is not defined in the example.
                $builder
                ->add('addresse', null, [
                    'attr' => [
                        'placeholder' => 'Addresse',
                    ],
                    'label' => false
                ])
                ->add('telephone', null, [
                    'attr' => [
                        'placeholder' => '+212 XXX XXX XXX',
                    ],
                    'label' => false
                ])
                ->add('datenaissance')
                ->add('photoUrl', null, [
                    'attr' => [
                        'placeholder' => 'Photo de Profile',
                    ],
                    'label' => false
                ])
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
                ])
                ->add('nomArabe', null, [
                    'attr' => [
                        'placeholder' => 'الاسم العائلى',
                        'dir' => 'rtl'
                    ],
                    'label' => false
                ])
                ->add('prenomArabe', null, [
                    'attr' => [
                        'placeholder' => 'الاسم الشخصي',
                        'dir' => 'rtl'
                    ],
                    'label' => false
                ])
                ->add('S\'inscrire', SubmitType::class, [
                    'attr' => ['class' => 'btn btn-widest btn-tall btn-primary rounded-0 shadow-sm'],
                ]);
				break;
		}
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'flow_step' => 1,
            'data_class' => Laureat::class,
        ]);
    }
}
