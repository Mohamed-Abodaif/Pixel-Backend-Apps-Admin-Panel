<?php

namespace App\CustomLibs\APIResponder;

use Illuminate\Http\JsonResponse;

class APIResponder
{
    public function JsonResponseHandler(array $messages , $status , $statusCode , array $dataToSend  = []) : JsonResponse
    {
        if(!empty($dataToSend)){ $dataToResponding["data"] = $dataToSend;}
        $dataToResponding["messages"] = $messages ;
        $dataToResponding["status"] = $status;
        return response()->json( $dataToResponding, $statusCode);
    }

}
