<?php namespace App\Http\Traits;


trait ApiHelpers {

    protected $statusCode = 200;

    protected $errCode = 0;

    protected $message = '';

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = '找不到资源')
    {
        return $this->setStatusCode(404)->setErrCode(404)->setMessage($message)->respondWithError();
    }

    public function respondLogicError($errCode = 400, $message = '业务错误')
    {
        return $this->setStatusCode(400)->setErrCode($errCode)->setMessage($message)->respondWithError();
    }

    public function respondForbidden($message = '没有权限')
    {
        return $this->setStatusCode(403)->setErrCode(403)->setMessage($message)->respondWithError();
    }

    /**
     * @param $data
     * @param array $header
     * @return mixed
     */
    public function respond($data = [], $header = [])
    {
        $opt = ['errCode' => $this->getErrCode(), 'message' => $this->getMessage()];

        return response()->json(array_merge($opt, $data), $this->getStatusCode(), $header);
    }

    public function respondCreated($data = null)
    {
        return $this->setStatusCode(201)->respond(['data' => $data]);
    }

    public function respondData($data)
    {
        return $this->setStatusCode(200)->respond(['data' => $data]);
    }

    public function respondOk($message = 'success')
    {
        return $this->setStatusCode(200)->setMessage($message)->respond();
    }


    /**
     * @param $message
     * @return mixed
     */
    public function respondWithError()
    {
        return response()->json(array(
            'errors'  => $this->getErrCode(),
            'message' => $this->getMessage()

        ), $this->getStatusCode());
    }

    /**
     * @return int
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * @param int $errCode
     */
    public function setErrCode($errCode)
    {
        $this->errCode = $errCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }


}
