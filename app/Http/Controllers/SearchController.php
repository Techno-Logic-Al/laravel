<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle global navbar search for companies and employees.
     */
    public function __invoke(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 3) {
            return response()->json(['results' => []]);
        }

        $perTypeLimit = 5;

        $companies = Company::query()
            ->where('name', 'like', '%' . $query . '%')
            ->orderBy('name')
            ->limit($perTypeLimit)
            ->get(['id', 'name']);

        $employees = Employee::query()
            ->with('company:id,name')
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', '%' . $query . '%')
                    ->orWhere('last_name', 'like', '%' . $query . '%');
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit($perTypeLimit)
            ->get(['id', 'first_name', 'last_name', 'company_id']);

        $results = [];

        foreach ($companies as $company) {
            $results[] = [
                'type'  => 'Company',
                'label' => $company->name,
                'url'   => route('companies.show', $company),
            ];
        }

        foreach ($employees as $employee) {
            $results[] = [
                'type'     => 'Employee',
                'label'    => $employee->first_name . ' ' . $employee->last_name,
                'subtitle' => optional($employee->company)->name,
                'url'      => route('employees.show', $employee),
            ];
        }

        return response()->json(['results' => $results]);
    }
}

