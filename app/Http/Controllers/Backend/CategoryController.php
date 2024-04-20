<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

use App\Models\MsCategory;
use App\Models\MsProduct;
use Carbon\Carbon;
use DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = MsCategory::orderByRaw("name Desc");
            return DataTables::of($data)
                ->addColumn('updated_at', function ($data) {
                    return Carbon::parse($data->updated_at)->formatLocalized("%B, %d %Y");
                })
                ->addColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->formatLocalized("%B, %d %Y");
                })
                ->addColumn('edit', function ($data) {
                    $category_data = [
                        'id'   => $data->id,
                        'name' => $data->name
                    ];
                    return "<button onclick='editModal(".json_encode($category_data).")' class='btn btn-sm btn-outline-primary' title='Edit'>Edit</button>
                    <button data-url=\"" . route('delete', $data->id) . "\" class=\"btn btn-sm btn-outline-danger btn-square delete-btn\" title=\"Delete\"><i class=\"fa fa-trash\"></i></button>";
                })
                ->addColumn('action', function ($data) {
                    if ($data->status == "Active") {
                        $buttons = "
                            <button data-color='#d26a5c' data-url=" . route('category.change_status', $data->id) . " data-status='Yes, Not activated' class='btn btn-sm btn-outline-success btn-square js-change-status' title='Change to Inactive'>Active</button>
                        ";
                    } else {
                        $buttons = "
                            <button data-color='#79d25c' data-url=" . route('category.change_status', $data->id) . " data-status='Yes, Activate' class='btn btn-sm btn-outline-danger btn-square js-change-status' title='Change to Active'>Inactive</button>
                        ";
                    }
                
                    // Add Image link
                    $buttons .= "
                        <a href=" . asset('/uploads/master/category/image') . "/" . $data->image . " class='btn btn-sm btn-outline-warning popup-image' title='Image'>Image</a>
                    ";
                
                    return $buttons;
                })
                
                ->rawColumns(['edit', 'action', 'updated_at'])
                ->make(true);
        }
        return view('backend.master.category.index');
    }
    public function delete($id)
    {
        $brand = MsCategory::findOrFail($id);
        MsProduct::where('category_id', $brand->id)->delete();
        $brand->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }  
    public function create(Request $request)
    {
        $data=$request->all();
        $limit=[
            'name'      => 'required|unique:ms_categories|string',
            'image'     => 'max:2048|mimes:jpeg,jpg,bmp,png|nullable' // 2MB
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
                    $file->move(public_path().'/uploads/master/category/image/', $image);
                } else {
                    $image = null;
                }
            
                MsCategory::create([
                    'name'              => $request->name,
                    'slug'              => Str::slug($request->name),
                    'image'             => $image
                ]);
                DB::commit();
                return response()->json([
                    'success' 		=> true,
                    'message'	    => '<i class="em em-email em-svg mr-2"></i>Category added successfully!'
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
        $x = MsCategory::find($id);
        if($x->status == "Active") {
            $x->status = "Inactive";
            $x->save();
            return redirect()->back()
            ->with([
                'type'    => 'success',
                'message' => '<i class="em em-email em-svg mr-2"></i>Successfully change to inactive category '. $x->name 
            ]);
        } else {
            $x->status = "Active";
            $x->save();
            return redirect()->back()
            ->with([
                'type'    => 'success',
                'message' => '<i class="em em-email em-svg mr-2"></i>Successfully change to active category '. $x->name 
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $data=$request->all();
        $limit=[
            'name'      => 'required|string|unique:ms_categories,name,' . $id,
            'image'     => 'max:2048|mimes:jpeg,jpg,bmp,png|nullable' // 2MB
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
                $db_category = MsCategory::where('id', $id)->first();
                if($request->file('image')) {
                    $file = $request->file('image');
                    $image  = str_replace(' ', '', $file->getClientOriginalName());
                    $file->move(public_path().'/uploads/master/category/image/', $image);
                    $db_category->image = $image;
                } 
                $db_category->name = $request->name;
                $db_category->slug = Str::slug($request->name);
                $db_category->save();

                DB::commit();
                return response()->json([
                    'success' 		=> true,
                    'message'	    => '<i class="em em-email em-svg mr-2"></i>Category updated successfully!'
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
