<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::filter()->sort()->getOrPaginate();
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $order = new Order();

        $order->Order_Date = now()->toIso8601String();
        $order->Order_Status = "Pendiente";
        $order->Order_Total = $order->getTotalPrice($request->Products, $request->Order_PaymentMethod);
        $order->Order_PaymentMethod = $request->Order_PaymentMethod;
        $order->Order_ShippingMethod = null;
        $order->Products = $order->getShippingProducts($request->Products, $request->Order_PaymentMethod);
        $order->Client = $order->getShippingClient($request->Client);
        $order->Order_ShippingAddress = null;

        try {
            $order->save();
            //return OrderResource::make($order);
            return response()->json([
                'message' => 'Orden creada correctamente',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            // Incluir el mensaje de error en la respuesta para depuración
            return response()->json([
                'message' => 'Error al crear la orden',
                'error' => $e->getMessage(), // Mensaje de la excepción
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return OrderResource::make($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order)
    {
        $order->Order_Date = now()->toIso8601String();
        $order->Order_Total = $order->getTotalPrice($request->Products, $request->Order_PaymentMethod).'';
        $order->Order_PaymentMethod = $request->Order_PaymentMethod;
        $order->Products = $order->getShippingProducts($request->Products, $request->Order_PaymentMethod);

        try {
            $order->save();
            return OrderResource::make($order);
        } catch (\Exception $e) {
            // Incluir el mensaje de error en la respuesta para depuración
            return response()->json([
                'message' => 'Error al crear la orden',
                'error' => $e->getMessage(), // Mensaje de la excepción
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $orderDelete = $order;
        
        try {
            $order->delete();
            return response()->json([
                'message' => 'Orden eliminada correctamente',
                'order' => $orderDelete,
            ]);
        } catch (\Exception $e) {
            // Incluir el mensaje de error en la respuesta para depuración
            return response()->json([
                'message' => 'Error al eliminar la orden',
                'error' => $e->getMessage(), // Mensaje de la excepción
            ], 500);
        }
    }
}
