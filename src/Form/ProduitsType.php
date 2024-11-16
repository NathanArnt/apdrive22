<?php

namespace App\Form;

use App\Entity\DetailsCommandes;
use App\Entity\Emplacements;
use App\Entity\Produits;
use App\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('prix')
            ->add('quantite')
            ->add('description')
            ->add('leStock', EntityType::class, [
                'class' => Stock::class,
                'choice_label' => 'quantite',
                'required' => true
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
