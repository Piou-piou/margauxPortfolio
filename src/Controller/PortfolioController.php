<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\EditProject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController
{
	/**
	 * @Route("/ribs-admin/portfolio/image/{id}", name="ribsadmin_portfolio_image")
	 * @return JsonResponse
	 */
	public function getImage(int $id = null)
	{
		if ($id === null) return new JsonResponse([]);
		
		$project = $this->getDoctrine()->getRepository(Project::class)->find($id);
		
		if ($project) {
			$image = $this->get("app.traitement_fine_uploader")->getImagesDisplayed($project->getImagesDir());
			
			return $image;
		} else {
			return new JsonResponse([]);
		}
	}
	
	/**
	 * @Route("/ribs-admin/portfolio/index/", name="ribsadmin_portfolio_index"))
	 */
	public function index(): Response
	{
		$articles = $this->getDoctrine()->getManager()->getRepository(Project::class)->findBy([
			"state" => Project::PUBLISHED,
		]);
		
		return $this->render("admin/portfolio/index.html.twig", [
			"articles" => $articles,
		]);
	}
	
	/**
	 * @Route("/ribs-admin/portfolio/create/", name="ribsadmin_portfolio_create"))
	 * @Route("/ribs-admin/portfolio/edit/{id}", name="ribsadmin_portfolio_edit"))
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editProject(Request $request, int $id = null): Response
	{
		$em = $this->getDoctrine()->getManager();
		
		if ($id === null) {
			$project = new Project();
			$edit = false;
		} else {
			$project = $em->getRepository(Project::class)->find($id);
			$edit = true;
		}
		
		$form = $this->createForm(EditProject::class, $project);
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($form->getData());
			$em->flush();
			
			if ($edit === true) {
				$this->addFlash("success-flash", "Le projet a été édité");
			} else {
				$this->addFlash("success-flash", "Le projet a été créé");
			}
			
			return $this->redirectToRoute('ribsadmin_portfolio_index');
		}
		
		return $this->render("admin/portfolio/edit.html.twig", [
			"form" => $form->createView(),
			"id" => $id
		]);
	}
}