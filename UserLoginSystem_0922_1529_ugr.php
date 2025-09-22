<?php
// 代码生成时间: 2025-09-22 15:29:41
// UserLoginSystem.php
// 这是一个使用Symfony框架的用户登录验证系统

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\<TokenStorageInterface>;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Security\HttpUtils;

class UserLoginSystem {

    private $security;
    private $httpUtils;
    private $userPasswordEncoder;
    private $tokenStorage;
    private $userProvider;

    // 构造函数
    public function __construct(Security $security, HttpUtils $httpUtils, UserPasswordEncoderInterface $userPasswordEncoder, TokenStorageInterface $tokenStorage, UserProviderInterface $userProvider) {
        $this->security = $security;
        $this->httpUtils = $httpUtils;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->tokenStorage = $tokenStorage;
        $this->userProvider = $userProvider;
    }

    // 用户登录验证方法
    public function login(Request $request): Response {
        try {
            // 获取请求中的用户名和密码
            $username = $request->request->get('username');
            $password = $request->request->get('password');

            // 检查用户名和密码是否为空
            if (empty($username) || empty($password)) {
                return new Response('Username or password is empty', Response::HTTP_BAD_REQUEST);
            }

            // 通过用户提供者获取用户
            $user = $this->userProvider->loadUserByUsername($username);

            // 检查用户是否存在
            if (!$user instanceof UserInterface) {
                return new Response('User not found', Response::HTTP_NOT_FOUND);
            }

            // 验证密码
            if (!$this->userPasswordEncoder->isPasswordValid($user, $password)) {
                return new Response('Invalid credentials', Response::HTTP_UNAUTHORIZED);
            }

            // 创建并设置认证令牌
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);

            // 重定向到登录成功的路由
            $successUrl = $this->httpUtils->generateUri('/success');
            return new Response('', Response::HTTP_FOUND, ['Location' => $successUrl]);

        } catch (\Exception $e) {
            // 错误处理
            return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
