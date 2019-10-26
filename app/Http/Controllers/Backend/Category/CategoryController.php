<?php

namespace App\Http\Controllers\Backend\Category;

use App\Models\Category;
use App\Models\Competence;
use App\Http\Resources\CategoryCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $category;

    protected $competence;
    
    /**
     * Constructor
     * 
     * @param Category $category
     */

    public function __construct(
        Category $category,
        Competence $competence
    )
    {
        $this->category = $category;
        $this->competence = $competence;
    }

    /**
     * Get all records
     *
     * @return \Illuminate\Http\Response
     */

    public function get()
    {
        $categorys = $this->category->orderBy('order', 'ASC')->get();
        return new CategoryCollection($categorys);
    }

    public function getSubcategories()
    {
        $subcategories = $this->category->subcategories;
        return response()->json($subcategories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {   
        $category = new Category([
            'name' => $request->input('name'),
        ]);
        $category->save();
        return response()->json(['categoryId' => $category->id]);
    }

    /**
     * Edit a specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = $this->category->findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $category = $this->category->findOrFail($id);
        $category->name = $request->input('name');
        $category->save();
        return response()->json('successfully updated');
    }

    /**
     * Clone a specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function clone($id)
    {
        $category = $this->category->findOrFail($id);
        $categoryCopy = $category->replicate();
        $categoryCopy->name     = $category->name . ' (Kopie)';
        $categoryCopy->publish  = 0;
        $categoryCopy->save();
        return response()->json($categoryCopy);
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $category = $this->category->findOrFail($id);
        $category->publish = $category->publish == 0 ? 1 : 0;
        $category->save();
        return response()->json($category->publish);
    }

    /**
     * Update the order of the resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function order(Request $request)
    {
        $categories = $request->get('categories');
        foreach($categories as $category)
        {
            $cat = $this->category->find($category['id']);
            $cat->order = $category['order'];
            $cat->save();
        }
        return response()->json('successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->category->find($id);
        if ($category)
        {
            $category->delete();

            // Delete relationships with competences
            // @todo: refactor (batch)
            $competences = $this->competence->where('category_id', '=', $id);
            foreach($competences as $competence)
            {
                $competence->category_id = null;
                $competence->save();
            }
        }
        return response()->json('successfully deleted');
    }
}
