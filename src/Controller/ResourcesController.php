<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResourcesController extends Controller
{
	/**
	 * @Route("/resource/{file}", name="show_resource", requirements={"file"=".+"})
	 * @return Response
	 */
	public function getImageAction(string $file = null): Response
	{
		if ($file === null) {
			return new Response();
		}
		
		$image = $this->container->get("kernel")->getRootDir() . "/../data/" . $file;
		
		if (file_exists($image)) {
			$file_name = basename($image);
			$mime_type = $this->getMimeType($image);
			
			header("Content-type: ".$mime_type);
			header("Content-Disposition: inline; filename=".$file_name);
			
			readfile($image);
		}
		
		return new Response();
	}
	
	/**
	 * @param $file
	 * @return string
	 * récupération du type de l'image
	 */
	private function getMimeType($file): string
	{
		if ('jpg' === substr($file, -3)) {
			$best_guess = 'jpeg';
		} else {
			$guesser = MimeTypeGuesser::getInstance();
			$best_guess = $guesser->guess($file);
		}
		
		return $best_guess;
	}
}