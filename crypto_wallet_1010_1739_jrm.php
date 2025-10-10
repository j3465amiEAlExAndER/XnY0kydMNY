<?php
// 代码生成时间: 2025-10-10 17:39:52
// crypto_wallet.php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

// CryptoWalletController 控制器类
class CryptoWalletController extends AbstractController
{
    private $entityManager;

    // 构造函数注入EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/wallet", name="wallet", methods={"GET"})
     */
    public function index(): Response
    {
        // 获取所有钱包记录
        $wallets = $this->entityManager->getRepository(Wallet::class)->findAll();
        return $this->json($wallets);
    }

    /**
     * @Route("/wallet/add", name="wallet_add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        try {
            $walletData = json_decode($request->getContent(), true);
            // 验证数据
            if (null === $walletData || !isset($walletData['name']) || !isset($walletData['currency'])) {
                throw new Exception('Invalid data');
            }

            // 创建钱包实体并保存
            $wallet = new Wallet();
            $wallet->setName($walletData['name']);
            $wallet->setCurrency($walletData['currency']);
            $this->entityManager->persist($wallet);
            $this->entityManager->flush();

            return $this->json($wallet, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}

// CryptoWallet 实体类
class Wallet
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $currency = null;

    // getters and setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
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

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }
}
