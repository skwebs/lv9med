<?php

namespace App\Http\Controllers\Api;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $med = Medicine::all();
        return response()->json([
            "success" => true,
            "attributes" => $med,
            "count" => $med->count()
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
        // validate
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|min:3|unique:medicines",
                "company" => "required|integer",
                "type" => "required|integer",
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

        //create company record
        $res = Medicine::create($request->only(["name", "company", "type", "created_by"]));

        if ($res) {
            return response()->json([
                "success" => true,
                "message" => "Medicine record created successfully.",
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $med = Medicine::find($id);
        if (!$med) {
            return response()->json([
                "success" => false,
                "message" => "Requested data not found."
            ]);
        }
        return response()->json([
            "success" => true,
            "attributes" => $med->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $med = Medicine::find($id);
        if (!$med) {
            return response()->json([
                "success" => false,
                "message" => "Requested data not found."
            ]);
        }

        // validate
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|min:3|unique:medicines,name," . $id . ",id",
                "company" => "required|integer",
                "type" => "required|integer",
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

        //create company record
        $res = $med->update($request->only(["name", "company", "type", "created_by"]));

        if ($res) {
            return response()->json([
                "success" => true,
                "message" => "Medicine record updated successfully."
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $med = Medicine::find($id);

        if ($med && $med->delete()) {
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
        $trashed = Medicine::onlyTrashed();
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

    public function restore($id)
    {
        $trashed = Medicine::onlyTrashed()->whereId($id);
        if ($trashed->count() > 0 && $trashed->restore()) {
            return response()->json([
                "success" => true,
                "message" => "Data restored successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Requested trashed record did not found."
            ]);
        }
    }

    public function restoreAll()
    {
        $trashed = Medicine::onlyTrashed();

        if ($trashed->count() > 0 && $trashed->restore()) {
            return response()->json([
                "success" => true,
                "message" => "All records restored successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No record found for restore."
            ]);
        }
    }
}
