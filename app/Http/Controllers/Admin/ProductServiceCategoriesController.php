<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductServiceCategory;

class ProductServiceCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(\Auth::user()->can('manage constant category'))
        // {
            $categories = ProductServiceCategory::get();

            return view('admin.productServiceCategory.index', compact('categories'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function create()
    {
        // if(\Auth::user()->can('create constant category'))
        // {
            $types = ProductServiceCategory::$categoryType;

            return view('admin.productServiceCategory.create', compact('types'));
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }

    public function store(Request $request)
    {
        // if(\Auth::user()->can('create constant category'))
        // {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'type' => 'required',
                                   'color' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $category             = new ProductServiceCategory();
            $category->name       = $request->name;
            $category->color      = $request->color;
            $category->type       = $request->type;
            $category->created_by = \Auth::user()->creatorId();
            $category->save();

            activity()->performedOn($category)->log('created product service category:'.$category->name);

            return redirect()->back()->with('success', __('Category successfully created.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function edit($id)
    {

        // if(\Auth::user()->can('edit constant category'))
        // {
            $types    = ProductServiceCategory::$categoryType;
            $category = ProductServiceCategory::find($id);

            return view('admin.productServiceCategory.edit', compact('category', 'types'));
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }


    public function update(Request $request, $id)
    {
        // if(\Auth::user()->can('edit constant category'))
        // {
            try {
                $category = ProductServiceCategory::find($id);
            if($category->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'type' => 'required',
                                       'color' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $category->name  = $request->name;
                $category->color = $request->color;
                $category->type  = $request->type;
                $category->save();

                activity()->performedOn($category)->log('updated product service category:'.$category->name);

                return redirect()->back()->with('success', __('Category successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('delete constant category'))
        {
            $category = ProductServiceCategory::find($id);
            if($category->created_by == \Auth::user()->creatorId())
            {

                if($category->type == 0)
                {
                    $categories = ProductService::where('category_id', $category->id)->first();
                }
                elseif($category->type == 1)
                {
                    $categories = Invoice::where('category_id', $category->id)->first();
                }
                else
                {
                    $categories = Bill::where('category_id', $category->id)->first();
                }

                if(!empty($categories))
                {
                    return redirect()->back()->with('error', __('this category is already assign so please move or remove this category related data.'));
                }

                $category->delete();

                activity()->performedOn($category)->log('deleted product service category:'.$category->name);

                return redirect()->route('product-category.index')->with('success', __('Category successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getProductCategories()
    {
        $cat = ProductServiceCategory::getallCategories();


        $all_products = ProductService::getallproducts()->count();
//        dd($all_products);
        $html = '<div class="mb-3 mr-2 zoom-in ">
                  <div class="card rounded-10 card-stats mb-0 cat-active overflow-hidden" data-id="0">
                     <div class="category-select" data-cat-id="0">
                        <button type="button" class="btn tab-btns btn-primary">'.__("All Categories").'</button>
                     </div>
                  </div>
               </div>';
        foreach ($cat as $key => $c) {
            $dcls = 'category-select';
//            if($c->products > 0){
//                $dcls = 'category-select';
//            }
            $html .= ' <div class="mb-3 mr-2 zoom-in cat-list-btn">
                          <div class="card rounded-10 card-stats mb-0 overflow-hidden " data-id="'.$c->id.'">
                             <div class="'.$dcls.'" data-cat-id="'.$c->id.'">
                                <button type="button" class="btn tab-btns btn-primary">'.$c->name.'</button>
                             </div>
                          </div>
                       </div>';
        }
        //dd($html);
        return Response($html);
    }
}
