<?php

namespace App\Http\Controllers;

use App\Models\SumberBayar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SumberBayarRequest;
use App\Http\Resources\SumberBayarResource;

class SumberBayarController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = SumberBayar::orderBy('nama', 'ASC');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->where('name', 'LIKE', '%' . $search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new SumberBayarResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => SumberBayarResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }
}
