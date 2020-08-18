<?php


namespace MissionBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('productName', TextType::class, [
                'attr' => ['class' => 'form-control']
        ])
            ->add('vendorName', TextType::class, [
                'attr' => ['class' => 'form-control']
        ])
            ->add('vendorEmail', TextType::class, [
                'attr' => ['class' => 'form-control']
        ])
            ->add('quantity', IntegerType::class, [
                'attr' => ['class' => 'form-control']
        ])
//            ->add('serviceDate', DateTimeType::class, [
//                'attr' => ['class' => 'form-control']
//        ])
            ;
    }

    public function configureOptions(OptionsResolver $optionsResolver){
        $optionsResolver->setDefaults([
            'data_class' => 'MissionBundle\Entity\Mission'
        ]);
    }
}