<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController
{
	
	/**
	 * @Route("/ribs-admin/portfolio/index/", name="ribsadmin_portfolio_index"))
	 */
	public function index(): Response
	{
		return $this->render("admin/portfolio/index.html.twig", [
			"articles" => [],
		]);
	}
	
	/**
	 * @Route("/ribs-admin/portfolio/create/", name="ribsadmin_portfolio_create"))
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editProject(Request $request): Response
	{
		return $this->render("admin/portfolio/index.html.twig");
	}
}