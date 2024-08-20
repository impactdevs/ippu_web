<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductService;
use App\Models\StockReport;

class ProductStocksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // if(\Auth::user()->can('manage product & service'))
        // {
            $productServices = ProductService::where('type', '=', 'product')->get();

            return view('admin.productstock.index', compact('productServices'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\ProductStock $productStock
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductStock $productStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ProductStock $productStock
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productService = ProductService::find($id);
        // if(\Auth::user()->can('edit product & service'))
        // {
            // if($productService->created_by == \Auth::user()->creatorId())
            // {
                return view('admin.productstock.edit', compact('productService'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Permission denied.')], 401);
            // }
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProductStock $productStock
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // if(\Auth::user()->can('edit product & service'))
        // {
            $productService = ProductService::find($id);
            $total          = $productService->quantity + $request->quantity;

            // if($productService->created_by == \Auth::user()->creatorId())
            // {
                try{
                    \DB::beginTransaction();
                    $productService->quantity   = $total;
                $productService->created_by = \Auth::user()->creatorId();
                $productService->save();

                //Product Stock Report
                $type        = 'manually';
                $type_id     = 0;
                $description = $request->quantity . '  ' . __('quantity added by manually');
                
                $stocks             = new StockReport();
                $stocks->product_id = $productService->id;
                $stocks->quantity    = $request->quantity;
                $stocks->type = $type;
                $stocks->type_id = $type_id;
                $stocks->description = $description;
                $stocks->created_by =\Auth::user()->creatorId();
                $stocks->save();
        

                \DB::commit();
                activity()->performedOn($stocks)->log('updated product stock:'.$product_id);
                return redirect()->back()->with('success', __('Product quantity updated manually.'));
                }catch(\Throwable $e){
                    return redirect()->back()->with('error', $e->getMessage());
                }
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission denied.'));
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ProductStock $productStock
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductStock $productStock)
    {
        //
    }
}
