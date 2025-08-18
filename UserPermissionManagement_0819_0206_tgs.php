<?php
// 代码生成时间: 2025-08-19 02:06:57
// UserPermissionManagement.php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

// 用户权限管理类
class UserPermissionManagement {
    /**
     * @var UserPasswordEncoderInterface 密码编码器
     */
    private $passwordEncoder;
    /**
     * @var UserProviderInterface 用户提供者
     */
    private $userProvider;
    /**
     * @var RoleHierarchyInterface 角色层级
     */
    private $roleHierarchy;
    /**
     * @var AuthorizationCheckerInterface 授权检查器
     */
    private $authorizationChecker;

    // 构造函数
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserProviderInterface $userProvider,
        RoleHierarchyInterface $roleHierarchy,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->userProvider = $userProvider;
        $this->roleHierarchy = $roleHierarchy;
        $this->authorizationChecker = $authorizationChecker;
    }

    // 添加用户
    public function addUser($username, $plainPassword, $roles): Response {
        try {
            $user = $this->userProvider->loadUserByUsername($username);
            // 用户已存在
            return new Response('User already exists', Response::HTTP_BAD_REQUEST);
        } catch (UsernameNotFoundException $e) {
            // 用户不存在，可以添加
            $user = $this->userProvider->createUser($username, $plainPassword);
            $encodedPassword = $this->passwordEncoder->encodePassword($plainPassword, null);
            $user->setPassword($encodedPassword);
            $user->setRoles($roles);
            $this->userProvider->updateUser($user);
            return new Response('User created successfully', Response::HTTP_CREATED);
        }
    }

    // 更新用户权限
    public function updateUserRoles($username, $roles): Response {
        try {
            $user = $this->userProvider->loadUserByUsername($username);
            $user->setRoles($roles);
            $this->userProvider->updateUser($user);
            return new Response('User roles updated successfully', Response::HTTP_OK);
        } catch (UsernameNotFoundException $e) {
            // 用户不存在
            return new Response('User not found', Response::HTTP_NOT_FOUND);
        } catch (UnsupportedUserException $e) {
            // 不支持的用户异常
            return new Response('Unsupported user', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // 检查用户权限
    public function checkPermission($username, $permission): bool {
        if (!$this->authorizationChecker->isGranted($permission)) {
            return false;
        }
        try {
            $userRoles = $this->userProvider->loadUserByUsername($username)->getRoles();
            foreach ($userRoles as $role) {
                if (in_array($permission, $this->roleHierarchy->getReachableRoles($role))) {
                    return true;
                }
            }
        } catch (UsernameNotFoundException $e) {
            // 用户不存在
            return false;
        }
        return false;
    }
}
