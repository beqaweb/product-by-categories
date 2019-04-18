<?php

namespace App\Http\Controllers;

use App\Category;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * List categories
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::query()
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show form for adding a new category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function new()
    {
        return view('admin.categories.new');
    }

    /**
     * Create a new category
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return view('admin.categories.new', $errors);
        }

        $category = new Category;
        $category->fill(
            $request->only($category->getFillable())
        );
        $category->save();

        return redirect()->route('categoryList');
    }

    /**
     * Show category update form
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateForm(Category $category)
    {
        return view('admin.categories.update', compact('category'));
    }

    /**
     * Update a category
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return view('admin.categories.update', $errors);
        }

        $category->fill(
            $request->only($category->getFillable())
        );
        $category->save();

        return redirect()->route('categoryList');
    }

    /**
     * Show form for assigning category to a user
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function permissions(Category $category)
    {
        $category->load('permittedUsers');
        $idsOfTheAlreadyPermittedUsers = array_map(function ($user) {
            return $user['id'];
        }, $category->permittedUsers->toArray());

        $users = User::role('admin-manager')
            ->whereNotIn('id', $idsOfTheAlreadyPermittedUsers)
            ->get();
        return view('admin.categories.permissions', compact('category', 'users'));
    }

    public function updatePermissions(Request $request, Category $category)
    {
        $usersToAttach = $request['idsToAttach'];
        $usersToDetach = $request['idsToDetach'];

        if ($usersToAttach && count($usersToAttach) > 0) {
            $category->permittedUsers()->attach($usersToAttach);
        }
        if ($usersToDetach && count($usersToDetach) > 0) {
            $category->permittedUsers()->detach($usersToDetach);
        }

        return redirect()->route('categoryPermissions', $category);
    }

    /**
     * Show confirm dialog for category delete
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleteConfirm(Category $category)
    {
        return view('admin.categories.delete', compact('category'));
    }

    /**
     * Delete category
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Category $category)
    {
        try {
            $category->delete();
        } catch (\Exception $exception) {
        }
        return redirect()->route('categoryList');
    }
}