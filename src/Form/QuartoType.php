<?php

namespace App\Form;

use App\Entity\Quarto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuartoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipo',ChoiceType::class, array(
                'choices'  => array(
                    'Solteiro' => "SOLTEIRO",
                    'Duplo' => "DUPLO",
                    'Casal' => "CASAL",
                ),
                "label"=>"Tipo de Quarto"
            ))

            ->add('nome')

            ->add('descricao',TextareaType::class,array(
                "label"=>"Descrição do Quarto"
            ))

            //->add('fotos')

            ->add('diaria',MoneyType::class, array("currency"=>"BRL"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quarto::class,
        ]);
    }
}
