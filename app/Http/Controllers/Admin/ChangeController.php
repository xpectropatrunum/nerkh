<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

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
        $query = Clinic::latest();
        if ($request->search) {
            $searching_for = $request->search;
            $search = $request->search;
            $query = $query->where("name", "like", "%$search%");
        }

        if ($request->limit) {
            $limit = $request->limit;
        }

        $items = $query->paginate($limit);



        return view('admin.pages.locations.clinics.index', compact('items', 'search', 'limit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doctors = Doctor::get();
        $payload = [
            "origin" => env("APP_URL"),
            "exp" => time() + 10000,
        ];
        $languages = Language::where("enable", "1")->orderBy("is_default", "desc")->get();


        $jwt  = JWT::encode($payload, env("JWT_SECRET"), 'HS256');
        try {
            $data = json_decode(Http::withToken($jwt)->withHeaders(["admin" => 1])->get("http://account.powernation.ir/api/v2/users"));
            $countries = json_decode(Http::withToken($jwt)->withHeaders(["admin" => 1])->get("http://account.powernation.ir/api/v2/countries"));
        } catch (\Exception $e) {
            return $e;
        }
        if ($data->success) {
            $query = collect($data->data)->sortByDesc("created_at");
        } else {
            abort(500);
        }
        if ($data->success) {
            $countries = collect($countries->data)->sortByDesc("created_at");
        }
        $doctors = $doctors->each(function ($item) use ($query) {
            $item->user = $query->where("id", $item->user_id)->first();
        });

        return view('admin.pages.locations.clinics.create', compact('doctors', 'languages', 'countries'));
    }
    public function genId()
    {
        return rand(999999999, 9999999999);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "name" => "required",
            "clinic_id" => "integer|unique:clinics,clinic_id|digits:10",
            "langs" => "required",
            "address.country_id" => "required",
            "address.state_id" => "required",
            "address.city_id" => "required",
        ];
        $request->validate($rules);
        if (!$request->enable) {
            $request->merge(["enable" => 0]);
        }
        $created = Clinic::create($request->all());
        if ($created) {
            // @vocal languages
            ClinicLanguages::where(["clinic_id" => $created->id])->delete();
            collect($request->langs)->each(function ($item) use ($created) {
                ClinicLanguages::create(["clinic_id" => $created->id, "language_id" => $item]);
            });
            // @address
            $address = $request->address;
            $address["clinic_id"] = $created->id;
            //remove last one
            ClinicAddress::where(["clinic_id" => $created->id])->delete();
            ClinicAddress::create($address);
            // @business time
            //remove last one
            ClinicBusinessTimes::where(["clinic_id" => $created->id])->delete();
            collect($request->bt)->each(function ($item, $key) use ($created) {
                if ($item) {
                    ClinicBusinessTimes::create(["clinic_id" => $created->id, "weekday" => $key, "time" => $item]);
                }
            });
            // @images
            //purge last one
            $images = ClinicImages::where(["clinic_id" => $created->id]);
            $images->each(function ($item) {
                unlink($item->url);
            });
            $images->delete();
            collect($request->images)->each(function ($item, $key) use ($created) {
                if ($item) {
                    if (!is_dir(env("CLINIC_PATH") . "/" . $created->id)) {
                        mkdir(env("CLINIC_PATH") . "/" . $created->id);
                    }
                    $extension = $item->getClientOriginalExtension();
                    $filenametostore = $created->id . "/$key-" . time() . '.' . $extension;
                    $img = Image::make($item);
                    $img->encode($extension, 100);
                    File::put(env("CLINIC_PATH") . "/" . $filenametostore, (string) $img);
                    ClinicImages::create(["clinic_id" => $created->id, "url" =>   env("CLINIC_PATH") . "/" . $filenametostore]);
                }
            });

            // @alts
            // purge
            AltField::where(["model" => Clinic::class, "related_id" => $created->id])->delete();
            // new
            collect($request->alt)->each(function ($item, $key) use ($created) {
                if ($item) {
                    AltField::create(["related_id" => $created->id, "model" => Clinic::class, "key" => $key, "value" => $item]);
                }
            });

              // @doctors
            //purge
            Doctor::where("clinic_id", $created->id)->update(["clinic_id" => 0]);
            //set
            collect($request->doctors)->each(function ($item, $key) use ($created) {
                if ($item) {
                    Doctor::find($item)->update(["clinic_id" => $created->id]);
                }
            });



            return redirect()->route("admin.locations.clinics.index")->withSuccess("The record was added successfully");
        }
        return redirect()->route("admin.locations.clinics.index")->withError("Something went wrong!");
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
    public function edit($id)
    {
        $types = ["address", "slogan", "services", "name"];
        $clinic = Clinic::find($id);
        $doctors = Doctor::get();
        $payload = [
            "origin" => env("APP_URL"),
            "exp" => time() + 10000,
        ];
        $languages = Language::where("enable", "1")->orderBy("is_default", "desc")->get();


        $jwt  = JWT::encode($payload, env("JWT_SECRET"), 'HS256');
        try {
            $data = json_decode(Http::withToken($jwt)->withHeaders(["admin" => 1])->get("http://account.powernation.ir/api/v2/users"));
            $countries = json_decode(Http::withToken($jwt)->withHeaders(["admin" => 1])->get("http://account.powernation.ir/api/v2/countries"));
        } catch (\Exception $e) {
            return $e;
        }
        if ($data->success) {
            $query = collect($data->data)->sortByDesc("created_at");
        } else {
            abort(500);
        }
        if ($data->success) {
            $countries = collect($countries->data)->sortByDesc("created_at");
        }
        $doctors = $doctors->each(function ($item) use ($query) {
            $item->user = $query->where("id", $item->user_id)->first();
        });

        return view('admin.pages.locations.clinics.edit', compact('doctors', 'languages', 'countries', 'clinic', 'types'));
    }
    public function saveAlt(Request $request, $ds)
    {
        $ds = Clinic::find($ds);
        if ($request->filled('id')) {
            $translate = tap($ds->alt_field()->find($request->id))->update($request->all())->first();
        } else {
          
            $translate = $ds->alt_field()->create($request->all());
        }

        return response()->json(['status' => 1, 'message' => 'Translate save successfully', 'id' => $request->id]);
    }

    public function removeAlt(Request $request, $ds)
    {
        $ds = Clinic::find($ds);
        $ds->alt_field()->find($request->id)->delete();
        return 1;
    }
    public function update(Request $request, $cl)
    {
        $clinic = Clinic::find($cl);


        $rules = [
            "name" => "required",
            "clinic_id" => $clinic->clinic_id == $request->clinic_id ?
                "integer|digits:10" : "integer|unique:clinics,clinic_id|digits:10",
            "langs" => "required",
            "address.country_id" => "required",
            "address.state_id" => "required",
            "address.city_id" => "required",
        ];
        $request->validate($rules);
        if (!$request->enable) {
            $request->merge(["enable" => 0]);
        }
        $updated = $clinic->update($request->all());
        if ($updated) {
            // @vocal languages
            ClinicLanguages::where(["clinic_id" => $clinic->id])->delete();
            collect($request->langs)->each(function ($item) use ($clinic) {
                ClinicLanguages::create(["clinic_id" => $clinic->id, "language_id" => $item]);
            });
            // @address
            $address = $request->address;
            $address["clinic_id"] = $clinic->id;
            //remove last one
            ClinicAddress::where(["clinic_id" => $clinic->id])->delete();
            ClinicAddress::create($address);
            // @business time
            //remove last one
            ClinicBusinessTimes::where(["clinic_id" => $clinic->id])->delete();
            collect($request->bt)->each(function ($item, $key) use ($clinic) {
                if ($item) {
                    ClinicBusinessTimes::create(["clinic_id" => $clinic->id, "weekday" => $key, "time" => $item]);
                }
            });
            // @images

            $images = ClinicImages::where(["clinic_id" => $clinic->id]);
            //purge
            $images->each(function ($item) use ($request) {

                if (!isset($request->image_names["{$item->id}"])) {
                    unlink($item->url);
                    $item->delete();
                }
            });
            //purge
            if (!$request->images) {
                $images->each(function ($item) use ($request) {
                    if (!isset($request->image_names["{$item->id}"])) {
                        unlink($item->url);
                        $item->delete();
                    }
                });
            }
            //new
            collect($request->images)->each(function ($item, $key) use ($clinic) {

                if ($item) {
                    if (!is_dir(env("CLINIC_PATH") . "/" . $clinic->id)) {
                        mkdir(env("CLINIC_PATH") . "/" . $clinic->id);
                    }
                    $extension = $item->getClientOriginalExtension();
                    $filenametostore = $clinic->id . "/$key-" . time() . '.' . $extension;
                    $img = Image::make($item);
                    $img->encode($extension, 100);
                    File::put(env("CLINIC_PATH") . "/" . $filenametostore, (string) $img);
                    ClinicImages::create(["clinic_id" => $clinic->id, "url" =>   env("CLINIC_PATH") . "/" . $filenametostore]);
                }
            });

            // @alts
            // purge
            AltField::where(["model" => Clinic::class, "related_id" => $clinic->id])->delete();
            // new
            collect($request->alt)->each(function ($item, $key) use ($clinic) {
                if ($item) {
                    AltField::create(["related_id" => $clinic->id, "model" => Clinic::class, "key" => $key, "value" => $item]);
                }
            });

            // @doctors
            //purge
            Doctor::where("clinic_id", $clinic->id)->get()->each(function($item){
                $item->update(["clinic_id" => 0]);
            });
            //set
            collect($request->doctors)->each(function ($item, $key) use ($clinic) {
                if ($item) {
                    Doctor::find($item)->update(["clinic_id" => $clinic->id]);
                }
            });




            return redirect()->back()->withSuccess("The record was updated successfully");
        }
        return redirect()->back()->withError("Something went wrong!");
    }
    public function status(Request $request, $cl)
    {
        $request->validate([
            'enable' => 'required'
        ]);
        $clinic = Clinic::find($cl);
        $clinic->update($request->except('_token'));

        return true;
    }
    public function destroy($id)
    {
        $cl = Clinic::find($id);
        if ($cl->delete()) {
            $cl->address()->delete();
            $cl->business_times()->delete();
            $cl->images->each(function ($item) {
                unlink($item->url);
            });
            $cl->images()->delete();
            $cl->alt_fields()->delete();
            $cl->ratings()->delete();
            $cl->languages()->delete();

            return redirect()->route("admin.locations.clinics.index")->withSuccess("The record was removed successfully");
        }
        return redirect()->route("admin.locations.clinics.index")->withError("Database Error");
    }
}
