<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PriceController extends Controller
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
        $query = Price::latest();
        if ($request->search) {
            $searching_for = $request->search;
            $search = $request->search;
            $query = $query->where("slug", "like", "%$search%")->orWhere("name", "like", "%$search%");
        }

        if ($request->limit) {
            $limit = $request->limit;
        }

        $items = $query->paginate($limit);



        return view('admin.pages.prices.index', compact('items', 'search', 'limit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Currency::get();
        return view('admin.pages.prices.create', compact('data'));
    }

    public function store(Request $request)
    {
        $rules = [
            "currency_id" => "required",
            "price" => "required|integer",
        ];
        $request->validate($rules);
       
        $created = Price::create($request->all());
        if ($created) {

            return redirect()->route("admin.prices.index")->withSuccess("The record was added successfully");
        }
        return redirect()->route("admin.prices.index")->withError("Something went wrong!");
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
    public function edit(Price $price)
    {
        $data = Currency::get();

        return view('admin.pages.prices.edit', compact('data', 'price'));
    }

    public function update(Request $request, Price $price)
    {

        $rules = [
            "currency_id" => "required",
            "price" => "required|integer",
        ];
        $request->validate($rules);

        $updated = $price->update($request->all());
        if ($updated) {
            return redirect()->back()->withSuccess("The record was updated successfully");
        }
        return redirect()->back()->withError("Something went wrong!");
    }
    public function destroy(Price $price)
    {
        if ($price->delete()) {
            return redirect()->back()->withSuccess("The record was removed successfully");
        }
        return redirect()->back()->withError("Database Error");
    }
}
