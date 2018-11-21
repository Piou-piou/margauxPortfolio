<?php

namespace App\Controller;

use App\Entity\Project;
use App\Service\FileTreaterFineUploader;
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
	public function projets(FileTreaterFineUploader $fine_uploader)
	{
		$projects = $this->getDoctrine()->getManager()->getRepository(Project::class)->findBy([
			"state" => Project::PUBLISHED,
		]);
		
		$images = [];
		
		foreach ($projects as $project) {
			$image = null;
			
			if ($project->getImagesDir() !== null) {
				$image = json_decode($fine_uploader->getImagesDisplayed($project->getImagesDir())->getContent());
				$images[$project->getId()] = $image[0]->thumbnailUrl;
			}
		}
		
		return $this->render("pages/projets.html.twig", [
			"projects" => $projects,
			"images" => $images
		]);
	}
	
	/**
	 * @Route("/projets/{id}", name="project")
	 */
	public function project(FileTreaterFineUploader $fine_uploader, int $id)
	{
		$project = $this->getDoctrine()->getManager()->getRepository(Project::class)->find($id);
		
		$image = null;
		
		if ($project->getImagesDir() !== null) {
			$image = json_decode($fine_uploader->getImagesDisplayed($project->getImagesDir())->getContent());
		}
		
		return $this->render("pages/projet.html.twig", [
			"project" => $project,
			"images" => $image
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