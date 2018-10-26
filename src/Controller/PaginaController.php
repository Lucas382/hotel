<?php

namespace App\Controller;

use App\Entity\Quarto;
use App\Entity\Reserva;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class PaginaController extends AbstractController {

    /**
     * @Route("/", name="index")
     */
    public function index() {
        return $this->render('pagina/index.html.twig', [
                    'controller_name' => 'PaginaController',
        ]);
    }

    /**
     * @Route("/pesquisa",name="pesquisa")
     */
    public function pesquisa(Request $request){

        $quantidade = $request->get("quantidade");
        $dias =explode(" - ",$request->get("data-selecionada"));

        $dataIni= \DateTime::createFromFormat("d/m/Y",$dias[0]);
        $dataFin= \DateTime::createFromFormat("d/m/Y",$dias[1]);

        $total = $dataIni->diff($dataFin);

        $session = $request->getSession();
        $session->set("dataIni",$dataIni);
        $session->set("dataFin",$dataFin);
        $session->set("quantidade",$quantidade);





        $em = $this->getDoctrine()->getRepository(Reserva::class);

        $quartos = $em->quartosOcupados($dataIni,$dataFin,$quantidade);

        return $this->render('pagina/index.html.twig',array("quartos" => $quartos));
        $quartos = $em->findAll;

    }

    /**
     * @Route("/contato", name="contato")
     */
    public function contato(Request $request) {

        return $this->render('pagina/contato.html.twig');
    }

    /**
     * @Route("/reservar/{quarto}", name="reservar")
     */
    public function reservar($quarto, Request $request) {

        $dataIni= $request->getSession()->get("dataIni");
        $dataFin= $request->getSession()->get("dataFin");

        $total = $dataIni->diff($dataFin);




        $quarto = $this->getDoctrine()->getRepository(Quarto::class)->find($quarto);

        $totalReserva = $totalDias->days * $quarto->getDiaria();
        return $this->render('pagina/reservar.html.twig', array(
            "quarto" => $quarto,
            "total_dias"=> $totalDias,
            "dataIni" => $dataIni,
            "dataFin" => $dataFin,
            "totalReserva" => $totalReserva


        ));
    }

    /**
     * @Route("/login2", name="login")
     */
    public function login() {

        return $this->render('pagina/login.html.twig');
    }

    /**
     * @Route("/admin",name="admin")
     */
    public function admin() {

        return $this->render('pagina/admin.html.twig');
    }

}
