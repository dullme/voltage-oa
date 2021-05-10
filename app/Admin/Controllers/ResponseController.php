<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;

class ResponseController extends AdminController
{
    protected $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * 404
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseNotFound($message = '网络异常，请重试！') {

        return $this->setStatusCode(404)->responseError($message);
    }

    public function responseError($message) {

        return $this->response([
            'status' => false,
            'message' => $message
        ], $this->getStatusCode());
    }

    public function responseSuccess($data, $message = 'success') {

        return $this->response([
            'status' => true,
            'data' => $data,
            'message' => $message
        ],$this->getStatusCode());
    }

    public function responseValidateError($data)
    {
        return $this->response([
            'message' => 'The given data was invalid.',
            'errors' => $data,
        ],$this->getStatusCode());
    }

    public function response($data) {

        return response()->json($data, $this->getStatusCode());
    }

}
