<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

use App\Models\MsProduct;
use App\Models\MsCategory;
use DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = MsProduct::join('ms_categories', 'ms_categories.id', '=', 'ms_products.category_id')
            ->orderByRaw("name Desc")->select('ms_products.*');
            return DataTables::of($data)
            ->addColumn('category', function ($data) {
                return $data->categories->name;
            })
            ->addColumn('size', function ($data) {
                return $data->size." ".$data->uom;
            })
            ->addColumn('price', function ($data) {
                return "Rp".number_format((float)$data->price, 0);
            })
            ->addColumn('edit', function ($data) {
                $product_data = [
                    'id'             => $data->id,
                    'sku'            => $data->sku,
                    'category'       => $data->category_id,
                    'name'           => $data->name,
                    'size'           => $data->size,
                    'uom'            => $data->uom,
                    'description'    => $data->description,
                    'specification'  => $data->description,
                    'price'          => number_format((float)$data->price, 0),
                ];
            
                return "<button onclick='editModal(".json_encode($product_data).")' class='btn btn-sm btn-outline-primary' title='Edit'>Edit</button>";
            })
            ->addColumn('action', function ($data) {
                if ($data->status == "Active") {
                    return "<button data-color='#d26a5c' data-url=".route('product.change_status', $data->id)." data-status='Yes, Not activated' class='btn btn-sm btn-outline-success btn-square js-change-status' title='Change to Inactive'>Active</button>
                    <a href=".asset('/uploads/master/product/image')."/".$data->image." class='btn btn-sm btn-outline-warning popup-image' title='Image'>Image</a>";
                } else {
                    return "<button data-color='#79d25c' data-url=".route('product.change_status', $data->id)." data-status='Yes, Activate' class='btn btn-sm btn-outline-danger btn-square js-change-status' title='Change to Active'>Inactive</button>
                    <a href=".asset('/uploads/master/product/image')."/".$data->image." class='btn btn-sm btn-outline-warning popup-image' title='Image'>Image</a>";
                }
            })
            
            ->rawColumns(['action', 'size', 'edit'])
            ->make(true);
        }
        $data['category'] = MsCategory::orderBy('name', 'ASC')->get();
        return view('backend.master.product.index', $data);
    }

    public function create(Request $request)
    {
        $data=$request->all();
        $limit=[
            'category'      => 'required|numeric',
            'name'          => 'required|string',
            'sku'           => 'required|unique:ms_products',
            'size'          => 'required',
            'uom'           => 'required',
            'description'   => 'required',
            'specification' => 'required',
            'price'         => 'required',
            'image'         => 'max:2048|mimes:jpeg,jpg,bmp,png|nullable' // 2MB
        ];
        $validator = Validator($data, $limit);
        if ($validator->fails()){
            return response()->json([
                'success'   => false,
                'message'	=> "<i class='em em-email em-svg mr-2'></i>".$validator->errors()->first()
            ]);
        } else {
            try {
                DB::beginTransaction();

                if($request->file('image')) {
                    $file = $request->file('image');
                    $image  = str_replace(' ', '', $file->getClientOriginalName());
                    $file->move(public_path().'/uploads/master/product/image/', $image);
                } else {
                    $image = null;
                }
            
                MsProduct::create([
                    'category_id'      => $request->category,
                    'cat_name'         => MsCategory::where('id', $request->category)->first()->name,
                    'sku'              => $request->sku,
                    'name'             => $request->name,
                    'slug'             => Str::slug($request->name),
                    'size'             => $request->size,
                    'uom'              => $request->uom,
                    'description'      => $request->description,
                    'specification'    => $request->specification,
                    'price'            => str_replace(",", "", $request->price),
                    'image'            => $image
                ]);
                DB::commit();
                return response()->json([
                    'success' 		=> true,
                    'message'	    => '<i class="em em-email em-svg mr-2"></i>Product added successfully!'
                ]);
           
            } catch (Exception $e) {
                DB::rollback();
                return response()->json([
                    'success'   => false,
                    'message'   => $e->getMessage()
                ]);
            }
        }
    }

    public function change_status($id) 
    {
        $x = MsProduct::find($id);
        if($x->status == "Active") {
            $x->status = "Inactive";
            $x->save();
            return redirect()->back()
            ->with([
                'type'    => 'success',
                'message' => '<i class="em em-email em-svg mr-2"></i>Successfully change to inactive product '. $x->name 
            ]);
        } else {
            $x->status = "Active";
            $x->save();
            return redirect()->back()
            ->with([
                'type'    => 'success',
                'message' => '<i class="em em-email em-svg mr-2"></i>Successfully change to active product '. $x->name 
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $data=$request->all();
        $limit=[
            'category'      => 'required|numeric',
            'name'          => 'required|string',
            'sku'           => 'required|unique:ms_products,name,' . $id,
            'size'          => 'required',
            'uom'           => 'required',
            'description'   => 'required',
            'specification' => 'required',
            'price'         => 'required',
            'image'         => 'max:2048|mimes:jpeg,jpg,bmp,png|nullable' // 2MB
        ];
        $validator = Validator($data, $limit);
        if ($validator->fails()){
            return response()->json([
                'success'   => false,
                'message'	=> "<i class='em em-email em-svg mr-2'></i>".$validator->errors()->first()
            ]);
        } else {
            try {
                DB::beginTransaction();
                $db_product = MsProduct::where('id', $id)->first();
                if($request->file('image')) {
                    $file = $request->file('image');
                    $image  = str_replace(' ', '', $file->getClientOriginalName());
                    $file->move(public_path().'/uploads/master/product/image/', $image);
                    $db_product->image = $image;
                } 
                $db_product->category_id   = $request->category;
                $db_product->sku   = $request->sku;

                $db_product->cat_name   = MsCategory::where('id', $request->category)->first()->name;
                $db_product->name   = $request->name;
                $db_product->slug   = Str::slug($request->name);

                $db_product->size           = $request->size;
                $db_product->uom            = $request->uom;
                $db_product->description    = $request->description;
                $db_product->specification  = $request->specification;
                $db_product->price          = str_replace(",", "", $request->price);
                $db_product->save();

                DB::commit();
                return response()->json([
                    'success' 		=> true,
                    'message'	    => '<i class="em em-email em-svg mr-2"></i>Product updated successfully!'
                ]);
           
            } catch (Exception $e) {
                DB::rollback();
                return response()->json([
                    'success'   => false,
                    'message'   => $e->getMessage()
                ]);
            }
        }
    }
}
