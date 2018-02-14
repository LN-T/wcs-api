<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Portion;
use App\Entity\Recipe;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipe', EntityType::class, array(
                'class' => Recipe::class,
                'query_builder' => function (EntityRepository $er){
                    $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'Catégorie'
            ))
            ->add('ingredient', EntityType::class, array(
                'class' => Ingredient::class
            ))
            ->add('quantity', NumberType::class)
            ->add('unity', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Portion::class,
        ]);
    }
}
