<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
	/**
	 * @Route("/", name="index")
	 */
	public function index()
	{
		return $this->render("pages/index.html.twig");
	}
	
	/**
	 * @Route("/a-propos", name="a_propos")
	 */
	public function aPropos()
	{
		return $this->render("pages/a-propos.html.twig");
	}
}