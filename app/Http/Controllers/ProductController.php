<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::filter()->sort()->getOrPaginate();
        return ProductResource::collection($products);
        //return response()->json(['data' => ProductResource::collection($products)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {       
        $supplier = $request['supplier'];        

        $data = json_decode($request->getContent(), true);

        $product = new Product();
        $product->Product_Slug = strtolower(str_replace(' ', '-', $data['Product_Name']));
        $product->Product_Category = $data['Product_Category'];
        $product->Product_Name = $data['Product_Name'];
        $product->Product_Tags = null;
        $product->Product_Images = [];
        $product->Product_Description = $data['Product_Description'];
        $product->Product_Availability = false;
        $product->Product_Stock = $data['Product_Stock'];
        $product->Product_Ratings = null;
        $product->Product_PromoCodes = null;
        $product->Product_ShippingMethods = null;
        $product->Product_PaymentMethodsAvailable = [];
        $product->Product_SupplierID = new ObjectId($supplier->_id);

        try {
            $product->save();
            return response()->json([
                'message' => 'Product created successfully',
                'data' => ProductResource::make($product)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Product creation failed',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 409);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return ProductResource::make($product);
    }
    public function slug($slug)
    {
        try {
            // Buscar el producto por el slug
            $product = Product::where('Product_Slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra, devolver un mensaje genérico
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
        // Devolver los datos del producto
        return ProductResource::make($product);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {

        $data = json_decode($request->getContent(), true);
        $product->Product_Category = $data['Product_Category'] ?? $product->Product_Category;
        $product->Product_Name = $data['Product_Name'] ?? $product->Product_Name;
        $product->Product_Slug = strtolower(str_replace(' ', '-', $data['Product_Name'] ?? $product->Product_Name));
        $product->Product_Tags = $data['Product_Tags'] ?? $product->Product_Tags;
        $product->Product_Images = $data['Product_Images'] ?? $product->Product_Images;
        $product->Product_Description = $data['Product_Description'] ?? $product->Product_Description;
        $product->Product_Availability = $data['Product_Availability'] ?? $product->Product_Availability;
        $product->Product_Stock = $data['Product_Stock'] ?? $product->Product_Stock;
        $product->Product_Ratings = $product->Product_Ratings;
        $product->Product_PromoCodes = $data['Product_PromoCodes'] ?? $product->Product_PromoCodes;
        $product->Product_ShippingMethods = null;
        $product->Product_PaymentMethodsAvailable = $data['Product_PaymentMethodsAvailable'] ?? $product->Product_PaymentMethodsAvailable;

        try {
            $product->save();
            return response()->json([
                'message' => 'Product updated successfully',
                'data' => ProductResource::make($product)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Product updated failed',
                'error' => $e
            ], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json([
                'message' => 'Product deleted successfully',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Product deletion failed',
                'error' => $e
            ], 409);
        }
    }
}
