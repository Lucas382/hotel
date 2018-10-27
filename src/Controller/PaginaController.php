<?php

namespace App\Controller;

use App\Entity\Cliente;
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

        //$total = $dataIni->diff($dataFin);

        $session = $request->getSession();
        $session->set("dataIni",$dataIni);
        $session->set("dataFin",$dataFin);
        $session->set("quantidade",$quantidade);






        //$quartos = $em->quartosOcupados($dataIni,$dataFin,$quantidade);

        $em = $this->getDoctrine()->getRepository(Quarto::class);
        $quartos = $em->findAll();
        return $this->render('pagina/pesquisa.html.twig',array("quartos" => $quartos));


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

        $totalDias = $dataIni->diff($dataFin);
        $quarto = $this->getDoctrine()->getRepository(Quarto::class)->find($quarto);
       // var_dump($quarto);die();
        $totalReserva = $totalDias->days * $quarto->getDiaria();


        return $this->render('pagina/reservar.html.twig', array(
            "quarto" => $quarto,
            "total_dias"=> $totalDias->days,
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

    /**
     * @Route("/confirmar", name="confirmar_reserva")
     */
    public function confirmar(Request $request, \Swift_Mailer $mailer)
    {

        $nome = $request->get("firstName");
        $sobrenome = $request->get("lastName");
        $email = $request->get("email");
        $endereco = $request->get("address");
        $quarto = $request->get("quarto");

        $dataIni = $request->getSession()->get("dataIni");
        $dataFin = $request->getSession()->get("dataFin");

        $erro = false;


        if (strlen($nome) < 2)
        {
            $this->addFlash("erro","O campo nome é obrigatório ");
            $erro = true;
        }
        if (strlen($sobrenome) < 2)
        {
            $this->addFlash("erro","O campo sobrenome é obrigatório ");
            $erro = true;
        }
        if (strlen($email) < 2)
        {
            $this->addFlash("erro","O campo email é obrigatório ");
            $erro = true;
        }

        if($erro == true)
        {
            return $this->redirectToRoute("reservar", array("quarto" => $quarto));
        }

        $totalDias = $dataIni->diff($dataFin);
        $quarto = $this->getDoctrine()->getRepository(Quarto::class)->find($quarto);
        $totalReserva = $totalDias->days * $quarto->getDiaria();

        //var_dump($totalReserva, $totalDias, $quarto->getDiaria());die();

        $cliente = new Cliente();
        $cliente->setNome($nome);
        $cliente->setEmail($email);
        $cliente->setSobrenome($sobrenome);
        $cliente->setEndereco($endereco);

        $reserva = new Reserva();
        $reserva->setDataEntrada($dataIni);
        $reserva->setDataSaida($dataFin);
        $reserva->setValorTotal($totalReserva);

        $reserva->setQuarto($quarto);
        $reserva->setCliente($cliente);


        $em = $this->getDoctrine()->getManager();
        $em->persist($cliente);
        $em->persist($reserva);

        $em->flush();

        $this->enviarEmail($reserva , $mailer);


       return $this->render("pagina/confirmar.html.twig", array("reserva" => $reserva));

    }

    /**
     * Envia email com a confirmação de reserva via current mail do symfony
     * @param Reserva $reserva
     */
    private function enviarEmail(Reserva $reserva, \Swift_Mailer $mailer)
    {

        $html = $this->renderView("pagina/confirmar.html.twig", array("reserva" => $reserva));



        $msg =  (new \Swift_Message());
        $msg->addTo($reserva->getCliente()->getEmail());
        $msg->setSubject("Confirmação de Reserva");
        $msg->addFrom("hotel@hotel.com","Hotel Zin");
        $msg->setBody($html, 'text/html');

        $mailer->send($msg);


    }


}
