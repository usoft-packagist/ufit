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

    protected function paginateQuery($resource, $modelQuery, $status_code = 200){
        $limit = request()->limit??10;
        if(!(is_int($limit) || $limit > 0) || $limit > 100){
            $limit = 25;
        }
        $items = $modelQuery->paginate($limit);
        if (count($items)) {
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
        }else{
            return response()->json([
                'pagination' => [
                    'current' => 0,
                    'previous' => 0,
                    'next' => 0,
                    'perPage' => 0,
                    'totalPage' => 0,
                    'totalItem' => 0,
                ],
                'result' => []
            ], $status_code);
        }
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

    public function errorNotFound($message = 'Not Found', $exception=null){
        return $this->error($message, 404, $exception);
    }

    public function errorBadRequest($message = 'Bad Request', $exception=null){
        return $this->error($message, 400, $exception);
    }

    public function errorForbidden($message = 'Forbidden', $exception=null){
        return $this->error($message, 403, $exception);
    }

    public function errorUnauthorized($message = 'Unauthorized', $exception=null){
        return $this->error($message, 401, $exception);
    }

    public function translate(String $key)
    {
        return trans('ufit_translations::'.$key);
    }
}
