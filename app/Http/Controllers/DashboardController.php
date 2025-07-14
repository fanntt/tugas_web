<?php

namespace App\Http\Controllers;

use App\Models\IrfanCategory;
use App\Models\IrfanOrder;
use App\Models\IrfanProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $totalProducts = IrfanProduct::count();
        $totalCategories = IrfanCategory::count();
        $totalOrders = IrfanOrder::count();
        $userOrders = $user->orders()->count();

        // Get recent orders for dashboard
        $recentOrders = IrfanOrder::with(['user', 'orderItems.product'])
            ->latest()
            ->take(5)
            ->get();

        // Calculate total sales
        $totalSales = IrfanOrder::where('status', 'completed')->sum('total_amount');

        return view('dashboard.index', compact('totalProducts', 'totalCategories', 'totalOrders', 'userOrders', 'recentOrders', 'totalSales'));
    }

    // Categories
    public function categories()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $categories = IrfanCategory::withCount('products')->get();
        return view('dashboard.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        return view('dashboard.categories.create');
    }

    public function storeCategory(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        IrfanCategory::create($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function editCategory(IrfanCategory $category)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        return view('dashboard.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, IrfanCategory $category)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category->update($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function deleteCategory(IrfanCategory $category)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus');
    }

    // Products
    public function products()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $products = IrfanProduct::with('category')->get();
        return view('dashboard.products.index', compact('products'));
    }

    public function createProduct()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $categories = IrfanCategory::all();
        return view('dashboard.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'irfan_category_id' => 'required|exists:irfan_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
            // Debug: cek path image
            Log::info('Image uploaded:', ['path' => $data['image']]);
        } else {
            Log::warning('No image uploaded');
        }
        $product = IrfanProduct::create($data);
        if (empty($product->image)) {
            return redirect()->back()->withInput()->withErrors(['image' => 'Gambar tidak tersimpan.']);
        }
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function editProduct(IrfanProduct $product)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $categories = IrfanCategory::all();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, IrfanProduct $product)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'irfan_category_id' => 'required|exists:irfan_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
            Log::info('Image uploaded (update):', ['path' => $data['image']]);
        } else {
            Log::warning('No image uploaded (update)');
        }
        $product->update($data);
        if (empty($product->image)) {
            return redirect()->back()->withInput()->withErrors(['image' => 'Gambar tidak tersimpan.']);
        }
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function deleteProduct(IrfanProduct $product)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }

    public function showProduct(IrfanProduct $product)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $product->load('category');
        return view('dashboard.products.show', compact('product'));
    }

    // Orders (user)
    public function orders()
    {
        if (Auth::user()->role !== 'user') {
            abort(403, 'Unauthorized.');
        }
        // Hanya order milik user yang login
        $orders = Auth::user()->orders()->with(['user', 'orderItems.product'])->get();
        return view('dashboard.orders.index', compact('orders'));
    }

    public function createOrder()
    {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        $users = User::all();
        $products = IrfanProduct::all();
        return view('dashboard.orders.create', compact('users', 'products'));
    }

    public function storeOrder(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:irfan_products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string'
        ]);
        DB::beginTransaction();
        try {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(IrfanOrder::count() + 1, 4, '0', STR_PAD_LEFT);
            $order = IrfanOrder::create([
                'order_number' => $orderNumber,
                'user_id' => $request->user_id,
                'status' => $request->status,
                'notes' => $request->notes,
                'total_amount' => 0
            ]);
            $totalAmount = 0;
            foreach ($request->products as $index => $productId) {
                if (!empty($productId) && isset($request->quantities[$index])) {
                    $product = IrfanProduct::find($productId);
                    $quantity = $request->quantities[$index];
                    $price = $product->price;
                    $subtotal = $price * $quantity;
                    $totalAmount += $subtotal;
                    $order->orderItems()->create([
                        'irfan_product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal
                    ]);
                }
            }
            $order->update(['total_amount' => $totalAmount]);
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat order']);
        }
    }

    public function showOrder(IrfanOrder $order)
    {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        // User hanya boleh akses order miliknya, admin boleh akses semua
        if (Auth::user()->role === 'user' && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
        $order->load(['user', 'orderItems.product']);
        return view('dashboard.orders.show', compact('order'));
    }

    public function editOrder(IrfanOrder $order)
    {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        // User hanya boleh edit order miliknya, admin boleh edit semua
        if (Auth::user()->role === 'user' && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
        $users = User::all();
        $products = IrfanProduct::all();
        $order->load('orderItems');
        return view('dashboard.orders.edit', compact('order', 'users', 'products'));
    }

    public function updateOrder(Request $request, IrfanOrder $order)
    {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        // User hanya boleh update order miliknya, admin boleh update semua
        if (Auth::user()->role === 'user' && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:irfan_products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string'
        ]);
        DB::beginTransaction();
        try {
            $order->update([
                'user_id' => $request->user_id,
                'status' => $request->status,
                'notes' => $request->notes
            ]);
            $order->orderItems()->delete();
            $totalAmount = 0;
            foreach ($request->products as $index => $productId) {
                if (!empty($productId) && isset($request->quantities[$index])) {
                    $product = IrfanProduct::find($productId);
                    $quantity = $request->quantities[$index];
                    $price = $product->price;
                    $subtotal = $price * $quantity;
                    $totalAmount += $subtotal;
                    $order->orderItems()->create([
                        'irfan_product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal
                    ]);
                }
            }
            $order->update(['total_amount' => $totalAmount]);
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui order']);
        }
    }

    public function deleteOrder(IrfanOrder $order)
    {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        // User hanya boleh hapus order miliknya, admin boleh hapus semua
        if (Auth::user()->role === 'user' && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus');
    }

    public function myOrders()
    {
        if (Auth::user()->role !== 'user') {
            abort(403, 'Unauthorized.');
        }
        $orders = Auth::user()->orders()->with('orderItems.product')->get();
        return view('dashboard.orders.my-orders', compact('orders'));
    }

    // Orders (admin)
    public function adminOrders()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $orders = IrfanOrder::with(['user', 'orderItems.product'])->get();
        return view('dashboard.orders.index', compact('orders'));
    }

    public function adminShowOrder(IrfanOrder $order)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $order->load(['user', 'orderItems.product']);
        return view('dashboard.orders.show', compact('order'));
    }

    public function adminEditOrder(IrfanOrder $order)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Unauthorized.');
        $users = User::all();
        $products = IrfanProduct::all();
        $order->load('orderItems');
        return view('dashboard.orders.edit', compact('order', 'users', 'products'));
    }

    public function adminUpdateOrder(Request $request, IrfanOrder $order)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Unauthorized.');
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:irfan_products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string'
        ]);
        DB::beginTransaction();
        try {
            $order->update([
                'user_id' => $request->user_id,
                'status' => $request->status,
                'notes' => $request->notes
            ]);
            $order->orderItems()->delete();
            $totalAmount = 0;
            foreach ($request->products as $index => $productId) {
                if (!empty($productId) && isset($request->quantities[$index])) {
                    $product = IrfanProduct::find($productId);
                    $quantity = $request->quantities[$index];
                    $price = $product->price;
                    $subtotal = $price * $quantity;
                    $totalAmount += $subtotal;
                    $order->orderItems()->create([
                        'irfan_product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal
                    ]);
                }
            }
            $order->update(['total_amount' => $totalAmount]);
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui order']);
        }
    }

    public function adminDeleteOrder(IrfanOrder $order)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Unauthorized.');
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus');
    }

    public function adminCreateOrder() {
        if (Auth::user()->role !== 'admin') abort(403, 'Unauthorized.');
        $users = User::all();
        $products = IrfanProduct::all();
        return view('dashboard.orders.create', compact('users', 'products'));
    }

    public function adminStoreOrder(Request $request) {
        if (Auth::user()->role !== 'admin') abort(403, 'Unauthorized.');
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*' => 'required|exists:irfan_products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string'
        ]);
        DB::beginTransaction();
        try {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(IrfanOrder::count() + 1, 4, '0', STR_PAD_LEFT);
            $order = IrfanOrder::create([
                'order_number' => $orderNumber,
                'user_id' => $request->user_id,
                'status' => $request->status,
                'notes' => $request->notes,
                'total_amount' => 0
            ]);
            $totalAmount = 0;
            foreach ($request->products as $index => $productId) {
                if (!empty($productId) && isset($request->quantities[$index])) {
                    $product = IrfanProduct::find($productId);
                    $quantity = $request->quantities[$index];
                    $price = $product->price;
                    $subtotal = $price * $quantity;
                    $totalAmount += $subtotal;
                    $order->orderItems()->create([
                        'irfan_product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal
                    ]);
                }
            }
            $order->update(['total_amount' => $totalAmount]);
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat order']);
        }
    }
}
