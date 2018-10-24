<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservaRepository")
 */
class Reserva
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * * @Assert\GreaterThan("tomorrow")
     */
    private $dataEntrada;

    /**
     * @ORM\Column(type="date")
     */
    private $dataSaida;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quarto", inversedBy="reservas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quarto;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $valorTotal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacao;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataEntrada(): ?\DateTimeInterface
    {

        return $this->dataEntrada;
    }

    public function setDataEntrada(\DateTimeInterface $dataEntrada): self
    {

        $this->dataEntrada = $dataEntrada;

        return $this;
    }

    public function getDataSaida(): ?\DateTimeInterface
    {

        return $this->dataSaida;
    }

    public function setDataSaida(\DateTimeInterface $dataSaida): self
    {
        $this->dataSaida = $dataSaida;

        return $this;
    }

    public function getQuarto(): ?Quarto
    {
        return $this->quarto;
    }

    public function setQuarto(?Quarto $quarto): self
    {
        $this->quarto = $quarto;

        return $this;
    }

    public function getValorTotal()
    {
        return $this->valorTotal;
    }

    public function setValorTotal($valorTotal): self
    {
        $this->valorTotal = $valorTotal;

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): self
    {
        $this->observacao = $observacao;

        return $this;
    }
}
