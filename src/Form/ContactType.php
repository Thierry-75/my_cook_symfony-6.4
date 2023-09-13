<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control', 'minlength' => '2', 'maxlength' => '50'
                ],
                'label' => 'Nom / prénom', 'label_attr' => ['class' => 'form-label mt-4'],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50, 'minMessage' => 'min 2', 'maxMessage' => 'max 50']),
                    new Assert\NotBlank(['message' => ''])
                ]
            ])
            ->add('email',EmailType::class,['attr'=>['class'=>'form-control','maxlength'=>'255'],
            'label'=>'Email','label_attr'=>['class'=>'form-label mt-4'],
            'constraints'=>[ new Assert\Length(['max'=>255,'maxMessage'=>'max 255']),
            new Assert\NotBlank(['message' => '']), new Assert\Email(['message'=>'indiquez votre email'])]])
            ->add('subject', TextType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'2','maxlength'=>'50'],'label'=>'Sujet','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1'],
            'constraints'=>[new Assert\Length(['min'=>2,'max'=>100]), new Assert\NotBlank(['message' => ''])]])
            ->add('message',TextareaType::class,['attr'=>['form-control form-control-sm'], 'label'=>'Message','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1'],
            'constraints'=>[new Assert\NotBlank(['message' => ''])]])
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'btn btn-warning text-black mt-4 float-end'], 'label' => 'Envoyer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
