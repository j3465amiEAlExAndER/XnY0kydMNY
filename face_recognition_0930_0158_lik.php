<?php
// 代码生成时间: 2025-09-30 01:58:25
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Face Recognition Controller
 *
 * This controller is responsible for handling face recognition functionality.
 * It uses OpenCV and Tesseract libraries for image processing and OCR.
 */
class FaceRecognitionController extends AbstractController
{
    private $faceApiUrl;
    private $apiKey;

    public function __construct()
    {
        $this->faceApiUrl = 'https://api.cloudmersive.com/image/recognize-face';
        $this->apiKey = 'your_api_key_here';
    }

    /**
     * Handles a POST request to perform face recognition
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function recognizeFace(Request $request): JsonResponse
    {
        try {
            $image = $request->files->get('image');
            if (!$image) {
                throw new \Exception('No image provided.');
            }

            $imageUrl = $this->uploadImageToTempDir($image);
            $response = $this->callFaceRecognitionApi($imageUrl);

            $result = json_decode($response->getContent(), true);
            if (empty($result['faces'])) {
                throw new \Exception('No faces detected in the image.');
            }

            return new JsonResponse($result);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Uploads the image to a temporary directory and returns the URL
     *
     * @param UploadedFile $image
     * @return string
     */
    private function uploadImageToTempDir(UploadedFile $image): string
    {
        $path = sys_get_temp_dir() . '/' . uniqid('face_recognition_', true) . '.' . $image->getClientOriginalExtension();

        $image->move($path);
        return 'file://' . $path;
    }

    /**
     * Calls the face recognition API and returns the response
     *
     * @param string $imageUrl
     * @return Response
     */
    private function callFaceRecognitionApi(string $imageUrl): Response
    {
        $process = new Process(['curl', $this->faceApiUrl, '-X', 'POST', '-H', 'api-secret: ' . $this->apiKey, '-F', 'imageUrl=' . escapeshellarg($imageUrl)]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return new Response($process->getOutput(), Response::HTTP_OK);
    }
}
