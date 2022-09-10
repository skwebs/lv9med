<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PharmacyCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PharmacyCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json([
            "data" => PharmacyCompany::all()
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
                "name" => "required|min:3|unique:pharmacy_companies",
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
        $res = PharmacyCompany::create([
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
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PharmacyCompany  $pharmacyCompany
     * @return \Illuminate\Http\Response
     */
    public function show(PharmacyCompany $pharmacyCompany)
    {

        dd($pharmacyCompany->get());
        exit;
        return response()->json([
            "data" => $pharmacyCompany
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PharmacyCompany  $pharmacyCompany
     * @return \Illuminate\Http\Response
     */
    public function edit(PharmacyCompany $pharmacyCompany)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PharmacyCompany  $pharmacyCompany
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PharmacyCompany $pharmacyCompany)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PharmacyCompany  $pharmacyCompany
     * @return \Illuminate\Http\Response
     */
    public function destroy(PharmacyCompany $pharmacyCompany)
    {
        //
    }
}
