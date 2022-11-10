<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PriceController extends Controller
{

    public function index(Request $request)
    {
        $currencies = Currency::orderBy("priority", "desc")->get();
        $token = env('SA_TOKEN');
        try {
            $data = Http::timeout(10)->get("http://sourcearena.ir/api/?token={$token}&currency&h")->object()->data;
            Cache::put("last_data", $data);
        } catch (\Exception $e) {
            $data = Cache::get("last_data", (object)[]);
        }

        $gold_ = [];
        $currency_ = [];
        $parsian_ = [];
        $data = collect($data);

        foreach ($currencies as $currency) {
            $api = $data->where("slug", $currency->slug)->first();
            if ($api) {
                $price_sell = $api->price;
                $price_buy = $api->price;
                $diff = collect($currency->diffs);
                $diff_buy = $diff->where("type", "=", "0")->first();
                $diff_sell = $diff->where("type", "=", "1")->first();
                if ($diff_buy) {
                    $price_buy += $diff_buy->value;
                }
                if ($diff_sell) {
                    $price_sell += $diff_sell->value;
                }
              
            } else {
                $price_sell = $currency->price->price;
                $price_buy = $currency->price->price;
                $diff = collect($currency->diffs);
                $diff_buy = $diff->where("type", "=", "0")->first();
                $diff_sell = $diff->where("type", "=", "1")->first();
                if ($diff_buy) {
                    $price_buy += $diff_buy->value;
                }
                if ($diff_sell) {
                    $price_sell += $diff_sell->value;
                }
              
            }
            if (str_contains($currency->slug, "TALA") || str_contains($currency->slug, "SEKE")) {
                $gold_[] = ["slug" => $currency->slug, "priority" => $currency->priority ?? 1000, "name" => $currency->name, "prices" => ["buy" => $price_buy, "sell" => $price_sell]];
            } else if (str_contains($currency->slug, "PARSIAN")) {
                $parsian_[] = ["slug" => $currency->slug, "priority" => $currency->priority ?? 1000, "name" => $currency->name, "prices" => ["buy" => $price_buy, "sell" => $price_sell]];
            } else {
                $currency_[] = ["slug" => $currency->slug, "priority" => $currency->priority ?? 1000, "name" => $currency->name, "prices" => ["buy" => $price_buy, "sell" => $price_sell]];
            }
        }

        array_multisort(array_column($gold_, "priority"), SORT_ASC, $gold_);
        array_multisort(array_column($currency_, "priority"), SORT_ASC, $currency_);
        array_multisort(array_column($parsian_, "priority"), SORT_ASC, $parsian_);

        return ["gold" => $gold_, "currency" => $currency_, "parsian" => $parsian_];
    }
}
