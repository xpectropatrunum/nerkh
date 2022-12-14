<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Change;
use App\Models\Currency;
use Illuminate\Http\Request;

class ChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = "";
        $limit = 10;
        $query = Change::latest();
        if ($request->search) {
            $searching_for = $request->search;
            $search = $request->search;
            $query = $query->where("name", "like", "%$search%");
        }

        if ($request->limit) {
            $limit = $request->limit;
        }
        $items = $query->paginate($limit);
        return view('admin.pages.changes.index', compact('items', 'search', 'limit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = collect([(object)["id" => 0, "name" => "buy"],(object)["id" => 1, "name" => "sell"]])->toArray();
        $data = Currency::get();
        return view('admin.pages.changes.create', compact('data', 'types'));
    }
   
    public function store(Request $request)
    {
        $rules = [
            "currency_id" => "required",
            "value" => "required",
            "type" => "required",
        ];
        $request->validate($rules);
   
        $created = Change::create($request->all());
        if ($created) {
            return redirect()->route("admin.changes.index")->withSuccess("The record was added successfully");
        }
        return redirect()->route("admin.changes.index")->withError("Something went wrong!");
    }

 
    public function edit(Change $change)
    {
        $data = Currency::get();
        $types = collect([(object)["id" => 0, "name" => "buy"],(object)["id" => 1, "name" => "sell"]])->toArray();
        return view('admin.pages.changes.edit', compact('change', 'data', 'types'));
    }
   
    public function update(Request $request, Change $change)
    {

        $rules = [
            "currency_id" => "required",
            "value" => "required",
            "type" => "required",
        ];
        $request->validate($rules);

        $updated = $change->update($request->all());
        if ($updated) {
            return redirect()->back()->withSuccess("The record was updated successfully");
        }
        return redirect()->back()->withError("Something went wrong!");
    }
   
    public function destroy(Change $change)
    {
       
        if ($change->delete()) {
            return redirect()->back()->withSuccess("The record was removed successfully");
        }
        return redirect()->back()->withError("Database Error");
    }
}
