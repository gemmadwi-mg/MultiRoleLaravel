<?php

namespace App\Http\Controllers;

use App\Models\ChilderLand;
use App\Models\ParentLand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChilderLandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parent_id = $request->input('parent_id');
        $child_query = ChilderLand::with(['parentland'])->get();
        $keyword = $request->input('keyword');

        $childs = $child_query;

        if ($parent_id) {
            $childs->whereHas('parentland', function ($query) use ($parent_id) {
                $query->where('parent_land_id', $parent_id);
            });
        }

        if ($keyword && $parent_id) {
            $result = ChilderLand::where('parent_id', '=', $parent_id)
                ->OrWhere('rental_retribution', 'LIKE', '%' . $keyword . '%')
                ->orWhere('utilization_engagement_type', 'LIKE', '%' . $keyword . '%')
                ->orWhere('utilization_engagement_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('allotment_of_use', 'LIKE', '%' . $keyword . '%')
                ->orWhere('coordinate', 'LIKE', '%' . $keyword . '%')
                ->orWhere('large', 'LIKE', '%' . $keyword . '%')
                ->orWhere('present_condition', 'LIKE', '%' . $keyword . '%')
                ->orWhere('validity_period_of', 'LIKE', '%' . $keyword . '%')
                ->orWhere('validity_period_until', 'LIKE', '%' . $keyword . '%')
                ->orWhere('engagement_number', 'LIKE', '%' . $keyword . '%')
                ->orWhere('engagement_date', 'LIKE', '%' . $keyword . '%')
                ->orWhere('description', 'LIKE', '%' . $keyword . '%')
                ->get();
            // if (count($result)) {
            //     return ResponseFormatter::responseSuccessWithData('Data ditemukan', $result);
            // } else {
            //     return ResponseFormatter::responseError('Data tidak ditemukan', 400);;
            // }
        }

        return $child_query;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_land_id' => 'required',
            'allotment_of_use' => 'required',
            'coordinate' => 'required',
            'large' => 'required|integer',
            'description' => 'required',
            'application_letter' => 'mimes:doc,docx,pdf,txt',
            'agreement_letter' => 'mimes:doc,docx,pdf,txt',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->errors()
            ]);
        }

        try {
            if ($request->hasFile('application_letter')) {
                $file_application = $request->file('application_letter')->store('public/documents/applicationletter');
            }
            if ($request->hasFile('agreement_letter')) {
                $file_agreement = $request->file('agreement_letter')->store('public/documents/agreementletter');
            }

            $childer_land = ChilderLand::create([
                'parent_land_id' => $request->parent_land_id,
                'rental_retribution' => $request->rental_retribution,
                'utilization_engagement_type' => $request->utilization_engagement_type,
                'utilization_engagement_name' => $request->utilization_engagement_name,
                'allotment_of_use' => $request->allotment_of_use,
                'coordinate' => $request->coordinate,
                'large' => $request->large,
                'validity_period_of' => $request->validity_period_of,
                'validity_period_until' => $request->validity_period_until,
                'engagement_number' => $request->engagement_number,
                'engagement_date' => $request->engagement_date,
                'description' => $request->description,
                'application_letter' => isset($file_application) ? $file_application : '',
                'agreement_letter' => isset($file_agreement) ? $file_agreement : '',
            ]);
        } catch (HttpException $th) {
            throw $th;
        }

        $response = [
            'message' => 'parent_land created successfully',
            'id' => $childer_land->id
        ];

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$user = auth()->user()) {
            throw new NotFoundHttpException('User not found');
        }

        $stores = ParentLand::where('owner_id', $user->id)->find($id);

        return $stores;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStoreRequest  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        if (!$user = auth()->user()) {
            throw new NotFoundHttpException('User not found');
        }

        $store = ParentLand::where('owner_id', $user->id)->find($id);

        if (!$store) {
            throw new NotFoundHttpException('parent_land not exists');
        }

        if (!empty($request->certificate_number)) {
            $validator = Validator::make($request->all(), [
                'certificate_number' => 'required|int'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $store->certificate_number = $request->certificate_number;
        }

        if (!empty($request->certificate_date)) {
            $validator = Validator::make($request->all(), [
                'certificate_date' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $store->certificate_date = $request->certificate_date;
        }

        if (!empty($request->item_name)) {
            $validator = Validator::make($request->all(), [
                'item_name' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $store->item_name = $request->item_name;
        }

        if (!empty($request->address)) {
            $validator = Validator::make($request->all(), [
                'address' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $store->address = $request->address;
        }

        if (!empty($request->large)) {
            $validator = Validator::make($request->all(), [
                'large' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $store->large = $request->large;
        }

        if (!empty($request->asset_value)) {
            $validator = Validator::make($request->all(), [
                'asset_value' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $store->asset_value = $request->asset_value;
        }



        if ($store->isDirty()) {

            $store->save();

            $response = [
                'message' => 'ParentLand updated successfully',
                'id' => $store->id
            ];

            return response()->json($response, 200);
        }

        return response()->json(['message' => 'Nothing to update'], 200);
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$user = auth()->user()) {
            throw new NotFoundHttpException('User not found');
        }

        $store = ParentLand::where('owner_id', $user->id)->find($id);

        if (!$store) {
            throw new NotFoundHttpException('Store does not exists');
        }

        try {
            $store->delete();
            $response = [
                'message' => 'ParentLand delete successfully',
                'id' => $store->id,
            ];

            return response()->json($response, 200);
        } catch (HttpException $th) {
            throw $th;
        }
    }
}
