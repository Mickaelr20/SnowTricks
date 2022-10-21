<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TrickRepository;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickEditType;
use App\Form\CommentAddType;
use App\Trick\CreateTrickInterface;
use App\Trick\EditTrickInterface;
use App\Comment\CreateCommentInterface;

class TrickController extends AbstractController
{
    #[Route('/trick/new', name: 'app_trick_new', methods: ["GET", "POST"])]
    public function new(Request $request, CreateTrickInterface $createTrick): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickEditType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $createTrick($trick, $form->get('thumbnail')->getData());
            $this->addFlash('success', "Trick ajouté");
            return $this->redirectToRoute('app_trick_edit', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Nouveau trick'
        ]);
    }

    #[Route('/trick/edit/{slug}', name: 'app_trick_edit', methods: ["GET", "POST"])]
    public function edit(Trick $trick, Request $request, EditTrickInterface $editTrick): Response
    {
        $form = $this->createForm(TrickEditType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $editTrick($trick, $form->get('thumbnail')->getData());
            $this->addFlash('success', "Trick Modifié !");
            return $this->redirectToRoute('app_trick_edit', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'page_title' => 'Modifier un trick'
        ]);
    }

    #[Route('/trick/delete/{slug}', name: 'app_trick_delete', methods: ["GET", "POST"])]
    public function delete(Trick $trick, TrickRepository $repo, String $thumbnailsDir): Response
    {
        $thumbnail = $thumbnailsDir . "/" . $trick->getThumbnailFilename();

        if (file_exists($thumbnail)) {
            unlink($thumbnail);
        }

        $repo->remove($trick, true);
        $this->addFlash('success', "Trick supprimé !");

        return $this->redirectToRoute('app_home');
    }

    #[Route('/trick/view/{slug}', name: 'app_trick_view', methods: ["GET", "POST"])]
    public function view(Trick $trick, Request $request, CreateCommentInterface $createComment): Response
    {
        $comment = new Comment();
        $formComment = $this->createForm(CommentAddType::class, $comment);
        $formComment->handleRequest($request);
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $createComment($comment, $trick);
            $this->addFlash('success', "Commentaire ajouté!");
            return $this->redirectToRoute('app_trick_view', ['slug' => $trick->getSlug()]);
        }

        return $this->renderForm('trick/view.html.twig', [
            'form_comment' => $formComment,
            'trick' => $trick,
            'page_title' => $trick->getName() . " - trick",
            'comments' => $trick->getComments()
        ]);
    }

    #[Route('/trick/load_more/{page}', name: 'app_trick_load_more')]
    public function load_more(int $page, TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->listPage($page, 6);
        return $this->render('Elements/trick/display_cards.html.twig', [
            'tricks' => $tricks
        ]);
    }

    #[Route('/trick/video_preview', name: 'app_trick_video_preview')]
    public function video_preview(Request $request): Response
    {
        $previewUrl = "";
        $frameTitle = "";
        $frameAllow = "";

        // Calcul des variables
        $urlArray = parse_url(urldecode($request->query->get("url")));
        if (!empty($urlArray['host'])) {
            $domain = str_replace('www.', '', $urlArray['host']);

            if (!empty($urlArray['query'])) {
                $queryArray = [];
                $splitedQuery = explode('&', $urlArray['query']);
                foreach ($splitedQuery as $strQuery) {
                    $tempSplited = explode('=', $strQuery);
                    $queryArray[$tempSplited[0]] = $tempSplited[1];
                }

                $urlArray['arrayQuery'] = $queryArray;
            }

            if (!empty($urlArray['path'])) {
                $explodedPath = explode('/', $urlArray['path']);
                foreach ($explodedPath as $pathPoint) {
                    if (!empty($pathPoint)) {
                        $urlArray['arrayPath'][] = $pathPoint;
                    }
                }
            }

            // Attribution des variables
            if (in_array($domain, ['youtube.com', 'youtu.be'])) {
                $previewUrl = "https://www.youtube.com/embed/" . $urlArray['arrayQuery']['v'];
                $frameAllow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
            } else if (in_array($domain, ['vimeo.com'])) {
                $previewUrl = "https://player.vimeo.com/video/" . $urlArray['arrayPath'][0];
                $frameAllow = "autoplay; fullscreen; picture-in-picture";
            } else if (in_array($domain, ['dailymotion.com', 'dai.ly'])) {
                // https://www.dailymotion.com/video/x8drdz2?playlist=x5nmbq
                $previewUrl = "https://www.dailymotion.com/embed/video/" . $urlArray['arrayPath'][1];
                $frameAllow = "autoplay; fullscreen; picture-in-picture";
            }

            $frameTitle = "Video from " . $domain;
        }

        return $this->render('Elements/video/preview.html.twig', [
            'preview_url' => $previewUrl,
            'frame_title' => $frameTitle,
            'frame_allow' => $frameAllow
        ]);
    }
}
