<?php

namespace App\Controller;

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
     * @Route("/contato", name="contato")
     */
    public function contato(Request $request) {

        return $this->render('pagina/contato.html.twig');
    }

    /**
     * @Route("/reservar", name="reservar")
     */
    public function reservar() {
        return $this->render('pagina/reservar.html.twig');
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
