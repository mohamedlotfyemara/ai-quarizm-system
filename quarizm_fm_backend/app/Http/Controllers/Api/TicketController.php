<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceReport;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // GET /api/tickets - تعرض حسب دور المستخدم الحالي
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Ticket::with(['customer', 'technician', 'report']);

        if ($user->isRole('customer')) {
            $query->where('customer_id', $user->id);
        } elseif ($user->isRole('technician')) {
            $query->where('assigned_team', $user->team);
        }
        // منسق الصيانة والمدير يريان كل البلاغات

        return response()->json($query->orderByDesc('id')->get());
    }

    public function show(Request $request, Ticket $ticket)
    {
        return response()->json($ticket->load(['customer', 'technician', 'report']));
    }

    // POST /api/tickets - تسجيل بلاغ جديد (العميل/المشرف)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high,critical',
            'attachments' => 'array',
        ]);

        $ticket = Ticket::create([
            'code' => Ticket::generateCode(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'customer_id' => $request->user()->id,
            'priority' => $data['priority'] ?? 'medium',
            'status' => 'received',
            'attachments' => $data['attachments'] ?? [],
        ]);

        return response()->json($ticket, 201);
    }

    // PATCH /api/tickets/{ticket}/assign - منسق الصيانة يسند البلاغ لفريق
    public function assign(Request $request, Ticket $ticket)
    {
        $data = $request->validate(['team' => 'required|string']);

        $ticket->update([
            'assigned_team' => $data['team'],
            'status' => 'assigned',
        ]);

        return response()->json($ticket);
    }

    // PATCH /api/tickets/{ticket}/accept - الفني يقبل المهمة
    public function accept(Request $request, Ticket $ticket)
    {
        $ticket->update([
            'technician_id' => $request->user()->id,
            'status' => 'accepted',
        ]);

        return response()->json($ticket);
    }

    // PATCH /api/tickets/{ticket}/start - الفني يبدأ التنفيذ
    public function start(Request $request, Ticket $ticket)
    {
        $ticket->update(['status' => 'in_progress']);

        return response()->json($ticket);
    }

    // POST /api/tickets/{ticket}/report - الفني يرسل تقرير الخدمة
    public function submitReport(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'notes' => 'nullable|string',
            'photos' => 'array',
        ]);

        $report = ServiceReport::updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'technician_id' => $request->user()->id,
                'notes' => $data['notes'] ?? '',
                'photos' => $data['photos'] ?? [],
            ]
        );

        return response()->json($report, 201);
    }

    // PATCH /api/tickets/{ticket}/confirm - العميل يؤكد إتمام العمل ويغلق البلاغ
    public function confirm(Request $request, Ticket $ticket)
    {
        $ticket->update([
            'customer_confirmed' => true,
            'status' => 'closed',
        ]);

        return response()->json($ticket);
    }

    // GET /api/stats - مؤشرات لوحة تحكم المدير
    public function stats()
    {
        return response()->json([
            'open' => Ticket::whereNotIn('status', ['closed'])->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
            'total' => Ticket::count(),
        ]);
    }
}
