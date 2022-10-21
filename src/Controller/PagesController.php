<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
	#[Route('/', name: 'app_home')]
	public function index(TrickRepository $trickRepository): Response
	{
		$tricks = $trickRepository->listPage(0, 6);

		return $this->render('pages/home.html.twig', [
			'controller_name' => 'PagesController',
			'tricks' => $tricks,
			'page_title' => 'Accueil',
		]);
	}
}
