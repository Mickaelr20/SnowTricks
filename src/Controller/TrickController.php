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
    #[Route('/trick/add', name: 'app_trick_add', methods: ["GET", "POST"])]
    public function signup(Request $request, TrickRepository $repo): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickEditType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repo->add($trick, true);
            $this->addFlash('success', "Trick ajouté");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('trick/edit.html.twig', [
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
            'form' => $form->createView()
        ]);
    }
}
