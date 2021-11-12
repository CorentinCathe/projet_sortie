<?php

namespace App\Form;

use App\Entity\Place;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Status;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('dateHourStart')
            ->add('duration')
            ->add('dateLimitSubscritption')
            ->add('nbSubMax')
            ->add('information')
            //->add('user')
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
            ]  )
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
            ]  )
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'type',
            ])
            //->add('organisator')
        ;
    }

    public function buildCancelForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('information')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
