<?php
// 代码生成时间: 2025-08-01 23:24:49
 * It is designed to be easily understandable and maintainable.
 */
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Imagick\Imagick;

class ImageResizer {
    private $targetWidth;
    private $targetHeight;
    private $image;
    private $imagine;

    /**
     * Constructor
     *
     * @param int $targetWidth Target width for the images
     * @param int $targetHeight Target height for the images
     */
    public function __construct($targetWidth, $targetHeight) {
        $this->targetWidth = $targetWidth;
        $this->targetHeight = $targetHeight;
        $this->imagine = new Imagine();
    }

    /**
     * Set the image to resize
     *
     * @param File $image Image to be resized
     */
    public function setImage(File $image) {
        $this->image = $image;
    }

    /**
     * Resize the image and save it
     *
     * @param string $newPath The path to save the resized image
     * @return bool True on success, false on failure
     */
    public function resizeAndSave($newPath) {
        try {
            // Load the image from path
            $this->image = $this->imagine->open($this->image->getPathname());

            // Resize the image
            $this->image->resize(new Box($this->targetWidth, $this->targetHeight));

            // Save the resized image
            $this->image->save($newPath);

            return true;
        } catch (Exception $e) {
            // Handle errors
            error_log('Error resizing image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process a batch of images
     *
     * @param array $files Array of image files to resize
     * @param string $newPathBase Base path to save the resized images
     * @return array An array of results for each image
     */
    public function processBatch(array $files, $newPathBase) {
        $results = [];
        foreach ($files as $file) {
            // Ensure the file is an instance of File or UploadedFile
            if ($file instanceof File || $file instanceof UploadedFile) {
                $this->setImage($file);
                $result = $this->resizeAndSave($newPathBase . '/' . $file->getFilename());
                $results[$file->getFilename()] = $result;
            } else {
                // Handle non-file inputs
                $results['Invalid file'] = false;
            }
        }
        return $results;
    }
}

// Usage example
// $resizer = new ImageResizer(800, 600);
// $results = $resizer->processBatch($_FILES['images'], '/path/to/save/resized/images/');
// print_r($results);
