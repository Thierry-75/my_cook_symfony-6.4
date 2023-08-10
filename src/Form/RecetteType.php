<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Ingredient;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'2','maxlength'=>'50'],'label'=>'Nom','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1']])
            ->add('time',IntegerType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'1','maxlength'=>'1440'],'label'=>'Durée','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1']])
            ->add('nbPeople',IntegerType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'1','maxlength'=>'50'],'label'=>'Nombre de personne','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1']])
            ->add('difficulty',IntegerType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'1','maxlength'=>'6'],'label'=>'Difficulté','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1']])
            ->add('description',TextareaType::class,['attr'=>['form-control form-control-sm'], 'label'=>'Description','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1']])
            ->add('price',MoneyType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'0','maxlength'=>'99'],'label'=>'Prix','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1']])
            ->add('isFavorite',CheckboxType::class, [
                'label'    => ' Recette favorite ?',
                'required' => false,
            ])
            ->add('ingredients',EntityType::class, [
                'class' => Ingredient::class,'label'=>'Ingrédients','label_attr' => ['class' =>'col-form-label col-form-label-sm mt-1'],
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'mapped' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'choice_label' => 'name', 'placeholder' => 'Choisir des ingrédients'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-warning mt-4 float-end'
                ], 'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
