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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('dateHourStart', DateTimeType::class, [
                'label' => 'Date de début',
            ])
            ->add('duration')
            ->add('dateLimitSubscritption', DateTimeType::class, [
                'label' => 'Date Limite Inscription ',
            ])
            ->add('nbSubMax', IntegerType::class, [
                'label' => 'Nombre de participants Max'
            ])
            ->add('information')
            //->add('user')
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
            ]  )
            ->add('place', EntityType::class, [
                'label' => 'Lieu',
                'class' => Place::class,
                'choice_label' => 'name',
            ]  )
            ->add('status', EntityType::class, [
                'label' => 'Etat',
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
