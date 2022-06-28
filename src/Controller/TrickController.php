<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TrickRepository;
use App\Entity\Trick;
use App\Form\TrickEditType;
use App\Form\CommentAddType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    #[Route('/trick/new', name: 'app_trick_new', methods: ["GET", "POST"])]
    public function new(Request $request, TrickRepository $repo, SluggerInterface $slugger): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickEditType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailFile = $form->get('thumbnail')->getData();

            if ($thumbnailFile) {
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $thumbnailFile->guessExtension();

                $thumbnailFile->move(
                    $this->getParameter('thumbnails_directory'),
                    $newFilename
                );

                $trick->setThumbnailFilename($newFilename);
            }

            $trick->setCreated(new \DateTime());
            $repo->add($trick, true);
            $this->addFlash('success', "Trick ajouté");
            return $this->redirectToRoute('app_trick_edit', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Nouveau trick'
        ]);
    }

    #[Route('/trick/edit/{slug}', name: 'app_trick_edit', methods: ["GET", "POST"])]
    public function edit(Trick $trick, Request $request, TrickRepository $repo, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TrickEditType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailFile = $form->get('thumbnail')->getData();

            if ($thumbnailFile) {
                //Supprimer l'ancienne thumbnail
                $oldTrick = $repo->get($trick->getId());
                $oldThumbnail = $this->getParameter('thumbnails_directory') . "/" . $oldTrick->getThumbnailFilename();

                if (file_exists($oldThumbnail)) {
                    unlink($oldThumbnail);
                }

                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $thumbnailFile->guessExtension();

                $thumbnailFile->move(
                    $this->getParameter('thumbnails_directory'),
                    $newFilename
                );

                $trick->setThumbnailFilename($newFilename);
            }

            $trick->setModified(new \DateTime());
            $repo->add($trick, true);
            $this->addFlash('success', "Trick Modifié !");
            return $this->redirectToRoute('app_trick_edit', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' =>
            $trick,
            'page_title' => 'Modifier un trick'
        ]);
    }

    #[Route('/trick/delete/{slug}', name: 'app_trick_delete', methods: ["GET", "POST"])]
    public function delete(Trick $trick, TrickRepository $repo): Response
    {
        $thumbnail = $this->getParameter('thumbnails_directory') . "/" . $trick->getThumbnailFilename();

        if (file_exists($thumbnail)) {
            unlink($thumbnail);
        }

        $repo->remove($trick, true);
        $this->addFlash('success', "Trick supprimé !");

        return $this->redirectToRoute('app_home');
    }

    #[Route('/trick/view/{slug}', name: 'app_trick_view', methods: ["GET", "POST"])]
    public function view(Trick $trick): Response
    {
        $formComment = $this->createForm(CommentAddType::class);

        return $this->renderForm('trick/view.html.twig', [
            'form_comment' => $formComment,
            'trick' => $trick,
            'page_title' => $trick->getName() . " - trick"
        ]);
    }
}
