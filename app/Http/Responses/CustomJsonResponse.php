<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class CustomJsonResponse implements Responsable
{
    protected $status;
    protected $code;
    protected $message;
    protected $data;
    protected $errors;

    /**
     * Constructor for the custom response.
     *
     * @param  string  $status
     * @param  int     $code
     * @param  string  $message
     * @param  mixed   $data
     * @param  mixed   $errors
     */
    public function __construct(string $status, int $code, string $message, $data = null, $errors = null)
    {
        $this->status = $status;
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
        $this->errors = $errors;
    }

    /**
     * Create the HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return response()->json(
            array_filter([
                'status' => $this->status,
                'code' => $this->code,
                'message' => $this->message,
                'data' => $this->data,
                'errors' => $this->errors,
            ], fn($value) => !is_null($value)),
            $this->code
        );
    }

    /**
     * Create a success response.
     *
     * @param  string  $message
     * @param  mixed   $data
     * @param  int     $code
     * @return CustomJsonResponse
     */

    public static function success(string $message, $data = null, int $code = 200): CustomJsonResponse
    {
        return new static('success', $code, $message, $data);
    }

    /**
     * Create a validation error response.
     *
     * @param  string  $message
     * @param  mixed   $errors
     * @param  int     $code
     * @return CustomJsonResponse
     */

    public static function validationError($errors, string $message = 'Validation Error',  int $code = 400): CustomJsonResponse
    {
        return new static('fail', $code, $message, null, $errors);
    }
}
