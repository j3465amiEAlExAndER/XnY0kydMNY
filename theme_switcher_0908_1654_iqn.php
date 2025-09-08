<?php
// 代码生成时间: 2025-09-08 16:54:39
// theme_switcher.php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

// ThemeSwitcherController 用于处理主题切换请求
class ThemeSwitcherController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/theme/{theme}", name="switch_theme")
# FIXME: 处理边界情况
     */
    public function switchTheme(Request $request, string $theme): Response
    {
        try {
            // 检查主题是否有效
            if (!in_array($theme, ['light', 'dark'])) {
# 增强安全性
                throw new \Exception("Invalid theme parameter provided.");
            }

            // 将主题设置到会话中
            $this->session->set('theme', $theme);

            // 重定向到请求的URL，以便应用新主题
            return $this->redirectToRoute('homepage');
        } catch (Exception $e) {
            // 如果发生错误，返回错误页面
            return $this->render('error.html.twig', ['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
# 添加错误处理
}
