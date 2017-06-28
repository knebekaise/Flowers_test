<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is designed for managing image file uploads.
 */
class ImageController extends AbstractActionController
{
    const DEFAULT_WIDTH = 200;
    /**
     * Image manager.
     * @var Application\Service\ImageManager;
     */
    private $imageManager;

    /**
     * Constructor.
     */
    public function __construct($imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * This is the 'file' action that is invoked when a user wants to
     * open the image file in a web browser or generate a thumbnail.
     */
    public function fileAction()
    {
        // Get the file name from GET variable
        $fileName = $this->params()->fromRoute('file', '');

        $width = $this->params()->fromRoute('width', self::DEFAULT_WIDTH);

        // Validate input parameters
        if (empty($fileName) || strlen($fileName)>128) {
            throw new \Exception('File name is empty or too long');
        }

        // Resize image
        $fileName = $this->imageManager->resizeImage($fileName, $width);

        // Get image file info (size and MIME type).
        $fileInfo = $this->imageManager->getImageFileInfo($fileName);

        if ($fileInfo===false) {
            // Set 404 Not Found status code
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Write HTTP headers.
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-type: " . $fileInfo['type']);
        $headers->addHeaderLine("Content-length: " . $fileInfo['size']);

        // Write file content
        $fileContent = $this->imageManager->getImageFileContent($fileName);
        if($fileContent!==false) {
            $response->setContent($fileContent);
        } else {
            // Set 500 Server Error status code
            $this->getResponse()->setStatusCode(500);
            return;
        }

        // Return Response to avoid default view rendering.
        return $this->getResponse();
    }
}


