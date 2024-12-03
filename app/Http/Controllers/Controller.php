<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[
  OA\Info(
    title: "NFD API",
    version: "1.0.0",
    description: "API do zarządzania firmami i pracownikami",
  ),
  OA\Server(
    url: "http://localhost:8000",
    description: "Local server",
  ),
  OA\SecurityScheme(
    securityScheme: "api_key",
    type: "apiKey",
    name: "x-api-key",
    in: "header",
  )
]
abstract class Controller
{
  //
}
