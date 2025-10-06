<?php
// 代码生成时间: 2025-10-07 01:51:23
// Blockchain Explorer using PHP and Symfony Framework
// Filename: blockchain_explorer.php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlockchainExplorerController extends AbstractController
{
    private $blockchainService;

    // Constructor for dependency injection
    public function __construct(BlockChainService $blockchainService)
    {
        $this->blockchainService = $blockchainService;
    }

    /**
     * @Route("/blocks", name="blocks_list")
     * Lists all blocks in the blockchain
     */
    public function listBlocks(): Response
    {
        try {
            $blocks = $this->blockchainService->getAllBlocks();
            return $this->json($blocks);
        } catch (\Exception $e) {
            // Error handling
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @Route("/blocks/{id}", name="block_detail", requirements={"id"="\d+"})
     * Displays a specific block's details based on its ID
     */
    public function showBlock(int $id): Response
    {
        try {
            $block = $this->blockchainService->getBlockById($id);
            if (null === $block) {
                return $this->json(['error' => 'Block not found'], 404);
            }
            return $this->json($block);
        } catch (\Exception $e) {
            // Error handling
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}

class BlockChainService
{
    private $blocks;

    public function __construct()
    {
        $this->blocks = []; // Initialize the blockchain array
    }

    public function getAllBlocks(): array
    {
        return $this->blocks;
    }

    public function getBlockById(int $id): ?array
    {
        foreach ($this->blocks as $block) {
            if ($block['id'] === $id) {
                return $block;
            }
        }
        return null;
    }
}
