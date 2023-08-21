<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class RecetteType extends AbstractType
{

    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'2','maxlength'=>'50'],'label'=>'Nom','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1'],
            'constraints'=>[new Assert\Length(['min'=>2,'max'=>50]), new Assert\NotBlank(['message' => ''])]])
            ->add('time',IntegerType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'1','maxlength'=>'1440'],'label'=>'Durée','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1'],
            'constraints'=>[new Assert\Positive(), new Assert\LessThan(1441)]])
            ->add('nbPeople',IntegerType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'1','maxlength'=>'50'],'label'=>'Nombre de personne','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1'],
            'constraints'=>[new Assert\Positive(), new Assert\LessThan(51)]])
            ->add('difficulty',RangeType::class,['attr'=>['form-range form-range-sm', 'min'=>'0','max'=>'5','step'=>'1'],'label'=>'Difficulté','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1'],
            'constraints'=>[new Assert\Positive(['message' => '']),new Assert\LessThan(6)]])
            ->add('description',TextareaType::class,['attr'=>['form-control form-control-sm'], 'label'=>'Description','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1'],
            'constraints'=>[new Assert\NotBlank(['message' => ''])]])
            ->add('price',MoneyType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'0','maxlength'=>'1000'],'label'=>'Prix','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1'],
            'constraints'=>[new Assert\Positive(), new Assert\LessThan(1001)]])
            ->add('isFavorite',CheckboxType::class,[
                'label'=> ' Recette favorite ?',
                'required' => true,
            ])
            ->add('ingredients',EntityType::class, [
                'class' => Ingredient::class,'label'=>'Les ingrédients','required'=>true,'label_attr' => ['class' =>'col-form-label col-form-label-sm mt-1'],
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'mapped' => false,
                'query_builder' => function (IngredientRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->where('i.user =:user')
                        ->orderBy('i.name', 'ASC')
                        ->setParameter('user', $this->token->getToken()->getUser());
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
