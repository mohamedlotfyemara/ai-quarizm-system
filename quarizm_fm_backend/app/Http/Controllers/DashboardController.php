<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// لوحة تحكم المدير - Blade عادية بدون أي فرونت اند منفصل (Bootstrap فقط)
class DashboardController extends Controller
{
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }
        return view('dashboard.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($data)) {
            return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة']);
        }

        if (! in_array(Auth::user()->role, ['manager', 'coordinator'])) {
            Auth::logout();
            return back()->withErrors(['email' => 'هذا الحساب لا يملك صلاحية الدخول للوحة التحكم']);
        }

        $request->session()->regenerate();
        return redirect()->route('dashboard.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('dashboard.login');
    }

    public function index()
    {
        $stats = [
            'open' => Ticket::whereNotIn('status', ['closed'])->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
            'total' => Ticket::count(),
            'critical' => Ticket::where('priority', 'critical')->whereNotIn('status', ['closed'])->count(),
        ];

        $tickets = Ticket::with(['customer', 'technician'])
            ->orderByDesc('id')
            ->paginate(15);

        return view('dashboard.index', compact('stats', 'tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['customer', 'technician', 'report']);
        return view('dashboard.show', compact('ticket'));
    }

    // إضافة عميل جديد وإرسال بيانات الدخول له
    public function storeCustomer(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'customer',
            'phone' => $data['phone'] ?? null,
        ]);

        return back()->with('status', 'تم إضافة العميل وإرسال بيانات الدخول له');
    }

    // إضافة موظف/فني/منسق مع تحديد الدور والفريق
    public function storeStaff(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:coordinator,technician,manager',
            'team' => 'nullable|string',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'team' => $data['team'] ?? null,
        ]);

        return back()->with('status', 'تم إضافة الموظف وإرسال بيانات الدخول له');
    }
}
