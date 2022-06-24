<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {

     $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin');
        return Product::latest()->paginate(10);

    }

    /**
     * Display a listing of the resource.

     *
     * @return \Illuminate\Http\Response
     */

    public function dashboardInfo(){
        $user_id = auth()->user()->id;
        $user = User::find($user_id)->products()->latest()->paginate(10);
        $products = Product::all();
        $allUsers = User::all();
        return [
            'products' => $products,
            'users' => $allUsers ,
            'userProducts'=> $user
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'price' => 'required|integer|min:10',
            'description' => 'required|string|max:500'

        ]);
        //Get authenticated user
        $user = auth('api')->user();
        //Get first two char
        $description = substr($request->description, 0,2);
        $name =  substr($request->name, 0,2);
        $price =  substr($request->price, 0,2);
        //Join to make string
        $sku = $name."-".$description."-".$price;
        //check  user is admin
        $this->authorize('isAdmin');
       return Product::create([
            'name' => $request['name'],
            'user_id' => $user->id,
            'sku' => strtoupper($sku),
            'price' => $request['price'],
            'description' => $request['description'],
        ]);
        //return['message'=>'Product created successfully.'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $product = Product::findOrFail($id);
         $request->validate([
            'name' => 'required|string|max:191',
            'price' => 'required|numeric|min:10',
            'description' => 'required|string|max:500'

        ]);
        //Get authenticated user
        $user = auth('api')->user();
        //Get first two char
        $description = substr($request->description, 0,2);
        $name =  substr($request->name, 0,2);
        $price =  substr($request->price, 0,2);
        //Join string
        $sku = $name."-".$description."-".$price;
        //check  user is admin
        $this->authorize('isAdmin');
        //check product belongs to logged in user
        //Get logged in user_id
        $user_id = $request->user_id;
        if($user->id !== $user_id){
            return ['message' => 'Not authorized.'];
        }
        $product->update([
            'name' => $request['name'],
            'user_id' => $user->id,
            'sku' => strtoupper($sku),
            'price' => $request['price'],
            'description' => $request['description'],
        ]);
        return['message' => 'Product update successfully.'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $product = Product::findOrFail($id);
        $product->delete();
        return['message' => 'Product deleted successfully.'];
    }
}