<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


use function GuzzleHttp\Promise\all;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json([
            "data" => Company::all()
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
                "name" => "required|min:3|unique:companies",
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
        $res = Company::create([
            "name" => $request->name,
            "created_by" => $request->created_by,
        ]);

        if ($res) {
            return response()->json([
                "success" => true,
                "message" => "Company created successfully.",
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
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
        // dd($company);
        return response()->json([
            "success" => true,
            "message" => "Success",
            "attributes" => $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        // validate
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|min:3|unique:companies,name," . $company->id . ",id",
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
        $res = $company->update([
            "name" => $request->name,
            "created_by" => $request->created_by,
        ]);

        if ($res) {
            return response()->json([
                "success" => true,
                "message" => "Company updated successfully."
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
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json([
            "success" => true,
            "message" => "Company deleted successfully."
        ]);
    }

    public function trashed()
    {
        return response()->json([
            "success" => true,
            "attributes" => Company::onlyTrashed()->get()
        ]);
    }

    public function withTrashed()
    {
        $res = Company::withTrashed()->get();
        return response()->json([
            "success" => true,
            "trashed" => $res
        ]);
    }

    public function restore(Request $request)
    {
        $trashed = Company::onlyTrashed()->whereId($request->id);
        if ($trashed->count() > 0 && $trashed->restore()) {
            return response()->json([
                "success" => true,
                "message" => "Data restored successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Requested trashed item did not found."
            ]);
        }
    }

    public function restoreAll()
    {
        $trashed = Company::onlyTrashed();

        if ($trashed->count() > 0 && $trashed->restore()) {
            return response()->json([
                "success" => true,
                "message" => "All items restored successfully."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No item found for restore."
            ]);
        }
    }


    public function deleteForever(Request $request)
    {
        $item = Company::withTrashed()->whereId($request->id);
        $message = "";
        if ($item->count() > 0 && $item->forceDelete()) {
            return response()->json([
                "success" => true,
                "message" => "Data deleted forever."
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Requested data did not found."
            ]);
        }
    }
}
