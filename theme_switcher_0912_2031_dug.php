<?php
// 代码生成时间: 2025-09-12 20:31:47
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ThemeSwitcherController extends AbstractController
{
    /**
     * @Route("/theme/{theme}", name="theme_switch")
     */
    public function switchTheme(Request $request, SessionInterface $session, string $theme): Response
    {
        // Check if the theme is valid
        $validThemes = ['dark', 'light'];
        if (!in_array($theme, $validThemes)) {
            throw $this->createNotFoundException('Invalid theme provided.');
        }

        // Set the theme in the session
        $session->set('theme', $theme);

        // Redirect to the previous page
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ? $referer : '/');
    }
}
