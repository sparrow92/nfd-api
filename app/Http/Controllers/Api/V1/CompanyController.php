<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CompanyRequest;
use App\Models\Company;
use App\Http\Resources\Api\V1\Company\CompanyResource;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
  /**
   * Lista firm
   * 
   * @param \Illuminate\Http\Request $request
   * @return \App\Http\Resources\Api\V1\Company\CompanyResource
   */
  public function index(Request $request)
  {
    $perPage = $request->input('per_page');
    $companies = Company::paginate($perPage);

    return CompanyResource::collection($companies);
  }

  /**
   * Dodawanie firmy
   * 
   * @param \App\Http\Requests\Api\V1\CompanyRequest $request
   * @return \App\Http\Resources\Api\V1\Company\CompanyResource
   */
  public function store(CompanyRequest $request)
  {
    try {
      $company = Company::create($request->all());
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Wystąpił błąd podczas dodawania firmy.',
      ], 400);
    }

    return response()->json(new CompanyResource($company));
  }

  /**
   * Wyświetlanie firmy
   * 
   * @param int $id
   * @return \App\Http\Resources\Api\V1\Company\CompanyResource
   */
  public function show($id)
  {
    $company = Company::find($id);

    if (!$company) {
      return response()->json([
        'message' => 'Firma o podanym ID nie istnieje.',
      ], 404);
    }

    return response()->json(new CompanyResource($company));
  }

  /**
   * Aktualizacja firmy
   * 
   * @param \App\Http\Requests\Api\V1\CompanyRequest $request
   * @param int $id
   * @return \App\Http\Resources\Api\V1\Company\CompanyResource
   */
  public function update(CompanyRequest $request, $id)
  {
    $company = Company::find($id);

    if (!$company) {
      return response()->json([
        'message' => 'Firma o podanym ID nie istnieje.',
      ], 404);
    }
    
    try {
      $company->update($request->all());
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Wystąpił błąd podczas aktualizacji firmy.',
      ], 400);
    }

    return response()->json(new CompanyResource($company));
  }

  /**
   * Usuwanie firmy
   * 
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy($id)
  {
    $company = Company::find($id);

    if (!$company) {
      return response()->json([
        'message' => 'Firma o podanym ID nie istnieje.',
      ], 404);
    }

    try {
      $company->delete();
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Wystąpił błąd podczas usuwania firmy.',
      ], 400);
    }

    return response()->json([
      'message' => 'Firma została usunięta pomyślnie.',
    ], 200);
  }
}
