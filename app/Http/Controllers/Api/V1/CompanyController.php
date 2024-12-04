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
   * @OA\Get(
   *    path="/api/v1/companies",
   *    tags={"Companies"},
   *    description="Pobieranie listy firm",
   * 
   *    @OA\Parameter(name="per_page", in="query", description="Wyników na stronie", required=false, @OA\Schema(type="integer")),
   *    @OA\Parameter(name="lang", in="query", description="Język", required=false, @OA\Schema(type="string", enum={"pl", "en", "es"})),
   * 
   *    @OA\Response(response=200, description="Pomyślnie pobrano listę firm"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas pobierania listy firm"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function index(Request $request)
  {
    $perPage = $request->input('per_page');
    $companies = Company::latest()->paginate($perPage);

    return CompanyResource::collection($companies);
  }

  /**
   * @OA\Post(
   *    path="/api/v1/companies",
   *    tags={"Companies"},
   *    description="Tworzenie nowej firmy",
   * 
   *    @OA\Parameter(name="lang", in="query", description="Język", required=false, @OA\Schema(type="string", enum={"pl", "en", "es"})),
   * 
   *    @OA\RequestBody(
   *      required=true,
   *      @OA\JsonContent(
   *        required={"name", "nip", "address", "city", "postcode"},
   *        @OA\Property(property="name", type="string", example="No Friday Deploys"),
   *        @OA\Property(property="nip", type="string", example="98765432109"),
   *        @OA\Property(property="address", type="string", example="Kościuszki 12"),
   *        @OA\Property(property="city", type="string", example="Kraków"),
   *        @OA\Property(property="postcode", type="string", example="30-055"),
   *      )
   *    ),
   * 
   *    @OA\Response(response=200, description="Pomyślnie pobrano listę firm"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas pobierania listy firm"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function store(CompanyRequest $request)
  {
    try {
      $company = Company::create($request->all());
    } catch (\Exception $e) {
      return response()->json([
        'message' => __('company.store_error'),
      ], 400);
    }

    return response()->json(new CompanyResource($company));
  }

  /**
   * @OA\Get(
   *    path="/api/v1/companies/{id}",
   *    tags={"Companies"},
   *    description="Pobieranie danych firmy o podanym ID",
   * 
   *    @OA\Parameter(name="id", in="path", description="UUID firmy", required=true, @OA\Schema(type="string")),
   *    @OA\Parameter(name="lang", in="query", description="Język", required=false, @OA\Schema(type="string", enum={"pl", "en", "es"})),
   * 
   *    @OA\Response(response=200, description="Pomyślnie pobrano dane firmy"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas pobierania danych firmy"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function show($id)
  {
    $company = Company::find($id);

    if (!$company) {
      return response()->json([
        'message' => __('company.not_found'),
      ], 404);
    }

    return response()->json(new CompanyResource($company));
  }

  /**
   * @OA\Put(
   *    path="/api/v1/companies/{id}",
   *    tags={"Companies"},
   *    description="Aktualizacja danych firmy o podanym ID",
   * 
   *    @OA\Parameter(name="id", in="path", description="UUID firmy", required=true, @OA\Schema(type="string")),
   *    @OA\Parameter(name="lang", in="query", description="Język", required=false, @OA\Schema(type="string", enum={"pl", "en", "es"})),
   * 
   *    @OA\RequestBody(
   *      required=true,
   *      @OA\JsonContent(
   *        required={"name", "nip", "address", "city", "postcode"},
   *        @OA\Property(property="name", type="string", example="No Friday Deploys"),
   *        @OA\Property(property="nip", type="string", example="98765432109"),
   *        @OA\Property(property="address", type="string", example="Kościuszki 12"),
   *        @OA\Property(property="city", type="string", example="Kraków"),
   *        @OA\Property(property="postcode", type="string", example="30-055"),
   *      )
   *    ),
   * 
   *    @OA\Response(response=200, description="Pomyślnie zaktualizowano dane firmy"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas aktualizacji danych firmy"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function update(CompanyRequest $request, $id)
  {
    $company = Company::find($id);

    if (!$company) {
      return response()->json([
        'message' => __('company.not_found'),
      ], 404);
    }
    
    try {
      $company->update($request->all());
    } catch (\Exception $e) {
      return response()->json([
        'message' => __('company.update_error'),
      ], 400);
    }

    return response()->json(new CompanyResource($company));
  }

  /**
   * @OA\Delete(
   *    path="/api/v1/companies/{id}",
   *    tags={"Companies"},
   *    description="Usuwanie firmy o podanym ID",
   * 
   *    @OA\Parameter(name="id", in="path", description="UUID firmy", required=true, @OA\Schema(type="string")),
   *    @OA\Parameter(name="lang", in="query", description="Język", required=false, @OA\Schema(type="string", enum={"pl", "en", "es"})),
   * 
   *    @OA\Response(response=200, description="Pomyślnie usunięto firmę"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas usuwania firmy"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function destroy($id)
  {
    $company = Company::find($id);

    if (!$company) {
      return response()->json([
        'message' => __('company.not_found'),
      ], 404);
    }

    try {
      $company->delete();
    } catch (\Exception $e) {
      return response()->json([
        'message' => __('company.delete_error'),
      ], 400);
    }

    return response()->json([
      'message' => __('company.deleted'),
    ], 200);
  }
}
