<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-sm', 'minlength' => '2', 'maxlength' => '50'
                ],
                    'label' => 'Nom', 
                    'label_attr' => [
                        'class' => 'col-form-label col-form-label-sm mt-4'
                    ],
            'constraints' => [ new Assert\Length(['min'=>2,'max'=>50, 'minMessage'=>'minimum 2','maxMessage'=>'max 50']),
             new Assert\NotBlank(['message' => ''])]
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control form-control-sm' ],
                    'label' => 'Prix', 
                    'label_attr' => [
                        'class' => 'col-form-label col-form-label-sm mt-4'
                    ],
            'constraints' => [  new Assert\NotBlank(['message' => '']),
            new Assert\Positive(),
            new Assert\LessThan(200)
            ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-warning text-black mt-4 float-end'
                ], 'label' => 'Valider'
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
