<?php

namespace App\Form;

use App\Controller\DemandeController;
use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeSecretaireStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('etatSecretaire', ChoiceType::class, [
                'choices' => [
                    'Valider' => DemandeController::ETAT_VALIDE,
                    'Annuler' => DemandeController::ETAT_NOT_VALIDE,
                    'En cours' => DemandeController::ETAT_PROCESS
                ],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Etat de Demande' 
            ])
            // ->add('diplome',null, ['disabled' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
