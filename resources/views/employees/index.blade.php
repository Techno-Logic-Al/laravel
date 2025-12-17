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
            return $currentDirection === 'asc' ? '▲' : '▼';
        };
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
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Employees</span>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">Add Employee</a>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-white">Track your team members, their companies, and contact information in one place.</p>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="alert alert-success mb-0 text-center">
                    {{ session('status') }}
                </div>
            </div>
        </div>
    @endif

    @if ($employees->count())
        <div class="card">
            <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:60px"></th>
                    <th>
                        <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'first_name', 'direction' => $nextDirection('first_name')])) }}"
                           class="table-sort-link">
                            First Name
                            @if ($arrowFor('first_name'))
                                <span>{{ $arrowFor('first_name') }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'last_name', 'direction' => $nextDirection('last_name')])) }}"
                           class="table-sort-link">
                            Last Name
                            @if ($arrowFor('last_name'))
                                <span>{{ $arrowFor('last_name') }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'gender', 'direction' => $nextDirection('gender')])) }}"
                           class="table-sort-link">
                            Gender
                            @if ($arrowFor('gender'))
                                <span>{{ $arrowFor('gender') }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'company', 'direction' => $nextDirection('company')])) }}"
                           class="table-sort-link">
                            Company
                            @if ($arrowFor('company'))
                                <span>{{ $arrowFor('company') }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => $nextDirection('email')])) }}"
                           class="table-sort-link">
                            Email
                            @if ($arrowFor('email'))
                                <span>{{ $arrowFor('email') }}</span>
                            @endif
                        </a>
                    </th>
                    <th>Phone</th>
                    <th style="width:140px; white-space:nowrap">
                        <a href="{{ route('employees.index', array_merge(request()->query(), ['sort' => 'updated_at', 'direction' => $nextDirection('updated_at')])) }}"
                           class="table-sort-link">
                            Last Updated
                            @if ($arrowFor('updated_at'))
                                <span>{{ $arrowFor('updated_at') }}</span>
                            @endif
                        </a>
                    </th>
                    <th width="230">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
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
                                {{ optional($employee->company)->name }}
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
                        <td>
                            <a href="{{ route('employees.show', $employee) }}" class="d-block text-reset text-decoration-none">
                                {{ optional($employee->updated_at)->format('d M Y') }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this employee?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
            </div>
        </div>

        <div class="mt-4" id="pagination">
            {{ $employees->links() }}
        </div>
    @else
        <p>No employees found.</p>
    @endif
</div>
@endsection
