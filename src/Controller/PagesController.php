<?php

namespace App\Controller;

use App\Entity\Project;
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
	 * @Route("/projets", name="projets")
	 */
	public function projets()
	{
		$projects = $this->getDoctrine()->getManager()->getRepository(Project::class)->findBy([
			"state" => Project::PUBLISHED,
		]);
		
		return $this->render("pages/projets.html.twig", [
			"projects" => $projects
		]);
	}
	
	/**
	 * @Route("/a-propos", name="a_propos")
	 */
	public function aPropos()
	{
		return $this->render("pages/a-propos.html.twig");
	}
	
	/**
	 * @Route("/contact", name="contact")
	 */
	public function contact()
	{
		return $this->render("pages/contact.html.twig");
	}
}