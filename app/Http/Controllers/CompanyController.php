<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sort = request('sort');
        $direction = strtolower((string) request('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        $query = Company::query()->withCount('employees');

        if ($sort === 'employees') {
            $query->orderBy('employees_count', $direction);
        } elseif (in_array($sort, ['name', 'email', 'website', 'updated_at'], true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('name');
        }

        $companies = $query->paginate(10)->withQueryString()->fragment('pagination');

        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Company::create($data);

        return redirect()->route('companies.index')
            ->with('status', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company->loadCount('employees');

        $sort = request('sort');
        $direction = strtolower((string) request('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        $employeeQuery = $company->employees();

        if (in_array($sort, ['id', 'first_name', 'last_name', 'gender', 'email', 'phone'], true)) {
            $employeeQuery->orderBy($sort, $direction);
        } else {
            $employeeQuery->orderBy('last_name')->orderBy('first_name');
        }

        $employees = $employeeQuery->paginate(10)->withQueryString()->fragment('pagination');

        return view('companies.show', compact('company', 'employees'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);

        return redirect()->route('companies.index')
            ->with('status', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')
            ->with('status', 'Company deleted successfully.');
    }
}
