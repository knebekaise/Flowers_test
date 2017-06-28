<?php
/**
 * A service model class encapsulating the functionality for image management.
 */
namespace Application\Service;

/**
 * The image manager service. Responsible for getting the list of uploaded
 * files and resizing the images.
 */
class ImageManager
{
    /**
     * The directory where we save image files.
     * @var string
     */
    private $saveToDir = './data/avatars/';

    private $resizedDir = './data/avatars/resize/';


    public function __construct()
    {
        // Check whether the directory already exists, and if not,
        // create the directory.
        if (!is_dir($this->saveToDir)) {
            if (!mkdir($this->saveToDir)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }

        if (!is_dir($this->resizedDir)) {
            if (!mkdir($this->resizedDir)) {
                throw new \Exception('Could not create directory for uploads: '. error_get_last());
            }
        }
    }

    /**
     * Returns path to the directory where we save the image files.
     * @return string
     */
    public function getSaveToDir()
    {
        return $this->saveToDir;
    }

    /**
     * Get file extension by file name
     * @param  string $fileName
     * @return string
     */
    public function getFileExtension($fileName)
    {
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }

    /**
     * Move uploaded file
     * @param  string $tmpFileName
     * @param  string $fileName
     * @return bool
     *
     * @throws \Exception when file upload directory cann`t be created
     */
    public function moveUploadedFile($tmpFileName, $fileName)
    {
        return move_uploaded_file($tmpFileName, $this->saveToDir . $fileName);
    }

    /**
     * Returns the path to the saved image file.
     * @param string $fileName Image file name (without path part).
     * @return string Path to image file.
     */
    public function getImagePathByName($fileName)
    {
        // Take some precautions to make file name secure
        $fileName = str_replace("/", "", $fileName);  // Remove slashes
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes

        // Return concatenated directory name and file name.
        return $this->saveToDir . $fileName;
    }

    /**
     * Retrieves the file information (size, MIME type) by image path.
     * @param string $filePath Path to the image file.
     * @return array File information.
     */
    public function getImageFileInfo($filePath)
    {
        // Try to open file
        if (!is_readable($filePath)) {
            return false;
        }

        // Get file size in bytes.
        $fileSize = filesize($filePath);

        // Get MIME type of the file.
        $finfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($finfo, $filePath);
        if ($mimeType === false) {
            $mimeType = 'application/octet-stream';
        }

        return [
            'size' => $fileSize,
            'type' => $mimeType
        ];
    }

    /**
     * Returns the image file content. On error, returns boolean false.
     * @param string $filePath Path to image file.
     * @return string|false
     */
    public function getImageFileContent($filePath)
    {
        return file_get_contents($filePath);
    }

    /**
     * Resizes the image, keeping its aspect ratio.
     * @param string $filePath
     * @param int $desiredWidth
     * @return string Resulting file name.
     */
    public function resizeImage($fileName, $desiredWidth = 240)
    {
        $currentResizeDir = $this->resizedDir . $desiredWidth;
        $resizedFileName = $currentResizeDir . '/' . $fileName;
        if (file_exists($resizedFileName)) {
            return $resizedFileName;
        }

        $filePath = $this->saveToDir . $fileName;

        if (!is_dir($currentResizeDir)) {
            if (!mkdir($currentResizeDir)) {
                throw new \Exception('Could not create directory for resizes: '. error_get_last());
            }
        }

        // Get original image dimensions.
        list($originalWidth, $originalHeight) = getimagesize($filePath);

        // Calculate aspect ratio
        $aspectRatio = $originalWidth/$originalHeight;
        // Calculate the resulting height
        $desiredHeight = ceil($desiredWidth/$aspectRatio);

        // Get image info
        $fileInfo = $this->getImageFileInfo($filePath);

        // Resize the image
        $resultingImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
        if (substr($fileInfo['type'], 0, 9) =='image/png') {
            $originalImage = imagecreatefrompng($filePath);
            imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0,
                    $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);
            imagepng($resultingImage, $resizedFileName, 8);
        } else {
            $originalImage = imagecreatefromjpeg($filePath);
            imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0,
                    $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);
            imagejpeg($resultingImage, $resizedFileName, 80);
        }

        // Return the path to resulting image.
        return $resizedFileName;
    }
}
