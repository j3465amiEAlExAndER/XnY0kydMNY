<?php
// 代码生成时间: 2025-10-12 02:25:24
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

// 内容管理系统类
class ContentManagementSystem extends AbstractController {

    /**
     * @Route("/content", name="content_index")
     * @return JsonResponse
     */
    public function index(): JsonResponse {
        try {
            $content = $this->loadContent();
            return new JsonResponse(['content' => $content]);
        } catch (IOExceptionInterface $e) {
            return new JsonResponse(['error' => 'File not found or unable to read'], Response::HTTP_NOT_FOUND);
        } catch (ParseException $e) {
            return new JsonResponse(['error' => 'Unable to parse YAML content'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/content/{id}", name="content_show", requirements={"id":"\d+"})
     * @param int $id The content ID
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse {
        try {
            $content = $this->loadContent($id);
            return new JsonResponse(['content' => $content]);
        } catch (IOExceptionInterface $e) {
            return new JsonResponse(['error' => 'Content not found'], Response::HTTP_NOT_FOUND);
        } catch (ParseException $e) {
            return new JsonResponse(['error' => 'Unable to parse YAML content'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Load content from YAML file

     * @param int|null $id The content ID to filter by, null for all content
     * @return array
     * @throws IOExceptionInterface
     * @throws ParseException
     */
    private function loadContent(?int $id = null): array {
        $fs = new Filesystem();
        $contentPath = $this->getParameter('kernel.project_dir') . '/config/content.yaml';

        if (!$fs->exists($contentPath)) {
            throw new IOExceptionInterface('The content file does not exist.');
        }

        $yaml = Yaml::parseFile($contentPath);

        if (null !== $id && isset($yaml[$id])) {
            return $yaml[$id];
        }

        return $yaml;
    }
}
