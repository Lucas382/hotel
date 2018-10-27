<?php

namespace App\Repository;

use App\Entity\Reserva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reserva|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reserva|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reserva[]    findAll()
 * @method Reserva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reserva::class);
    }

//    /**
//     * @return Reserva[] Returns an array of Reserva objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reserva
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Retorna as reservas cuja data de saida é maior que hoje
     * @param string $campo - Campo de Ordenação
     * @param string $ordem - ASC ou DESC
     * @return array
     */
    public function findReservasAtivas($campo,$ordem)
    {

        $hoje = new \DateTime('today');

        $query=  $this->createQueryBuilder('r');
        $reservas =$query->andWhere("r.dataSaida >= :hoje")
            ->setParameter("hoje",$hoje)
            ->orderBy("r.".$campo,$ordem)
            ->getQuery()
            ->getResult()
            ->execute();

        return $reservas;
    }

    /**
     * @return array
     * @param \DateTime $dataIni
     * @param \DateTime $dataFin
     * @param $quantidade
     */
    public function quartosOcupados(\DateTime $dataIni,\DateTime $dataFin,$quantidade)
    {
        $query=$this->createQueryBuilder('r');
        $quartos = $query->where("r.dataEntrada >= :dataIni")
        ->setParameter("dataIni",$dataIni)
            ->andWhere("r.dataEntrada <= :dataFin")
            ->setParameter("dataFin",$dataFin)
            ->getQuery()
            ->execute();

        return $quartos;


    }
}
