<?php
// 代码生成时间: 2025-08-11 16:54:28
// inventory_management.php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// InventoryController 处理库存管理相关的请求
class InventoryController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inventory", name="inventory_index")
     */
    public function index(): Response
    {
        // 获取所有库存项
        $inventoryItems = $this->entityManager->getRepository(Item::class)->findAll();

        return $this->render('inventory/index.html.twig', [
            'inventoryItems' => $inventoryItems,
        ]);
    }

    /**
     * @Route("/inventory/add", name="inventory_add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        // 获取表单数据
        $itemName = $request->request->get('item_name');
        $quantity = $request->request->get('quantity');

        // 验证数据
        if (empty($itemName) || empty($quantity)) {
            return $this->json(['error' => 'Item name and quantity are required.'], Response::HTTP_BAD_REQUEST);
        }

        // 创建新的库存项
        $item = new Item();
        $item->setName($itemName);
        $item->setQuantity((int)$quantity);

        try {
            // 保存到数据库
            $this->entityManager->persist($item);
            $this->entityManager->flush();

            return $this->json(['message' => 'Item added successfully.'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // 错误处理
            return $this->json(['error' => 'Failed to add item.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/inventory/{id}/update", name="inventory_update", methods={"POST"})
     */
    public function update(Request $request, $id): Response
    {
        // 查找库存项
        $item = $this->entityManager->getRepository(Item::class)->find($id);

        if (!$item) {
            return $this->json(['error' => 'Item not found.'], Response::HTTP_NOT_FOUND);
        }

        // 获取表单数据
        $quantity = $request->request->get('quantity');

        // 验证数据
        if (empty($quantity)) {
            return $this->json(['error' => 'Quantity is required.'], Response::HTTP_BAD_REQUEST);
        }

        // 更新库存项
        $item->setQuantity((int)$quantity);

        try {
            // 保存到数据库
            $this->entityManager->flush();

            return $this->json(['message' => 'Item updated successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            // 错误处理
            return $this->json(['error' => 'Failed to update item.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

// Item 实体类
class Item
{
    private $id;
    private $name;
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}
