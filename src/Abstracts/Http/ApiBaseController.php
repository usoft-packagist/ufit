<?php

namespace Usoft\Ufit\Abstracts\Http;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Usoft\Ufit\Interfaces\ApiController;

abstract class ApiBaseController implements ApiController
{
    public function created($resource, $item){
        return $this->singleItem($resource, $item, 201);
    }

    public function accepted($resource, $item){
        return $this->singleItem($resource, $item, 202);
    }

    public function singleItem($resource, $item, $status_code = 200){
        return response()->json([
            "result" => new $resource($item)
        ], $status_code);
    }

    public function paginated($resource, $items, $status_code = 200)
    {
        return response()->json([
            'pagination' => [
                'current' => $items->currentPage(),
                'previous' => $items->currentPage() > 1 ? $items->currentPage() - 1 : 0,
                'next' => $items->hasMorePages() ? $items->currentPage() + 1 : 0,
                'perPage' => $items->perPage(),
                'totalPage' => $items->lastPage(),
                'totalItem' => $items->total(),
            ],
            'result' => $resource::collection($items->items())
        ], $status_code);
    }

    public function noContent(){
        return abort(204);
    }

    public function error($message, $status_code=400, $exception=null){
        if($exception){
            Log::error('MESSAGE: '.$message.' ERROR: '.$exception->getMessage().' TRACE: '.$exception->getTraceAsString());
        }else{
            Log::error('MESSAGE: '.$message);
        }
        if (Lang::has('ufit_translations::errors.'.$message)){
            return response()->json([
                'message'   => $this->translate('errors.'.$message),
            ], $status_code);
        }
        return response()->json([
            'message'   =>$message,
        ], $status_code);
    }

    public function errorNotFound($message = 'Not Found'){
        return $this->error($message, 404);
    }

    public function errorBadRequest($message = 'Bad Request'){
        return $this->error($message, 400);
    }

    public function errorForbidden($message = 'Forbidden'){
        return $this->error($message, 403);
    }

    public function errorUnauthorized($message = 'Unauthorized'){
        return $this->error($message, 401);
    }

    public function translate(String $key)
    {
        return trans('ufit_translations::'.$key);
    }
}
