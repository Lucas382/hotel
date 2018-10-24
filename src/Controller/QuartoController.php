<?php

namespace App\Controller;

use App\Entity\Quarto;
use App\Form\QuartoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/quarto")
 */
class QuartoController extends AbstractController
{
    /**
     * @Route("/", name="quarto_index", methods="GET")
     * @return Response 
     */
    public function index(): Response
    {
        $quartos = $this->getDoctrine()
            ->getRepository(Quarto::class)
            ->findAll();

            
        return $this->render('quarto/index.html.twig', ['listagem' => $quartos]);
    }

    /**
     * @Route("/new", name="quarto_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $quarto = new Quarto();
        $form = $this->createForm(QuartoType::class, $quarto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quarto);
            $em->flush();

            return $this->redirectToRoute('quarto_index');
        }

        return $this->render('quarto/new.html.twig', [
            'quarto' => $quarto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idQuarto}", name="quarto_show", methods="GET")
     */
    public function show($idQuarto): Response
    {
        $em = $this->getDoctrine()->getRepository(Quarto::class);//Buscando a tabela da classe Quarto
        $quarto = $em->find($idQuarto);                                          //Fazendo select pelo id

        return $this->render('quarto/show.html.twig', ['quarto' => $quarto]);
    }

    /**
     * @Route("/{id}/edit", name="quarto_edit", methods="GET|POST")
     */
    public function edit(Request $request, Quarto $quarto): Response
    {
        $form = $this->createForm(QuartoType::class, $quarto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quarto_edit', ['id' => $quarto->getId()]);
        }

        return $this->render('quarto/edit.html.twig', [
            'quarto' => $quarto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quarto_delete", methods="DELETE")
     */
    public function delete(Request $request, Quarto $quarto): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quarto->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($quarto);
            $em->flush();
        }

        return $this->redirectToRoute('quarto_index');
    }
}
