<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TrickRepository;
use App\Entity\Trick;
use App\Form\TrickEditType;

class TrickController extends AbstractController
{
    #[Route('/trick/new', name: 'app_trick_new', methods: ["GET", "POST"])]
    public function new(Request $request, TrickRepository $repo): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickEditType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repo->add($trick, true);
            $this->addFlash('success', "Trick ajouté");
            return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/trick/edit/{id}', name: 'app_trick_edit', methods: ["GET", "POST"])]
    public function edit(int $id, Request $request, TrickRepository $repo): Response
    {
        $trick = $repo->get($id);

        if (empty($trick)) {
            throw new \Exception('Le trick demandé n\'a pas été trouvé.');
        }
        $form = $this->createForm(TrickEditType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repo->add($trick, true);
            $this->addFlash('success', "Trick Modifié !");
            return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
        }

        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
        ]);
    }

    #[Route('/trick/delete/{id}', name: 'app_trick_delete', methods: ["GET", "POST"])]
    public function delete(int $id, TrickRepository $repo): Response
    {
        $trick = $repo->get($id);

        if (empty($trick)) {
            $this->addFlash('warning', "Le trick demandé n'a pas été trouvé !");
        } else {
            $repo->remove($trick, true);
            $this->addFlash('success', "Trick supprimé !");
        }

        return $this->redirectToRoute('app_home');
    }
}
