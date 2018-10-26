<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Form\ReservaType;
use App\Repository\ReservaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/reserva")
 */
class ReservaController extends AbstractController
{
    /**
     * @Route("/", name="reserva_index", methods="GET")
     */
    public function index(ReservaRepository $reservaRepository, Request $request): Response
    {

        $campo = $request->get("campo");
        $ordem= $request->get("ord");

        $campo = ($campo == null)? "dataEntrada": $campo; //If ternario
        $ordem = ($ordem ==null)? "ASC":$ordem;

        $reservas = $reservaRepository->findReservasAtivas($campo,$ordem);

        //$reservas = $reservaRepository->findBy(array("dataSaida" => $hoje),array($campo => $ordem));



        return $this->render('reserva/index.html.twig', ['reservas' => $reservas]);
    }

    /**
     * @Route("/new", name="reserva_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {

        $reserva = new Reserva();

        $reserva->setDataEntrada(new \DateTime());
        $reserva->setDataSaida(new \DateTime("tomorrow"));

        $form = $this->createForm(ReservaType::class, $reserva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reserva);
            $em->flush();

            return $this->redirectToRoute('reserva_index');
        }

        return $this->render('reserva/new.html.twig', [
            'reserva' => $reserva,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reserva_show", methods="GET")
     */
    public function show(Reserva $reserva): Response
    {
        return $this->render('reserva/show.html.twig', ['reserva' => $reserva]);
    }

    /**
     * @Route("/{id}/edit", name="reserva_edit", methods="GET|POST")
     */
    public function edit(Request $request, Reserva $reserva): Response
    {
        $form = $this->createForm(ReservaType::class, $reserva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reserva_edit', ['id' => $reserva->getId()]);
        }

        return $this->render('reserva/edit.html.twig', [
            'reserva' => $reserva,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reserva_delete", methods="DELETE")
     */
    public function delete(Request $request, Reserva $reserva): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reserva->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reserva);
            $em->flush();
        }

        return $this->redirectToRoute('reserva_index');
    }
}
