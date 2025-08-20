<?php
// 代码生成时间: 2025-08-20 15:45:03
// 引入Symfony依赖
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ThemeSwitcherController 控制器类，用于处理主题切换请求。
 */
class ThemeSwitcherController
{
    // 注入会话服务
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/switch-theme", name="switch_theme")
     * 处理主题切换请求的方法。
     * @param Request $request HTTP请求对象。
     * @return Response HTTP响应对象。
     */
    public function switchTheme(Request $request): Response
    {
        try {
            // 从请求中获取主题名称
            $theme = $request->query->get('theme', 'default');
            
            // 检查主题是否存在
            $availableThemes = ['default', 'dark', 'light'];
            if (!in_array($theme, $availableThemes)) {
                throw new \Exception('Requested theme is not available.');
            }

            // 将主题名称存储在会话中
            $this->session->set('theme', $theme);
            
            // 返回成功响应
            return new Response('Theme switched to ' . $theme);
        } catch (\Exception $e) {
            // 错误处理
            return new Response('Error: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
