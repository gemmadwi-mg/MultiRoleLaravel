<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentLand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UserTransformer;

class AdminParentLandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ownerId)
    {
        $brands = ParentLand::where('owner_id', $ownerId)->get();

        if (empty($brands)) {
            throw new NotFoundHttpException('ParentLand does not exist');
        }

        return $brands;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userId)
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

        try {
            $parent_land = User::find($userId)->users()
                ->create([
                    'owner_id' => $request->owner_id,
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
            'message' => 'ParentLand created successfully',
            'id' => $parent_land->id
        ];

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$user = User::find($id)) {
            throw new NotFoundHttpException('User not found with id = ' . $id);
        }

        return $user;
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
        if (!$user = User::find($id)) {
            throw new NotFoundHttpException('User not found with id = ' . $id);
        }

        if (!empty($request->firstname)) {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string|min:3|max:30',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ]);
            }

            $user->userProfile()->updateOrCreate(['user_id' => $user->id], [
                'firstname' => $request->firstname,
            ]);
        }

        if (!empty($request->lastname)) {
            $validator = Validator::make($request->all(), [
                'lastname' => 'required|string|min:3|max:30',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ]);
            }

            $user->userProfile()->updateOrCreate(['user_id' => $user->id], [
                'lastname' => $request->lastname,
            ]);
        }

        $response = [
            'message' => 'User profile updated successfully',
            'id' => $user->id,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$user = User::find($id)) {
            throw new NotFoundHttpException('User not found');
        }

        try {
            $user->delete();
        } catch (HttpException $th) {
            throw $th;
        }

        return response()->json(['message' => 'User deleted successfully', 'id' => $id]);
    }

}
