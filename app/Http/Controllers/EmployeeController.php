<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sort = request('sort');
        $direction = strtolower((string) request('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        $query = Employee::query()->with('company');

        if ($sort === 'company') {
            $query->leftJoin('companies', 'employees.company_id', '=', 'companies.id')
                ->select('employees.*')
                ->orderBy('companies.name', $direction);
        } elseif (in_array($sort, ['first_name', 'last_name', 'gender', 'email', 'updated_at'], true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('last_name')->orderBy('first_name');
        }

        $employees = $query->paginate(10)->withQueryString()->fragment('pagination');

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();

        $selectedCompanyId = request('company_id');

        return view('employees.create', compact('companies', 'selectedCompanyId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('employee-avatars', 'public');
        }

        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('status', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load('company');

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $companies = Company::orderBy('name')->get();

        return view('employees.edit', compact('employee', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('employee-avatars', 'public');
        }

        $employee->update($data);

        return redirect()->route('employees.index')
            ->with('status', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('status', 'Employee deleted successfully.');
    }
}
