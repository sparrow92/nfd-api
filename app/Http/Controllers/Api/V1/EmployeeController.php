<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EmployeeRequest;
use App\Models\Employee;
use App\Models\Company;
use App\Http\Resources\Api\V1\Employee\EmployeeResource;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
  /**
   * @OA\Get(
   *    path="/api/v1/employees",
   *    tags={"Employees"},
   *    description="Pobieranie listy wszystkich pracowników",
   * 
   *    @OA\Parameter(name="per_page", in="query", description="Wyników na stronie", required=false, @OA\Schema(type="integer")),
   * 
   *    @OA\Response(response=200, description="Pomyślnie pobrano listę wszystkich pracowników"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas pobierania listy wszystkich pracowników"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function index(Request $request)
  {
    $perPage = $request->input('per_page');
    $employees = Employee::latest()->paginate($perPage);

    return EmployeeResource::collection($employees);
  }

  /**
   * @OA\Get(
   *    path="/api/v1/companies/{companyId}/employees",
   *    tags={"Employees"},
   *    description="Pobieranie listy pracowników danej firmy",
   * 
   *    @OA\Parameter(name="companyId", in="path", description="UUID firmy", required=true, @OA\Schema(type="string")),
   *    @OA\Parameter(name="per_page", in="query", description="Wyników na stronie", required=false, @OA\Schema(type="integer")),
   * 
   *    @OA\Response(response=200, description="Pomyślnie pobrano listę pracowników firmy"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas pobierania listy pracowników firmy"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function indexByCompany(Request $request, $companyId)
  {
    $company = Company::find($companyId);

    if (!$company) {
      return response()->json([
        'message' => 'Firma o podanym ID nie istnieje.',
      ], 404);
    }

    $perPage = $request->input('per_page');
    $employees = Employee::where('company_id', $company->id)->latest()->paginate($perPage);

    return EmployeeResource::collection($employees);
  }

  /**
   * @OA\Post(
   *    path="/api/v1/companies/{companyId}/employees",
   *    tags={"Employees"},
   *    description="Tworzenie nowego pracownika w firmie",
   * 
   *    @OA\Parameter(name="companyId", in="path", description="UUID firmy", required=true, @OA\Schema(type="string")),
   * 
   *    @OA\RequestBody(
   *      required=true,
   *      @OA\JsonContent(
   *        required={"first_name", "last_name", "email"},
   *        @OA\Property(property="first_name", type="string", example="Jan"),
   *        @OA\Property(property="last_name", type="string", example="Kowalski"),
   *        @OA\Property(property="email", type="string", example="jan@kowalski.pl"),
   *        @OA\Property(property="phone_number", type="string", example="123456789"),
   *      )
   *    ),
   * 
   *    @OA\Response(response=200, description="Pomyślnie utworzono pracownika w firmie"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas tworzenia pracownika w firmie"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function store(EmployeeRequest $request, $companyId)
  {
    $company = Company::find($companyId);

    if (!$company) {
      return response()->json([
        'message' => 'Firma o podanym ID nie istnieje.',
      ], 404);
    }

    try {
      $employee = Employee::create(array_merge($request->all(), ['company_id' => $company->id]));
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Wystąpił błąd podczas dodawania pracownika.',
      ], 400);
    }

    return response()->json(new EmployeeResource($employee));
  }

  /**
   * @OA\Get(
   *    path="/api/v1/employees/{id}",
   *    tags={"Employees"},
   *    description="Pobieranie danych pracownika",
   * 
   *    @OA\Parameter(name="id", in="path", description="UUID pracownika", required=true, @OA\Schema(type="string")),
   * 
   *    @OA\Response(response=200, description="Pomyślnie pobrano dane pracownika"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas pobierania danych pracownika"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function show($id)
  {
    $employee = Employee::with('company')->find($id);

    if (!$employee) {
      return response()->json([
        'message' => 'Pracownik o podanym ID nie istnieje.',
      ], 404);
    }

    return response()->json(new EmployeeResource($employee));
  }

  /**
   * @OA\Put(
   *    path="/api/v1/employees/{id}",
   *    tags={"Employees"},
   *    description="Aktualizacja danych pracownika",
   * 
   *    @OA\Parameter(name="id", in="path", description="UUID pracownika", required=true, @OA\Schema(type="string")),
   * 
   *    @OA\RequestBody(
   *      required=true,
   *      @OA\JsonContent(
   *        required={"first_name", "last_name", "email"},
   *        @OA\Property(property="first_name", type="string", example="Jan"),
   *        @OA\Property(property="last_name", type="string", example="Kowalski"),
   *        @OA\Property(property="email", type="string", example="jan@kowalski.pl"),
   *        @OA\Property(property="phone_number", type="string", example="123456789"),
   *      )
   *    ),
   * 
   *    @OA\Response(response=200, description="Pomyślnie zaktualizowano dane pracownika"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas aktualizowania danych pracownika"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function update(EmployeeRequest $request, $id)
  {
    $employee = Employee::find($id);

    if (!$employee) {
      return response()->json([
        'message' => 'Pracownik o podanym ID nie istnieje.',
      ], 404);
    }

    try {
      $employee->update($request->all());
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Wystąpił błąd podczas aktualizacji pracownika.',
      ], 400);
    }

    return response()->json(new EmployeeResource($employee));
  }

  /**
   * @OA\Delete(
   *    path="/api/v1/employees/{id}",
   *    tags={"Employees"},
   *    description="Usuwanie pracownika",
   * 
   *    @OA\Parameter(name="id", in="path", description="UUID pracownika", required=true, @OA\Schema(type="string")),
   * 
   *    @OA\Response(response=200, description="Pomyślnie usunięto pracownika"),
   *    @OA\Response(response=400, description="Wystąpił błąd podczas usuwania pracownika"),
   *    @OA\Response(response=401, description="Brak autoryzacji"),
   *    @OA\Response(response=422, description="Błąd walidacji"),
   *    security={{"api_key":{}}}
   * )
   */
  public function destroy($id)
  {
    $employee = Employee::find($id);
    if (!$employee) {
      return response()->json([
        'message' => 'Pracownik o podanym ID nie istnieje.',
      ], 404);
    }

    try {
      $employee->delete();
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Wystąpił błąd podczas usuwania pracownika.',
      ], 400);
    }

    return response()->json([
      'message' => 'Pracownik został usunięty.',
    ], 200);
  }
}
