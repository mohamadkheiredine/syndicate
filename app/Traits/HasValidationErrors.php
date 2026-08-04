<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait HasValidationErrors
{
    public function getBaseErrorStructure($status, $message): array
    {
        $error['error'] = [];
        $error['error']['debugger'] = 'Invalid parameters';
        $error['error']['code'] = $status;
        $error['error']['message'] = $message;

        return $error;
    }

    /**
     * Return Error Error Form APIs
     *
     */
    protected function getValidationError($message): \Illuminate\Http\JsonResponse
    {
        $error = $this->getBaseErrorStructure(Response::HTTP_UNPROCESSABLE_ENTITY, $message);

        return response()->json($error)->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->getValidationError($validator->messages()->all()[0]);

        throw new HttpResponseException($response);
    }
}
