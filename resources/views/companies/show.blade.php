@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $currentSort = request('sort');
        $currentDirection = request('direction', 'asc');
        $nextDirection = function (string $column) use ($currentSort, $currentDirection): string {
            return $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
        };
        $arrowFor = function (string $column) use ($currentSort, $currentDirection): string {
            if ($currentSort !== $column) {
                return '';
            }
            return $currentDirection === 'asc' ? 'ƒ-ı' : 'ƒ-¬';
        };
        $routeParams = ['company' => $company->id];
    @endphp
    @php
        // Override sort arrow symbols with clear up/down triangles
        $arrowFor = function (string $column) use ($currentSort, $currentDirection): string {
            if ($currentSort !== $column) {
                return '';
            }
            return $currentDirection === 'asc' ? '▲' : '▼';
        };
    @endphp

    @if (session('error'))
        <div class="row mb-3" data-auto-dismiss-container>
            <div class="col-md-12">
                <div class="alert alert-danger alert-auto-dismiss mb-0 text-center" data-auto-dismiss="6500">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="fs-3">{{ $company->name }}</span>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-4">
                            @if ($company->logo)
                                <img src="{{ asset('storage/'.$company->logo) }}"
                                     alt="{{ $company->name }} logo"
                                     class="company-logo-img"
                                     style="width:100px;height:100px;object-fit:cover;">
                            @else
                                <img src="{{ asset('images/company-placeholder.png') }}"
                                     alt="Placeholder logo for {{ $company->name }}"
                                     class="company-logo-img"
                                     style="width:100px;height:100px;object-fit:cover;">
                            @endif
                        </div>
                        <div class="text-white">
                            <div class="mb-1">
                                Email:
                                @if ($company->email)
                                    <a href="mailto:{{ $company->email }}" class="text-white text-decoration-none company-contact-link">
                                        {{ $company->email }}
                                    </a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </div>
                            <div class="mb-1">
                                Website:
                                @if ($company->website)
                                    <a href="{{ $company->website }}" target="_blank" class="text-white text-decoration-none company-contact-link">
                                        {{ $company->website }}
                                    </a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </div>
                            <div class="mb-1">
                                Number of Employees: {{ $company->employees_count }}
                            </div>
                            <div class="mt-2 small text-white">
                                Created on {{ optional($company->created_at)->format('d M Y') }}<br>
                                Last updated on {{ optional($company->updated_at)->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start flex-wrap gap-3 mt-3">
                        <a href="{{ route('companies.edit', $company) }}" class="btn btn-primary">Edit Company</a>
                        <form action="{{ route('companies.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this company?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Company</button>
                        </form>
                        <a href="{{ route('employees.create', ['company_id' => $company->id]) }}" class="btn btn-primary">
                            Add Employee
                        </a>
                        <a href="{{ route('companies.index') }}" class="btn btn-secondary">BACK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    @if ($employees->count())
                        <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width:90px">
                                        <a href="{{ route('companies.show', array_merge($routeParams, request()->query(), ['sort' => 'id', 'direction' => $nextDirection('id')])) }}"
                                           class="table-sort-link">
                                            Employee&nbsp;ID
                                            @if ($arrowFor('id'))
                                                <span>{{ $arrowFor('id') }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th style="width:60px"></th>
                                    <th>
                                        <a href="{{ route('companies.show', array_merge($routeParams, request()->query(), ['sort' => 'first_name', 'direction' => $nextDirection('first_name')])) }}"
                                           class="table-sort-link">
                                            First Name
                                            @if ($arrowFor('first_name'))
                                                <span>{{ $arrowFor('first_name') }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('companies.show', array_merge($routeParams, request()->query(), ['sort' => 'last_name', 'direction' => $nextDirection('last_name')])) }}"
                                           class="table-sort-link">
                                            Last Name
                                            @if ($arrowFor('last_name'))
                                                <span>{{ $arrowFor('last_name') }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('companies.show', array_merge($routeParams, request()->query(), ['sort' => 'gender', 'direction' => $nextDirection('gender')])) }}"
                                           class="table-sort-link">
                                            Gender
                                            @if ($arrowFor('gender'))
                                                <span>{{ $arrowFor('gender') }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('companies.show', array_merge($routeParams, request()->query(), ['sort' => 'email', 'direction' => $nextDirection('email')])) }}"
                                           class="table-sort-link">
                                            Email
                                            @if ($arrowFor('email'))
                                                <span>{{ $arrowFor('email') }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('companies.show', array_merge($routeParams, request()->query(), ['sort' => 'phone', 'direction' => $nextDirection('phone')])) }}"
                                           class="table-sort-link">
                                            Phone
                                            @if ($arrowFor('phone'))
                                                <span>{{ $arrowFor('phone') }}</span>
                                            @endif
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}" class="d-block text-reset text-decoration-none">
                                                {{ $employee->id }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}" class="d-block">
                                                @if ($employee->avatar)
                                                    <img src="{{ asset('storage/'.$employee->avatar) }}"
                                                         alt="{{ $employee->first_name }} {{ $employee->last_name }} avatar"
                                                         class="rounded-circle employee-avatar-img"
                                                         style="width:40px;height:40px;object-fit:cover;">
                                                @else
                                                    <img src="{{ asset('images/employee-placeholder.jpg') }}"
                                                         alt="Employee avatar placeholder"
                                                         class="rounded-circle employee-placeholder-img employee-avatar-img"
                                                         style="width:40px;height:40px;object-fit:cover;">
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}" class="d-block text-reset text-decoration-none">
                                                {{ $employee->first_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}" class="d-block text-reset text-decoration-none">
                                                {{ $employee->last_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}" class="d-block text-reset text-decoration-none">
                                                {{ $employee->gender }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}" class="d-block text-reset text-decoration-none">
                                                {{ $employee->email }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}" class="d-block text-reset text-decoration-none">
                                                {{ $employee->phone }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div class="mt-3 px-3 pb-3" id="pagination">
                            {{ $employees->links() }}
                        </div>
                    @else
                        <div class="p-3">
                            <p class="mb-0 text-muted">No employees have been added for this company yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
