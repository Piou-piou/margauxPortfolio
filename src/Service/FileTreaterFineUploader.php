<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class FileTreaterFineUploader
{
	/**
	 * @var ContainerInterface
	 */
	private $container;
	
	/**
	 * @var RequestStack
	 */
	private $request;
	
	private $files = [];
	private $paths_files = [];
	
	/**
	 * @param ContainerInterface $container
	 * @param RequestStack $request
	 */
	public function __construct(ContainerInterface $container, RequestStack $request)
	{
		$this->container = $container;
		$this->request = $request;
	}
	
	//-------------------------- GETTER ----------------------------------------------------------------------------//
	/**
	 * @param string $path represent path to go to file
	 * @return JsonResponse
	 */
	public function getImagesDisplayed(string $path = null): JsonResponse
	{
		$dir = $this->container->getParameter("ribs_data_directory") . $path;
		
		$objects = [];
		
		if (is_file($dir)) {
			$explode = explode("/", $dir);
			$image = end($explode);
			$uuid = explode(".", $image)[0];
			
			$obj = new \stdClass();
			$obj->name = $uuid;
			$obj->uuid = $uuid;
			$obj->thumbnailUrl = $this->container->get("router")->generate("show_resource", ["file" => $path]);
			
			$objects[] = $obj;
		} else if (is_dir($dir)) {
			$dh = opendir($dir);
			while (false !== ($filename = readdir($dh))) {
				if ($filename !== "." && $filename !== "..") {
					$uuid = substr($filename, 0, strrpos($filename, '.'));
					
					$obj = new \stdClass();
					$obj->name = $uuid;
					$obj->uuid = $uuid;
					$obj->thumbnailUrl = $this->container->get("router")->generate("show_resource", ["file" => $path . "/" . $filename]);
					
					$objects[] = $obj;
				}
			}
		}
		
		return new JsonResponse($objects);
	}
	
	/**
	 * @return array
	 */
	public function getFiles(): array
	{
		return $this->files;
	}
	//-------------------------- END GETTER ----------------------------------------------------------------------------//
	
	/**
	 * @param string $destination
	 * method that move files in desired folder
	 */
	private function moveFiles(string $destination)
	{
		$fs = new Filesystem();
		$tmp_dir = $this->container->getParameter("ribs_tmp_directory") . "/";
		$data_dir = $this->container->getParameter("ribs_data_directory_relatif") . $destination;
		$new_files_dir = $this->createRecursiveDirFromRoot($data_dir);
		
		$new_files = [];
		foreach ($this->files as $file) {
			if (!is_file($new_files_dir . $file) && is_file($tmp_dir . '/' . $file)) {
				$new_name = $destination . $file;
				$fs->rename($tmp_dir . $file, $new_files_dir . "/" . $file);
				
				array_push($new_files, $new_name);
				array_push($this->paths_files, $new_files_dir . $file);
			} else if (is_file($new_files_dir . $file)) {
				$new_name = $destination . $file;
				
				//if file exist yet, we keep it in $this->files
				array_push($new_files, $new_name);
				array_push($this->paths_files, $new_files_dir . $file);
			}
		}
		
		$this->files = $new_files;
	}
	
	/**
	 * @param array $files_to_delete
	 * @param string $destination
	 * method to delete old files
	 */
	private function deleteOldFile(array $files_to_delete, string $destination)
	{
		$fs = new Filesystem();
		$data_dir = $this->container->getParameter("ribs_data_directory") . $destination;
		
		foreach ($files_to_delete as $file) {
			$file_to_delete = $data_dir . $file;
			
			//we delete image or file only if different of profile image
			if (is_file($file_to_delete) && basename($file_to_delete) !== "profile.png") {
				$fs->remove($file_to_delete);
			}
		}
	}
	
	/**
	 * @param string $destination
	 * @return $this
	 * method that initialize treatment of uploaded files with fineUploader
	 */
	public function treatFiles(string $destination): self
	{
		$files_to_delete = json_decode($this->request->getCurrentRequest()->get("input-fine-uploader-delete"));
		
		if (count($files_to_delete) > 0) {
			$this->deleteOldFile($files_to_delete, $destination);
		}
		
		$this->files = json_decode($this->request->getCurrentRequest()->get("input-fine-uploader"));
		
		if (count($this->files) === 0) {
			return $this;
		}
		
		$this->moveFiles($destination);
		
		return $this;
	}
	
	/**
	 * @param int $width
	 * @param int $height
	 * @return $this
	 * @throws \ImagickException
	 * method wich resize image that were sent by FineUploader
	 */
	public function resizeImages(int $width, int $height)
	{
		$extensions_images = ["jpg", "png", "gif", "jpeg", "bmp"];
		
		foreach ($this->paths_files as $file) {
			$explode = explode(".", $file);
			$extension = strtolower(end($explode));
			
			if (in_array($extension, $extensions_images)) {
				$imagik = new \Imagick($file);
				$imagik->adaptiveResizeImage($width, $height, true);
				$imagik->writeImage($file);
			}
		}
		
		return $this;
	}
	
	/**
	 * @param $path
	 * @return string
	 */
	private function createRecursiveDirFromRoot($path): string
	{
		$fs = new Filesystem();
		$root_dir = $this->container->get("kernel")->getRootDir() . "/../";
		$new_path = $root_dir;
		$dossiers = explode("/", $path);
		
		foreach ($dossiers as $index => $dossier) {
			$new_path .= $dossier;
			
			if (!$fs->exists($new_path)) {
				$fs->mkdir($new_path);
			}
			
			if ($index + 1 < count($dossiers)) {
				$new_path .= "/";
			}
		}
		
		return $new_path;
	}
	
	/**
	 * @return string
	 */
	public function genGuid(): string
	{
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			
			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),
			
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,
			
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,
			
			// 48 bits for "node"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}
}