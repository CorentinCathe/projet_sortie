<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use App\Repository\SiteRepository;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\StringType;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    //private $siteRepo = SiteRepository;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //$allSite = $this->siteRepo->findAll();
        $builder
            ->add('email')
           /* ->add('roles', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'choices' => [
                          'User' => 'User : ROLE_USER' ,
                         'Admin' => 'User : ROLE_ADMIN'
            ]
                ]])*/
            ->add('password')
            ->add('name')
            ->add('firstName')
            ->add('pseudo')
            ->add('phoneNumber')
            ->add('activ')
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
            ])
           // ->add('sorties')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
