<?php

namespace App\Form;

use App\Entity\DetailsCommandes;
use App\Entity\Emplacements;
use App\Entity\Produits;
use App\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('prix')
            ->add('stock')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'Image du produit : ',
                'mapped' => false, 
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024K',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'maxSizeMessage' => 'Votre image ne doit pas dépasser 1024ko',
                        'mimeTypesMessage' =>'Votre image de produit doit être au format valide (jpg, jpeg, png).',
                    ]),
                ],
            ])
            ->add('leEmplacement', EntityType::class, [
                'class' => Emplacements::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
