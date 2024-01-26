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
                session()->flash('error', 'Please login to access the page:' . $request->url());
                return redirect(url('login'));
            default:
                throw $th;
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
            case MethodNotAllowedException::class:
                http_response_code(405);
                echo json_encode([
                    'message' => 'Method not allowed',
                    'data' => [],
                ]);
                break;
            default:
                throw $th;
        }
    }
}
