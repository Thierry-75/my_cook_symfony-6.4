<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('plainPassword', PasswordType::class,['attr'=>['class'=>'form-control'],
        'label'=>'Ancien mot de passe','label_attr'=>['class'=>'form-label mt-4']])
        ->add('newPassword', RepeatedType::class,['type'=>PasswordType::class,
        'first_options'=>['label'=>'Nouveau mot de passe','label_attr'=>['class'=>'form-label mt-4']],
        'second_options'=>['label'=>'Confirmer nouveau mot de passe','label_attr'=>['class'=>'form-label mt-4']],
        'invalid_message'=>'Mots de passe différents !','options'=>['attr'=>['class'=>'password-field']],
        'constraints'=> [new Assert\NotBlank(['message' => ''])]])
 
        ->add('submit',SubmitType::class, ['attr'=>['class'=>'btn btn-warning mt-4 float-end'],'label'=>'Valider'])
    ;
    }







}