<?php

namespace App\Controller;

use App\Entity\Project;
use App\Service\FileTreaterFineUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
	
	/**
	 * @Route("/contact/send", name="contact_send")
	 * @param Request $request
	 * @param \Swift_Mailer $mailer
	 */
	public function sendForm(Request $request, \Swift_Mailer $mailer) {
		$message = "<h2> Message de la part de  :".$request->get("firstname")." ". $request->get("lastname") ."</h2><br><br>";
		
		$mail = $message.$request->get("message");
		
		$message = (new \Swift_Message("Message de margauxbailly.fr, sujet : ". $request->get("object")))
			->setFrom($request->get("email"))
			->setTo("pilloud.anthony@gmail.com")
			->setBody($mail, "text/html");
		
		$mailer->send($message);
		$this->addFlash("success", "Votre message a été envoyé");
		
		$this->redirectToRoute("contact");
	}
}