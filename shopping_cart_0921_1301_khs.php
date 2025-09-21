<?php
// 代码生成时间: 2025-09-21 13:01:26
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartItemRepository;
use App\Service\ProductService;

// ShoppingCartController handles the shopping cart related operations
class ShoppingCartController {
    // @var EntityManagerInterface
    private $entityManager;
    // @var Serializer
    private $serializer;
    // @var ProductService
    private $productService;
    // @var CartItemRepository
    private $cartItemRepository;

    // Constructor to inject dependencies
    public function __construct(
        EntityManagerInterface $entityManager,
        Serializer $serializer,
        ProductService $productService,
        CartItemRepository $cartItemRepository
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->productService = $productService;
        $this->cartItemRepository = $cartItemRepository;
    }

    // Adds an item to the shopping cart
    public function addToCart(Request $request): Response {
        try {
            $data = json_decode($request->getContent(), true);
            if (empty($data['productId'])) {
                return new JsonResponse(['message' => 'Product ID is required'], Response::HTTP_BAD_REQUEST);
            }

            $productId = $data['productId'];
            $quantity = $data['quantity'] ?? 1; // Default quantity to 1 if not provided

            $product = $this->productService->findProductById($productId);
            if (!$product) {
                return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }

            // Check if item already exists in cart
            $cartItem = $this->cartItemRepository->findOneBy(['product' => $product, 'user' => $this->getUser()]);
            if ($cartItem) {
                $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
            } else {
                $cartItem = new CartItem();
                $cartItem->setProduct($product);
                $cartItem->setQuantity($quantity);
            }

            $this->entityManager->persist($cartItem);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Item added to cart', 'cartItem' => $this->serializer->serialize($cartItem, 'json')], Response::HTTP_OK);
        } catch (ORMException $e) {
            return new JsonResponse(['message' => 'Error adding item to cart'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Removes an item from the shopping cart
    public function removeFromCart(Request $request): Response {
        try {
            $data = json_decode($request->getContent(), true);
            if (empty($data['cartItemId'])) {
                return new JsonResponse(['message' => 'Cart Item ID is required'], Response::HTTP_BAD_REQUEST);
            }

            $cartItemId = $data['cartItemId'];
            $cartItem = $this->cartItemRepository->find($cartItemId);
            if (!$cartItem) {
                return new JsonResponse(['message' => 'Cart item not found'], Response::HTTP_NOT_FOUND);
            }

            $this->entityManager->remove($cartItem);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Item removed from cart'], Response::HTTP_OK);
        } catch (ORMException $e) {
            return new JsonResponse(['message' => 'Error removing item from cart'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Retrieves the current user's shopping cart
    public function getCart(Request $request): Response {
        $user = $this->getUser();
        $cartItems = $this->cartItemRepository->findBy(['user' => $user]);

        return new JsonResponse(['cartItems' => $this->serializer->serialize($cartItems, 'json')], Response::HTTP_OK);
    }

    // Retrieves the current user
    private function getUser(): ?object {
        // This method should return the current user object,
        // which would typically be retrieved from the security context
        // in a real Symfony application. For demonstration purposes,
        // we return null indicating no user is currently logged in.
        return null;
    }
}
