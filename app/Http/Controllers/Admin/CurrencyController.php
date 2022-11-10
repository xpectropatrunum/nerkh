<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $limit = 10;
        $search = "";
        $query = Currency::latest();
        if ($request->search) {
            $searching_for = $request->search;
            $search = $request->search;
            $query = $query->where("slug", "like", "%$search%")->orWhere("name", "like", "%$search%");
        }

        if ($request->limit) {
            $limit = $request->limit;
        }

        $items = $query->paginate($limit);



        return view('admin.pages.currencies.index', compact('items', 'search', 'limit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $token = env('SA_TOKEN');
        $data = Http::get("http://sourcearena.ir/api/?token={$token}&currency&h")->object()->data;
        $js_data = json_encode(collect($data)->pluck('name', 'slug'));

        return view('admin.pages.currencies.create', compact('data', 'js_data'));
    }

    public function store(Request $request)
    {
        $rules = [
            "name" => "required",
            "slug" => "required",
        ];
        $request->validate($rules);
        $request->merge(["priority" => $request->priority ?? 1000]);


        $created = Currency::create($request->all());
        if ($created) {

            return redirect()->route("admin.currencies.index")->withSuccess("The record was added successfully");
        }
        return redirect()->route("admin.currencies.index")->withError("Something went wrong!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DoctorSpecialty  $tvTemp
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DoctorSpecialty  $DoctorSpecialty
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        $token = env('SA_TOKEN');
        $data = Http::get("http://sourcearena.ir/api/?token={$token}&currency&h")->object()->data;
        $js_data = json_encode(collect($data)->pluck('name', 'slug'));
        return view('admin.pages.currencies.edit', compact('data', 'currency', 'js_data'));
    }

    public function update(Request $request, Currency $currency)
    {

        $rules = [
            "name" => "required",
            "slug" => "required",
        ];
        $request->validate($rules);
       $request->merge(["priority" => $request->priority ?: 1000]);

        $updated = $currency->update($request->all());
        if ($updated) {
            return redirect()->back()->withSuccess("The record was updated successfully");
        }
        return redirect()->back()->withError("Something went wrong!");
    }
    public function destroy(Currency $currency)
    {
        if ($currency->delete()) {
            return redirect()->back()->withSuccess("The record was removed successfully");
        }
        return redirect()->back()->withError("Database Error");
    }
}
