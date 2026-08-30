<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{



    // Product Detail (View)
    public function adminProductDetailPage(int $id)
    {
        $product = Product::findOrFail($id);
        return view('adminproduct.detail', compact('product'));
    }
    // End product detail page

    //Get Product page

    public function adminProductPage(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            // User ရိုက်ထည့်တဲ့ text ကနေ space ဖယ်ပြီး lowercase ပြောင်းမယ်
            $search = strtolower(str_replace(' ', '', $request->search));

            // Database ထဲက 'name' column ကနေလည်း space ဖယ်ပြီး lowercase ပြောင်းပြီးမှ compare
            $query->whereRaw("LOWER(REPLACE(name, ' ', '')) LIKE ?", ["%{$search}%"]);
        }

        $products = $query->latest()->get();

        return view('adminview.products', compact('products'));
    }
    // End Get Product page

    // Product Create Page

    public function adminProductCreatePage()
    {
        return view('adminproduct.create');
    }
    // End Product Create Page

    // Product Create 

    public function adminProductCreate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'photoupload' => 'required|image|mimes:jpg,jpeg,png,webp|max:5012',
        ]);

        $file = $request->file('photoupload');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $fileName);

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'photoupload' => $fileName,
        ]);

        return redirect()->route('admin.productpage')->with('success', 'Product created successfully');
    }
    //End  Product Create

    // Product Edit Page

    public function adminProductEditPage(int $id)
    {
        $product = Product::findOrFail($id);
        return view('adminproduct.edit', compact('product'));
    }
    //End  Product Edit Page

    // Product Edit 
    
    public function adminProductEdit(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photoupload' => 'nullable|image|max:5012',
        ]);

        $product->name = $request->name;
        $product->price = $request->price;

        if ($request->hasFile('photoupload')) {
            if ($product->photoupload && File::exists(public_path('images/' . $product->photoupload))) {
                File::delete(public_path('images/' . $product->photoupload));
            }
            $file = $request->file('photoupload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fileName);
            $product->photoupload = $fileName;
        }

        $product->save();

        return redirect()->route('admin.productpage')->with('success', 'Product updated successfully');
    }
    // End Product Edit

    // Product delete 
    public function adminProductDelete(int $id)
    {
        $product = Product::findOrFail($id);
        $imagePath = public_path('images/' . $product->photoupload);

        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $product->delete();
        return redirect()->route('admin.productpage')->with('success', 'Delete Successfully');
    }

}


