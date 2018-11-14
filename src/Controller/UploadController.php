<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\FineUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UploadController extends Controller
{
	/**
	 * @Route("/upload-sesssion/defaut", name="upload_defaut")
	 * @return JsonResponse
	 */
	public function defaultSessionAction(): JsonResponse
	{
		return new JsonResponse([]);
	}
	
	/**
	 * @Route("/upload/{uuid}", name="upload")
	 * @param string $uuid
	 * @return JsonResponse
	 */
	public function UploadAction($uuid = ""): JsonResponse
	{
		/**
		 * PHP Server-Side Example for Fine Uploader (traditional endpoint handler).
		 * Maintained by Widen Enterprises.
		 *
		 * This example:
		 *  - handles chunked and non-chunked requests
		 *  - supports the concurrent chunking feature
		 *  - assumes all upload requests are multipart encoded
		 *  - supports the delete file feature
		 *
		 * Follow these steps to get up and running with Fine Uploader in a PHP environment:
		 *
		 * 1. Setup your client-side code, as documented on http://docs.fineuploader.com.
		 *
		 * 2. Copy this file and handler.php to your server.
		 *
		 * 3. Ensure your php.ini file contains appropriate values for
		 *    max_input_time, upload_max_filesize and post_max_size.
		 *
		 * 4. Ensure your "chunks" and "files" folders exist and are writable.
		 *    "chunks" is only needed if you have enabled the chunking feature client-side.
		 *
		 * 5. If you have chunking enabled in Fine Uploader, you MUST set a value for the `chunking.success.endpoint` option.
		 *    This will be called by Fine Uploader when all chunks for a file have been successfully uploaded, triggering the
		 *    PHP server to combine all parts into one file. This is particularly useful for the concurrent chunking feature,
		 *    but is now required in all cases if you are making use of this PHP example.
		 */
		
		$uploader = new FineUploader();
		
		// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$uploader->allowedExtensions = []; // all files types allowed by default
		
		// Specify max file size in bytes.
		$uploader->sizeLimit = null;
		
		// Specify the input name set in the javascript.
		$uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
		
		// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
		$uploader->chunksFolder = "chunks";
		
		$ribs_tmp_directory = $this->getParameter("ribs_tmp_directory");
		
		$method = $_SERVER["REQUEST_METHOD"];
		if ($method == "POST") {
			header("Content-Type: text/plain");
			// Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
			// For example: /myserver/handlers/endpoint.php?done
			if (isset($_GET["done"])) {
				$result = $uploader->combineChunks($ribs_tmp_directory);
			} else {
				// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
				$result = $uploader->handleUpload($ribs_tmp_directory);
				
				// To return a name used for uploaded file you can use the following line.
				$result["uploadName"] = $uploader->getUploadName();
			}
			$response = new JsonResponse();
			$response->setData($result);
			
			return $response;
		} else if ($method == "DELETE") {
			$response = new JsonResponse();
			
			$file = glob($ribs_tmp_directory . "/" . $uuid . ".*");
			
			if (count($file) > 0) {
				$result = $uploader->handleDelete($ribs_tmp_directory, $uuid);
				$response->setData($result);
			} else {
				$response->setData(["success" => false, "server_file" => true]);
			}
			
			return $response;
		} else {
			$result = ["error" => " Method Not Allowed"];
			$response = new JsonResponse();
			$response->setData($result);
			
			return $response;
		}
	}
}