<?php

namespace App\Form;

use App\Entity\DirecteurPedagogique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
class DirecteurPedagogiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('nom')
            ->add('prenom')
            ->add('addresse')
            ->add('email',null, array('label'=>'Adress Email','data_class'=>null))
            ->add('telephone',null, array('label'=>'Numéro de téléphone','data_class'=>null))
            ->add('datenaissance',DateType::class,['widget'=>'single_text'])
            ->add('photoUrl', FileType::class, [
                'label' => 'Photo de Profile (image file)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'data_class'=> null,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image file',
                    ])
                ]
            ])
            ->add('etablissement')
            ->add('save', SubmitType::class, [
                'attr' => ['label' => 'Enregistrer', 'class' => 'btn btn-success btn-block'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DirecteurPedagogique::class,
        ]);
    }
}
