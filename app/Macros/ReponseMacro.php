<?php

use Illuminate\Support\Facades\Response;

Response::macro('success', function (array $data, array $messages = [], int $code = 200) {
    return $this->json([
        'status' => 'success',
        'messages' => $messages,
        'data' => $data
    ], $code);
});

Response::macro('error', function (array $messages = [] , int $code = 406) {
    return $this->json([
        'status' => 'error',
        'messages' => $messages,
        'data' => []
    ], $code);
});
