<?php

namespace App\Form;

use App\Entity\BonPlan;
use App\Entity\Categorie;


use App\Entity\Partenaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BonPlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('url')
            ->add('description')
            ->add('codePromo')
            ->add('prix')
            ->add('prix_habituel')
            ->add('fraisDePort')
            ->add('isFree',CheckboxType::class,[
                'label'=>"Livraison gratuite ",
                'required' => false
            ])
            ->add('partenaire', EntityType::class, [
                'class' => Partenaire::class,
                'choice_label' => 'name'
            ])
            ->add('categories',EntityType::class,[
                'class' => Categorie::class,
                'choice_label'=>'label',
                'label'=>"Categories",

                'multiple'=>true
            ])
            ->add('apercu',FileType::class,[
                'mapped'=>false,
                'label'=>"Choisir une image"
            ])
            ->add('Enregistrer',SubmitType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BonPlan::class,
        ]);
    }
}
