<?php

namespace App\Form;

use App\Controller\DemandeController;
use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeEtablStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('etatDirecteurGn', ChoiceType::class, [
                'choices' => [
                    'Valide deplome' => DemandeController::ETAT_VALIDE,
                    'Not Valide' => DemandeController::ETAT_NOT_VALIDE,
                    'On Process' => DemandeController::ETAT_PROCESS
                ],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Etat de Demande Directeur General' 
            ])
            ->add('diplome',null, ['disabled' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}