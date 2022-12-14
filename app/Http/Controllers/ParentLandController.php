<?php

namespace App\Http\Controllers;

use App\Models\ParentLand;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ParentLandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$user = auth()->user()) {
            throw new NotFoundHttpException('User not found');
        }

        $stores = ParentLand::where('owner_id', $user->id)->get();

        return $stores;
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
            'certificate_number' => 'required|integer',
            'certificate_date' => 'required',
            'item_name' => 'required',
            'address' => 'required',
            'large' => 'required',
            'asset_value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->errors()
            ]);
        }

        if (!$user = auth()->user()) {
            throw new NotFoundHttpException('User not found');
        }

        try {
            $parent_land = $user->parentLands()
                ->create([
                    'certificate_number' => $request->certificate_number,
                    'certificate_date' => $request->certificate_date,
                    'item_name' => $request->item_name,
                    'address' => $request->address,
                    'large' => $request->large,
                    'asset_value' => $request->asset_value,
                ]);
        } catch (HttpException $th) {
            throw $th;
        }

        $response = [
            'message' => 'parent_land created successfully',
            'id' => $parent_land->id
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
