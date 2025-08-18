<?php
// 代码生成时间: 2025-08-18 17:20:24
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

// InventoryController负责处理库存管理相关的请求
class InventoryController extends AbstractController
{
    private $entityManager;
    private $repository;

    // 构造函数注入EntityManager和仓库
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(InventoryItem::class);
    }

    /**
     * @Route("/inventory", name="inventory_list", methods={"GET"})
     */
    public function listInventory(): Response
    {
        try {
            $inventoryItems = $this->repository->findAll();
            return $this->json($inventoryItems);
        } catch (Exception $e) {
            // 错误处理
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/inventory/{id}", name="inventory_item", methods={"GET"})
     */
    public function getInventoryItem(int $id): Response
    {
        try {
            $item = $this->repository->find($id);
            if (!$item) {
                return $this->json(['error' => 'Inventory item not found'], Response::HTTP_NOT_FOUND);
            }
            return $this->json($item);
        } catch (Exception $e) {
            // 错误处理
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/inventory", name="add_inventory", methods={"POST"})
     */
    public function addInventory(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $item = new InventoryItem();
            $item->setName($data['name']);
            $item->setQuantity($data['quantity']);
            $item->setDescription($data['description']);
            $this->entityManager->persist($item);
            $this->entityManager->flush();
            return $this->json($item, Response::HTTP_CREATED);
        } catch (Exception $e) {
            // 错误处理
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/inventory/{id}", name="update_inventory", methods={"PUT"})
     */
    public function updateInventory(int $id, Request $request): Response
    {
        try {
            $item = $this->repository->find($id);
            if (!$item) {
                return $this->json(['error' => 'Inventory item not found'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            $item->setName($data['name'] ?? $item->getName());
            $item->setQuantity($data['quantity'] ?? $item->getQuantity());
            $item->setDescription($data['description'] ?? $item->getDescription());
            $this->entityManager->flush();
            return $this->json($item);
        } catch (Exception $e) {
            // 错误处理
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/inventory/{id}", name="delete_inventory", methods={"DELETE"})
     */
    public function deleteInventory(int $id): Response
    {
        try {
            $item = $this->repository->find($id);
            if (!$item) {
                return $this->json(['error' => 'Inventory item not found'], Response::HTTP_NOT_FOUND);
            }
            $this->entityManager->remove($item);
            $this->entityManager->flush();
            return $this->json(['message' => 'Inventory item deleted successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            // 错误处理
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

// InventoryItem是库存项的实体类
class InventoryItem
{
    private $id;
    private $name;
    private $quantity;
    private $description;

    // Getter和Setter方法
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
