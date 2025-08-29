<?php
// 代码生成时间: 2025-08-29 14:13:19
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * InventoryController handles inventory management functionalities.
 */
class InventoryController extends AbstractController
{
    private $entityManager;
    private $validator;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/inventory", name="inventory_index", methods={"GET"})
     */
    public function index(): Response
    {
        try {
            $inventories = $this->entityManager->getRepository(Inventory::class)->findAll();
            $data = $this->serializer->serialize($inventories, 'json');

            return new Response($data);
        } catch (\Exception $e) {
            return new Response(\$this->serializer->serialize(['error' => $e->getMessage()], 'json'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/inventory", name="inventory_add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        try {
            $content = $request->getContent();
            $data = \$this->serializer->deserialize($content, Inventory::class, 'json');
            $errors = \$this->validator->validate($data);

            if (count($errors) > 0) {
                return new Response(\$this->serializer->serialize(['errors' => $errors]), Response::HTTP_BAD_REQUEST);
            }

            \$this->entityManager->persist($data);
            \$this->entityManager->flush();

            return new Response(\$this->serializer->serialize($data), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new Response(\$this->serializer->serialize(['error' => $e->getMessage()]), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

/**
 * Inventory entity class.
 */
class Inventory
{
    private \$id;
    private \$name;
    private \$quantity;
    private \$price;

    // Getters and setters for each property
    public function getId(): ?int
    {
        return \$this->id;
    }

    public function setId(int \$id): self
    {
        \$this->id = \$id;
        return \$this;
    }

    public function getName(): ?string
    {
        return \$this->name;
    }

    public function setName(string \$name): self
    {
        \$this->name = \$name;
        return \$this;
    }

    public function getQuantity(): ?int
    {
        return \$this->quantity;
    }

    public function setQuantity(int \$quantity): self
    {
        \$this->quantity = \$quantity;
        return \$this;
    }

    public function getPrice(): ?float
    {
        return \$this->price;
    }

    public function setPrice(float \$price): self
    {
        \$this->price = \$price;
        return \$this;
    }
}
