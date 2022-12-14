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
        $company = Company::all();
        if (!$company->count() > 0) {
            return response()->json([
                "success" => false,
                "message" => "No record found."
            ]);
        }
        return response()->json([
            "success" => true,
            "attributes" => $company,
            "count" => $company->count()
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
        $trashed = Company::onlyTrashed();
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
        $allData = Company::withTrashed();
        return response()->json([
            "success" => true,
            "attributes" => $allData->get(),
            "count" => $allData->count()
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
                "message" => "Requested trashed record did not found."
            ]);
        }
    }

    public function restoreAll()
    {
        $trashed = Company::onlyTrashed();

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


    public function deleteForever(Request $request)
    {
        $company = Company::withTrashed()->whereId($request->id);
        $message = "";
        if ($company->count() > 0 && $company->forceDelete()) {
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
