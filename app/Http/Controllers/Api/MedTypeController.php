<?php

namespace App\Http\Controllers\Api;

use App\Models\MedType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MedTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medType = MedType::all();
        return response()->json([
            "success" => true,
            "attributes" => $medType,
            "count" => $medType->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // validate
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|min:3|unique:med_types",
                "created_by" => "required"
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation error",
                "error" => $validator->errors()
            ]);
        }

        //create MedType record
        $res = MedType::create([
            "name" => $request->name,
            "created_by" => $request->created_by,
        ]);

        if ($res) {
            return response()->json([
                "success" => true,
                "message" => "Medicine Type created successfully.",
                "attributes" => $res
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong."
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MedType  $medType
     * @return \Illuminate\Http\Response
     */
    public function show(MedType $medType)
    {
        //
        return response()->json([
            "success" => true,
            "attributes" => $medType
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MedType  $medType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedType $medType)
    {
        // validate
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|min:3|unique:med_types,name," . $medType->id . ",id",
                "created_by" => "required"
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation error",
                "error" => $validator->errors()
            ]);
        }

        //update medType record
        $res = $medType->update([
            "name" => $request->name,
            "created_by" => $request->created_by,
        ]);

        if ($res) {
            return response()->json([
                "success" => true,
                "message" => "Medicine Type updated successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong."
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MedType  $medType
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedType $medType)
    {
        $medType->delete();
        return response()->json([
            "success" => true,
            "message" => "Medicine Type deleted successfully."
        ]);
    }

    public function trash(Request $request)
    {
        $res = MedType::find($request->id);

        if ($res && $res->delete()) {
            return response()->json([
                "success" => true,
                "message" => "Data trashed successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Data not found."
            ]);
        }
    }

    public function trashed()
    {
        $trashed = MedType::onlyTrashed();
        if (!$trashed->count() > 0) {
            return response()->json([
                "success" => false,
                "message" => "Trashed records did not found."
            ]);
        }
        return response()->json([
            "success" => true,
            "attributes" => $trashed->get(),
            "count" => $trashed->count()
        ]);
    }

    public function withTrashed()
    {
        $allData = MedType::withTrashed();
        return response()->json([
            "success" => true,
            "trashed" => $allData->get(),
            "count" => $allData->count()
        ]);
    }

    public function restore(Request $request)
    {
        $trashed = MedType::onlyTrashed()->whereId($request->id);
        if ($trashed->count() > 0 && $trashed->restore()) {
            return response()->json([
                "success" => true,
                "message" => "Data restored successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Requested data did not found."
            ]);
        }
    }

    public function restoreAll()
    {
        $trashed = MedType::onlyTrashed();
        if ($trashed->count() > 0 && $trashed->restore()) {
            return response()->json([
                "success" => true,
                "message" => "All trashed records restored successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No record found for restore."
            ]);
        }
    }
}
