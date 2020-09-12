<?php

namespace App\Form;

use App\Entity\Laureat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class LaureatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('nom')
            ->add('prenom')
            ->add('addresse')
            ->add('email')
            ->add('password')
            ->add('telephone')
            ->add('datenaissance',DateType::class,['widget'=>'single_text'])
            ->add('photoUrl', FileType::class,array('label'=>'Image..','data_class'=>null))
            ->add('cinNumSejour')
            ->add('lieuNaissance')
            ->add('nationalite')
            ->add('nomArabe', null, [
                'attr' => ['class' => 'keyboardInput from-control form-control-lg' , 'dir' => 'rtl'],
            ])
            ->add('prenomArabe', null, [
                'attr' => ['class' => 'keyboardInput from-control form-control-lg', 'dir' => 'rtl'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Laureat::class,
        ]);
    }
}
