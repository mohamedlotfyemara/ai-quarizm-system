@extends('layouts.app')
@section('content')
<a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-outline-secondary mb-3">رجوع</a>
<div class="card">
    <div class="card-header">{{ $ticket->code }} - {{ $ticket->title }}</div>
    <div class="card-body">
        <p>{{ $ticket->description }}</p>
        <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item">العميل: {{ $ticket->customer->name ?? '-' }}</li>
            <li class="list-group-item">الأولوية: {{ $ticket->priority }}</li>
            <li class="list-group-item">الحالة: <span class="badge bg-secondary">{{ $ticket->status }}</span></li>
            <li class="list-group-item">الفريق المسند: {{ $ticket->assigned_team ?? '-' }}</li>
            <li class="list-group-item">الفني: {{ $ticket->technician->name ?? '-' }}</li>
            <li class="list-group-item">تأكيد العميل: {{ $ticket->customer_confirmed ? 'نعم' : 'لا' }}</li>
        </ul>
        @if ($ticket->report)
            <h6>تقرير الخدمة</h6>
            <p>{{ $ticket->report->notes }}</p>
        @endif
    </div>
</div>
@endsection
