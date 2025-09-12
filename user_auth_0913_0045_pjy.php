<?php
// 代码生成时间: 2025-09-13 00:45:18
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

// UserAuthController handles user authentication
class UserAuthController extends AbstractController
{
    private $passwordEncoder;
    private $security;
    private $authenticationUtils;

    public function __construct(PasswordEncoderInterface $passwordEncoder, Security $security, AuthenticationUtils $authenticationUtils)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request): Response
    {
        return $this->render('login.html.twig', [
            'last_username' => $this->authenticationUtils->getLastUsername(),
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        // Handle logout
    }

    /**
     * @Route("/check_credentials", name="check_credentials")
     */
    public function checkCredentials(Request $request): Response
    {
        $credentials = $request->request->all();
        $username = $credentials['username'];
        $password = $credentials['password'];

        try {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);
            if (!$user) {
                throw new AuthenticationException('User not found.');
            }

            if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
                throw new AuthenticationException('Invalid credentials.');
            }

            // Authentication successful, create token
            $token = new UsernamePasswordToken($user, $user->getRoles());
            $this->security->setTokenStorage($token);

            return $this->redirectToRoute('home');
        } catch (AuthenticationException $e) {
            // Handle error
            return new Response($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }
}
