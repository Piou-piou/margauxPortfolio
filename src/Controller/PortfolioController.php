<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController {
	
	/**
	 * @Route("/ribs-admin/portfolio/index/", name="ribsadmin_portfolio_index"))
	 */
	public function index() {
		return $this->render("admin/portfolio/index.html.twig", [
			"articles" => []
		]);
	}
	
	/**
	 * @Route("/ribs-admin/portfolio/create/", name="ribsadmin_portfolio_create"))
	 */
	public function createProject() {
		return $this->render("admin/portfolio/index.html.twig");
	}
}