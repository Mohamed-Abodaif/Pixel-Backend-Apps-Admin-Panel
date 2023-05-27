<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use  Illuminate\Support\MessageBag;
use stdClass;

class Helpers
{

    static public function getErrorsIndexedArray(MessageBag $bag) : array
    {
        $errorBagArray = $bag->toArray();
        $array = [];
        foreach ($errorBagArray as $messages)
        {
            foreach ($messages as $message)
            {
                $array[] = $message;
            }
        }
        return $array;
    }


    //JsonResponse Handling Methods - start
    static private function getResponseData(JsonResponse $response) : stdClass
    {
        return $response->getData();
    }
    static public function IsResponseStatusSuccess(JsonResponse $response) : bool
    {
        return static::getResponseData($response)->status == "success";
    }

    static public function getResponseStatus(JsonResponse $response) : string
    {
        return static::getResponseData($response)->status;
    }

    static public function getResponseMessages(JsonResponse $response) : array
    {
        return static::getResponseData($response)->messages;
    }
    //JsonResponse Handling Methods - end
}
