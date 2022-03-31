<?php

namespace App\Form;

use App\Entity\Posting;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('title', TextType::class ,['attr' => [ 'class' => 'form-control']])
            ->add('description', TextareaType::class ,[
                'attr' => array(
                    'class' => 'form-control'
                )
                ])
            ->add('image', FileType::class,
            [
                'label' =>'Select image to upload',
                'data_class' => null,
                'required' => false,
                
            ])
            // ->add('executed_at')
            // ->add('user')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posting::class,
        ]);
    }
}
