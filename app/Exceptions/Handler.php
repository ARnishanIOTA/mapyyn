<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->is('api/*')) {
            if($exception instanceof ValidationException){
                $errors = $exception->validator->errors()->getMessages();
                return $this->apiResponse((object)[], $errors, 422);
            }
    
            if($exception instanceof AuthenticationException){
                if(request('lang') == 'ar'){
                    return $this->apiResponse((object)[], ['errors' => 'غير موثوق'], 401);
                }else{
                    return $this->apiResponse((object)[], ['errors' => 'Unauthenticated'], 401);
                }
            }

            if($exception instanceof ModelNotFoundException){
                $modelName = strtolower(class_basename($exception->getModel()));
                if(request('lang') == 'ar'){
                    return $this->apiResponse((object)[], ['errors' => "موديل غير موجود"],404);
                }else{
                    return $this->apiResponse((object)[], ['errors' => "Does not exists any $modelName with spacified identified"],404);
                }
            }
    
    
            if($exception instanceof AuthorizationException){
                return $this->apiResponse((object)[], $exception->getMessage(), 403);
            }

            if($exception instanceof MethodNotAllowedHttpException){
                if(request('lang') == 'ar'){
                    return $this->apiResponse((object)[], ['errors' => "دالة غيرة موجودة"],404);
                }else{
                    return $this->apiResponse((object)[], ['errors' => "The spacific method for request not found"], 404);
                }
            }

            if($exception instanceof NotFoundHttpException){
                if(request('lang') == 'ar'){
                    return $this->apiResponse((object)[],['errors' => "رابط غير موجود"], 404);
                }else{
                    return $this->apiResponse((object)[],['errors' => "The spacific Url cannot be found"], 404);
                }
            }

            if($exception instanceof HttpException){
                if(request('lang') == 'ar'){
                    return $this->apiResponse((object)[], ['errors' => 'خطا في http'], $exception->getStatusCode());
                }else{
                    return $this->apiResponse((object)[], ['errors' => 'Http Exception'], $exception->getStatusCode());
                }
            }

            //return $this->apiResponse((object)[], 'Unexcepected Exception , try later', 500);
        }
        return parent::render($request, $exception);
    }



    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $guard = array_get($exception->guards(), 0);

        if ($request->is('api/*')) {
            if(request('lang') == 'ar'){
                return $thi->apiResponse((object)[], 'غير موثوق', 401);
            }else{
                return $thi->apiResponse((object)[], 'unauthorized', 401);
            }
        }


        switch ($guard) {
            case 'clients':
                if (!Auth::guard('clients')->check()) {
                    return redirect('/login');
                }
                break;

            case 'providers':
                if (!Auth::guard('providers')->check()) {
                    return redirect('/providers/login');
                }
                break;
            
            default:
                if (!Auth::guard($guard)->check()) {
                    return redirect('/backend/login');
                }
                break;
        }
    }



    /**
     * return response value
     * 
     * @param mixed $data
     * @param mixed $error
     * @param int $code
     * 
     * @return object
     */
    protected function apiResponse($data = null, $error = null, $code = 200)
    {
        $array = [
            'data'  => $data,
            'error' => $error,
            'code'  => $code
        ];

        return response()->json($array, 200);
    }
}
