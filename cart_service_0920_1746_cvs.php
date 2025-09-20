<?php
// 代码生成时间: 2025-09-20 17:46:09
// cart_service.php
// This class implements a basic shopping cart functionality using Symfony framework.

namespace App\Service;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class CartService
 * This class manages the shopping cart operations such as adding items, removing items,
 * and calculating the total price.
 */
class CartService
{
    private $session;
    private $cart;

    /**
     * CartService constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->initCart();
    }

    /**
     * Initialize or retrieve the cart from the session.
     */
    private function initCart()
    {
        if (!$this->session->has('cart')) {
            $this->session->set('cart', []);
        }

        $this->cart = $this->session->get('cart');
    }

    /**
     * Add an item to the cart.
     * @param array $item The item to add, containing 'id' and 'quantity' keys.
     * @return bool
     */
    public function addItem(array $item): bool
    {
        if (!isset($item['id'], $item['quantity']) || !is_numeric($item['quantity'])) {
            // Error handling: Invalid item data.
            return false;
        }

        if (isset($this->cart[$item['id']])) {
            // If the item already exists, increment the quantity.
            $this->cart[$item['id']]['quantity'] += $item['quantity'];
        } else {
            // Add a new item to the cart.
            $this->cart[$item['id']] = $item;
        }

        $this->session->set('cart', $this->cart);

        return true;
    }

    /**
     * Remove an item from the cart.
     * @param int $itemId The ID of the item to remove.
     * @return bool
     */
    public function removeItem(int $itemId): bool
    {
        if (isset($this->cart[$itemId])) {
            unset($this->cart[$itemId]);
            $this->session->set('cart', $this->cart);
            return true;
        }

        // Error handling: Item not found in the cart.
        return false;
    }

    /**
     * Calculate the total price of the items in the cart.
     * @param array $itemsPrices The prices of the items, indexed by item ID.
     * @return float
     */
    public function calculateTotal(array $itemsPrices): float
    {
        $total = 0.0;
        foreach ($this->cart as $itemId => $item) {
            if (isset($itemsPrices[$itemId])) {
                $total += $itemsPrices[$itemId] * $item['quantity'];
            }
        }

        return $total;
    }

    /**
     * Get the current cart contents.
     * @return array
     */
    public function getCart(): array
    {
        return $this->cart;
    }
}
