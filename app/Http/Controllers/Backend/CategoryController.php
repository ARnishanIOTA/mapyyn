<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.categories.index');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
      
        $categories = Category::query();
        $url = url('/backend/categories/');
        return Datatables::of($categories)
            ->addColumn('action', function ($category) use($url) {
                return '
                <a href="'.$url.'/'.$category->id.'" class="btn btn-primary m-btn m-btn--icon">
                <span><i class="flaticon-edit"></i><span>'.trans('lang.edit').'</span></span></a>
                <a href="" data-id="'.$category->id.'" class="btn btn-danger delete-button m-btn m-btn--icon">
                <span><i class="flaticon-delete-2"></i><span> '.trans('lang.remove').' </span></span></a>';
            })
            ->editColumn('name_en', function ($category){
                $name = 'name_'.LaravelLocalization::getCurrentLocale();
                return $category->$name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|unique:categories',
            'name_en' => 'required|string|unique:categories',
        ]);

        $inputs = $request->only(['name_ar', 'name_en']);

        Category::create($inputs);

        return response('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('backend.categories.update', compact('category'));
    }

    

  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_ar' => 'required|string|unique:categories,name_ar, ' . $category->id,
            'name_en' => 'required|string|unique:categories,name_en, ' . $category->id,
        ]);

        $inputs = $request->only(['name_ar', 'name_en']);
        
        $category->update($inputs);

        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
    }
}
