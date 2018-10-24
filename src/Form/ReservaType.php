<?php

namespace App\Form;

use App\Entity\Reserva;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $anos = array();
        $anoIni=date("Y");
        $anoFin=$anoIni +7;


        for($ano = $anoIni; $ano <= $anoFin; $ano++){
            $anos[] = $ano;
        }

        $builder


            ->add('dataEntrada', DateType::class,array(
                'format'=>"dd-MMMM-yyyy",
                'years' =>$anos
            ))

            ->add('dataSaida',DateType::class,array(
                'format'=>"dd-MMMM-yyyy",
                'years' =>$anos
            ))
            ->add('valorTotal',MoneyType::class, array("currency"=>"BRL"))
            ->add('observacao')
            ->add('quarto')
            ->add('cliente')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reserva::class,
        ]);
    }
}
