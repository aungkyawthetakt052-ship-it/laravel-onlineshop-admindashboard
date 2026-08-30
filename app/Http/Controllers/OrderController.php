<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Order List Page
    public function index(Request $request)
    {
        $query = Order::with(['user', 'product']);

        if ($request->filled('search')) {
            $search = strtolower(str_replace(' ', '', $request->search));

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->whereRaw("LOWER(REPLACE(name, ' ', '')) LIKE ?", ["%{$search}%"]);
                })->orWhereHas('product', function ($sub) use ($search) {
                    $sub->whereRaw("LOWER(REPLACE(name, ' ', '')) LIKE ?", ["%{$search}%"]);
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        // တစ်ခါတည်း count ရယူ (N+1 မဖြစ်အောင်)
        $statusCounts = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalOrders = $orders->total();

        return view('adminview.orders', compact('orders', 'statusCounts', 'totalOrders'));
    }

    // Order Detail Page
    public function detail(int $id)
    {
        $order = Order::with(['user', 'product'])->findOrFail($id);
        return view('adminorder.detail', compact('order'));
    }


    public function createPage()
    {
        $users = User::where('user_type', '!=', 'admin')->get(); // Admin ကို customer list ထဲ မထည့်ချင်လို့ (လိုချင်တာအတိုင်း ဖြုတ်လို့ရ)
        $products = Product::all();

        return view('adminorder.create', compact('users', 'products'));
    }

    // Order Store
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $product = Product::findOrFail($request->product_id);

        Order::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $product->price * $request->quantity, // Auto calculate
            'status' => $request->status,
        ]);

        return redirect()->route('admin.orderpage')->with('success', 'Order created successfully');
    }
    // Update Order Status
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::with(['user', 'product'])->findOrFail($id);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Status တကယ် ပြောင်းသွားမှသာ notification ပို့
        if ($oldStatus !== $request->status) {

            // Customer ကို ပို့
            if ($order->user) {
                $order->user->notify(new OrderStatusNotification($order, $request->status));
            }

            // Admin / Superadmin တွေကို ပို့
            $admins = User::whereIn('user_type', ['admin', 'superadmin'])->get();

            foreach ($admins as $admin) {
                // ကိုယ်တိုင် status ပြောင်းတဲ့ admin ကို မပို့ချင်ရင် ဒီလို စစ်လို့ရ
                // if ($admin->id === auth()->id()) continue;

                $admin->notify(new OrderStatusNotification($order, $request->status));
            }
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    // Delete Order
    public function destroy(int $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orderpage')->with('success', 'Order deleted successfully');
    }
}