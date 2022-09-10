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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                "response_code" => 0,
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
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
        //
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
        $res = Company::onlyTrashed()->get();
        return response()->json([
            "status" => true,
            "trashed" => $res
        ]);
    }

    public function withTrashed()
    {
        // $com = new Company();
        // if ($com->withTrashed()) {
        //     return response()->json(["message" => "found deleted data."]);
        // }
        $res = Company::withTrashed()->get();
        return response()->json([
            "status" => true,
            "trashed" => $res
        ]);
    }

    public function restore(Request $request)
    {
        $res = Company::withTrashed()
            ->whereId($request->id)
            ->restore();
        return response()->json([
            "status" => true,
            "trashed" => $res
        ]);
    }

    public function restoreAll()
    {
        $res = Company::withTrashed();

        $r = $res->restore();
        return response()->json([
            "status" => true,
            "response" => $r
        ]);
    }

    public function deleteForever(Request $request)
    {
    }
}
