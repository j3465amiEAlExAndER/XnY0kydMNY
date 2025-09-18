<?php
// 代码生成时间: 2025-09-19 01:38:36
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * MathToolService 类提供了一组数学计算工具。
 * 这个服务类包含了几个基本的数学操作，如加、减、乘、除。
 * 它遵循了PHP的最佳实践，包括清晰的代码结构和适当的错误处理。
 */
class MathToolService
{

    /**
     * 对两个数字执行加法操作。
     *
     * @param float $a 第一个数字
     * @param float $b 第二个数字
     * @return float 加法结果
     */
    public function add($a, $b)
    {
        return $a + $b;
    }

    /**
     * 对两个数字执行减法操作。
     *
     * @param float $a 第一个数字
     * @param float $b 第二个数字
     * @return float 减法结果
     */
    public function subtract($a, $b)
    {
        return $a - $b;
    }

    /**
     * 对两个数字执行乘法操作。
     *
     * @param float $a 第一个数字
     * @param float $b 第二个数字
     * @return float 乘法结果
     */
    public function multiply($a, $b)
    {
        return $a * $b;
    }

    /**
     * 对两个数字执行除法操作。
     *
     * @param float $a 被除数
     * @param float $b 除数
     * @return float 除法结果
     * @throws InvalidArgumentException 如果除数为0
     */
    public function divide($a, $b)
    {
        if ($b == 0) {
            throw new InvalidArgumentException("除数不能为0");
        }

        return $a / $b;
    }
}

/**
 * MathToolController 类处理HTTP请求，并提供数学计算工具的操作。
 * 这个控制器类使用了Symfony框架的注解来定义路由。
 */
class MathToolController
{

    private $mathToolService;

    public function __construct(MathToolService $mathToolService)
    {
        $this->mathToolService = $mathToolService;
    }

    /**
     * 添加两个数字
     *
     * @Route("/add", name="add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $a = $request->query->get('a');
        $b = $request->query->get('b');
        $result = $this->mathToolService->add($a, $b);
        return new Response("{"result": "$result"}
");
    }

    /**
     * 减去两个数字
     *
     * @Route("/subtract", name="subtract")
     * @param Request $request
     * @return Response
     */
    public function subtract(Request $request): Response
    {
        $a = $request->query->get('a');
        $b = $request->query->get('b');
        $result = $this->mathToolService->subtract($a, $b);
        return new Response("{"result": "$result"}
");
    }

    /**
     * 乘以两个数字
     *
     * @Route("/multiply", name="multiply")
     * @param Request $request
     * @return Response
     */
    public function multiply(Request $request): Response
    {
        $a = $request->query->get('a');
        $b = $request->query->get('b');
        $result = $this->mathToolService->multiply($a, $b);
        return new Response("{"result": "$result"}
");
    }

    /**
     * 除以两个数字
     *
     * @Route("/divide", name="divide")
     * @param Request $request
     * @return Response
     */
    public function divide(Request $request): Response
    {
        $a = $request->query->get('a');
        $b = $request->query->get('b');
        try {
            $result = $this->mathToolService->divide($a, $b);
            return new Response("{"result": "$result"}
");
        } catch (InvalidArgumentException $e) {
            return new Response("{"error": "{$e->getMessage()}"}
", Response::HTTP_BAD_REQUEST);
        }
    }
}
