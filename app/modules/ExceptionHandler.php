<?php
defined('RUN') or http_response_code(404) and die();

class ExceptionHandler
{
    public function handle(Request $request, Throwable $th)
    {
        if ($request->getLocal('expectJson')) {
            return $this->handleApiRequest($request, $th);
        }

        switch (get_class($th)) {
            case AuthenticationException::class:
                session()->flash('error', 'Please login to access the page: ' . $request->url());
                return redirect(url('login'));
            case PageNotFoundException::class:
                return $this->renderPage(404, 'Page not found', $th->getMessage());
            case MethodNotAllowedException::class:
                return $this->renderPage(405, 'Method not allowed', $th->getMessage());
            default:
                return $this->renderPage(500, 'Internal server error', $th->getMessage());
        }
    }

    private function handleApiRequest(Request $request, Throwable $th)
    {
        header('Content-Type: application/json');
        switch (get_class($th)) {
            case AuthenticationException::class:
                http_response_code(401);
                echo json_encode([
                    'message' => 'Please login to continue',
                    'data' => [],
                ]);
                break;
            case PageNotFoundException::class:
                http_response_code(404);
                echo json_encode([
                    'message' => $th->getMessage(),
                    'data' => [],
                ]);
                break;
            case MethodNotAllowedException::class:
                http_response_code(405);
                echo json_encode([
                    'message' => 'Method not allowed',
                    'data' => [],
                ]);
                break;
            default:
                http_response_code(500);
                echo json_encode([
                    'message' => 'Internal server error',
                    'data' => [],
                ]);
        }
    }

    private function renderPage(int $statusCode, string $title, string $message): void
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        http_response_code($statusCode);
        page_start('main');
        echo '<div class="container py-5 text-center">'
            . '<h1 class="display-1">' . $statusCode . '</h1>'
            . '<p class="lead">' . htmlspecialchars($title, ENT_QUOTES) . '</p>'
            . '<p class="text-muted">' . htmlspecialchars($message, ENT_QUOTES) . '</p>'
            . '<a href="' . url() . '" class="btn btn-primary">Back to home</a>'
            . '</div>';
        page_end();
    }
}
