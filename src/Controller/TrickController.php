<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickEditType;
use App\Form\CommentAddType;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Trick\CreateTrickInterface;

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
    public function edit(Trick $trick, Request $request, TrickRepository $repo, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TrickEditType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($trick->getImages() as $image) {
                $thumbnailFile = $image->getImage();

                if ($thumbnailFile) {
                    $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $thumbnailFile->guessExtension();

                    $thumbnailFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                    $image->setFilename($newFilename);
                }
            }

            $thumbnailFile = $form->get('thumbnail')->getData();

            if ($thumbnailFile) {
                //Supprimer l'ancienne thumbnail si elle existe
                $oldTrick = $repo->get($trick->getId());
                if (!empty($oldTrick->getThumbnailFilename())) {
                    $oldThumbnail = $this->getParameter('thumbnails_directory') . "/" . $oldTrick->getThumbnailFilename();

                    if (file_exists($oldThumbnail) && is_file($oldThumbnail)) {
                        unlink($oldThumbnail);
                    }
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
            'trick' => $trick,
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
    public function view(Trick $trick, Request $request, UserRepository $userRepo, TrickRepository $trickRepo): Response
    {
        $comment = new Comment();
        $formComment = $this->createForm(CommentAddType::class, $comment);
        $formComment->handleRequest($request);
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $username = $this->getUser()->getUserIdentifier();
            $user = $userRepo->get($username);
            $comment->setAuthor($user);
            $comment->setCreated(new \DateTime());
            $trick->addComment($comment);
            $trickRepo->add($trick, true);
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
        $preview_url = "";
        $frame_title = "";
        $frame_allow = "";

        // Calcul des variables
        $url_arr = parse_url(urldecode($request->query->get("url")));
        if (!empty($url_arr['host'])) {
            $domain = str_replace('www.', '', $url_arr['host']);

            if (!empty($url_arr['query'])) {
                $query_arr = [];
                $splited_query = explode('&', $url_arr['query']);
                foreach ($splited_query as $str_query) {
                    $temp_splited = explode('=', $str_query);
                    $query_arr[$temp_splited[0]] = $temp_splited[1];
                }

                $url_arr['array_query'] = $query_arr;
            }

            if (!empty($url_arr['path'])) {
                $exploded_path = explode('/', $url_arr['path']);
                foreach ($exploded_path as $path_point) {
                    if (!empty($path_point)) {
                        $url_arr['array_path'][] = $path_point;
                    }
                }
            }

            // Attribution des variables
            if (in_array($domain, ['youtube.com', 'youtu.be'])) {
                $preview_url = "https://www.youtube.com/embed/" . $url_arr['array_query']['v'];
                $frame_allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
            } else if (in_array($domain, ['vimeo.com'])) {
                $preview_url = "https://player.vimeo.com/video/" . $url_arr['array_path'][0];
                $frame_allow = "autoplay; fullscreen; picture-in-picture";
            } else if (in_array($domain, ['dailymotion.com', 'dai.ly'])) {
                // https://www.dailymotion.com/video/x8drdz2?playlist=x5nmbq
                $preview_url = "https://www.dailymotion.com/embed/video/" . $url_arr['array_path'][1];
                $frame_allow = "autoplay; fullscreen; picture-in-picture";
            }

            $frame_title = "Video from " . $domain;
        }

        return $this->render('Elements/video/preview.html.twig', [
            'preview_url' => $preview_url,
            'frame_title' => $frame_title,
            'frame_allow' => $frame_allow
        ]);
    }
}
