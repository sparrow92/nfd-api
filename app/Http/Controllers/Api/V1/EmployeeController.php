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
   * Lista pracowników
   * 
   * @param \Illuminate\Http\Request $request
   * @return \App\Http\Resources\Api\V1\Employee\EmployeeResource
   */
  public function index(Request $request)
  {
    $perPage = $request->input('per_page');
    $employees = Employee::latest()->paginate($perPage);

    return EmployeeResource::collection($employees);
  }

  /**
   * Lista pracowników po firmie
   * 
   * @param \Illuminate\Http\Request $request
   * @param int $companyId
   * @return \App\Http\Resources\Api\V1\Employee\EmployeeResource
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
   * Dodawanie pracownika
   * 
   * @param int $companyId
   * @param \App\Http\Requests\Api\V1\EmployeeRequest $request
   * @return \App\Http\Resources\Api\V1\Employee\EmployeeResource
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
   * Wyświetlanie pracownika
   * 
   * @param int $id
   * @return \App\Http\Resources\Api\V1\Employee\EmployeeResource
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
   * Aktualizacja pracownika
   * 
   * @param \App\Http\Requests\Api\V1\EmployeeRequest $request
   * @param int $id
   * @return \App\Http\Resources\Api\V1\Employee\EmployeeResource
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
   * Usuwanie pracownika
   * 
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
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
