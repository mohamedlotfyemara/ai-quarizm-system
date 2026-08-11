@extends('layouts.app')
@section('content')

<div class="row mb-4 g-3">
    <div class="col-md-3"><div class="card p-3 text-center"><div class="text-muted small">بلاغات مفتوحة</div><div class="fs-3 fw-bold">{{ $stats['open'] }}</div></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="text-muted small">بلاغات مغلقة</div><div class="fs-3 fw-bold">{{ $stats['closed'] }}</div></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="text-muted small">إجمالي البلاغات</div><div class="fs-3 fw-bold">{{ $stats['total'] }}</div></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="text-muted small">حرجة مفتوحة</div><div class="fs-3 fw-bold text-danger">{{ $stats['critical'] }}</div></div></div>
</div>

<div class="card mb-4">
    <div class="card-header">كل البلاغات</div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr>
                <th>الكود</th><th>العنوان</th><th>العميل</th><th>الأولوية</th><th>الحالة</th><th>الفني</th><th></th>
            </tr></thead>
            <tbody>
            @foreach ($tickets as $t)
                <tr>
                    <td>{{ $t->code }}</td>
                    <td>{{ $t->title }}</td>
                    <td>{{ $t->customer->name ?? '-' }}</td>
                    <td>{{ $t->priority }}</td>
                    <td><span class="badge bg-secondary">{{ $t->status }}</span></td>
                    <td>{{ $t->technician->name ?? '-' }}</td>
                    <td><a href="{{ route('dashboard.tickets.show', $t) }}" class="btn btn-sm btn-outline-primary">عرض</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $tickets->links() }}</div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">إضافة عميل جديد</div>
            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.customers.store') }}">
                    @csrf
                    <div class="mb-2"><input name="name" class="form-control" placeholder="الاسم" required></div>
                    <div class="mb-2"><input name="email" type="email" class="form-control" placeholder="البريد الإلكتروني" required></div>
                    <div class="mb-2"><input name="password" type="text" class="form-control" placeholder="كلمة المرور" required></div>
                    <div class="mb-2"><input name="phone" class="form-control" placeholder="الجوال"></div>
                    <button class="btn btn-success w-100">إضافة وإرسال بيانات الدخول</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">إضافة موظف (منسق / فني / مدير)</div>
            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.staff.store') }}">
                    @csrf
                    <div class="mb-2"><input name="name" class="form-control" placeholder="الاسم" required></div>
                    <div class="mb-2"><input name="email" type="email" class="form-control" placeholder="البريد الإلكتروني" required></div>
                    <div class="mb-2"><input name="password" type="text" class="form-control" placeholder="كلمة المرور" required></div>
                    <div class="mb-2">
                        <select name="role" class="form-select">
                            <option value="coordinator">منسق صيانة</option>
                            <option value="technician">فني</option>
                            <option value="manager">مدير</option>
                        </select>
                    </div>
                    <div class="mb-2"><input name="team" class="form-control" placeholder="الفريق (للفني فقط)"></div>
                    <button class="btn btn-success w-100">إضافة وإرسال بيانات الدخول</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
