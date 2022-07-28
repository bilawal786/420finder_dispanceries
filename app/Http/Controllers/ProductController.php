<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Deal;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Strain;

use App\Models\Genetic;

use App\Models\Category;
use App\Models\Favorite;
use App\Models\DealProduct;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\BrandProduct;

use App\Models\CategoryType;

use App\Models\DeliveryCart;
use Illuminate\Http\Request;
use App\Models\ProductRequest;
use App\Models\RecentlyViewed;
use App\Models\RetailerReview;
use App\Models\DispenseryProduct;
use App\Models\RetailerMenuOrder;
use App\Services\AuthorizeService;
use App\Models\BrandProductGallery;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Models\DispenseryProductGallery;


class ProductController extends Controller {

    public function index() {

        $products = DispenseryProduct::where('dispensery_id', session('business_id'))->latest()->get();

        $paid = RetailerMenuOrder::where('retailer_id', session('business_id'))->first();
        return view('products.index')
            ->with('products', $products)
            ->with('paid', $paid);

    }

    public function productrequests() {

        if(is_null($this->checkIfPaid())) {
            return $this->redirectToPayment();
        }

        $brands = Brand::where('status', 1)->select('id', 'name')->get();

        $requests = ProductRequest::where('retailer_id', session('business_id'))->get();

        return view('requestproducts.index')
            ->with('brands', $brands)
            ->with('requests', $requests);

    }

    public function getrproducts(Request $request) {

        $brand_id = $request->brand_id;

        $products = BrandProduct::where('brand_id', $brand_id)
            ->select('id', 'name')
            ->get();

        $data = '
                    <option value="">Select</option>
                ';

        foreach($products as $product) {

            $data .= '
                <option value="' . $product["id"] . '">' . $product["name"] . '</option>
            ';

        }

        echo $data;

    }

    public function submitproductrequest(Request $request) {

        $validated = $request->validate([
            'product_id' => 'required',
            'brand_id' => 'required'
        ]);

        $productIds = implode(", ", $request->product_id);

        $req = new ProductRequest;

        $req->retailer_id = session('business_id');

        $req->brand_id = $request->brand_id;

        $req->products = $productIds;

        $req->save();

        return redirect()->back()->with('info', 'Request Submitted.');

    }

    public function editproduct($id) {


        if(is_null($this->checkIfPaid())) {
            return $this->redirectToPayment();
        }

        if(is_null($this->checkIfRetailerProduct($id))) {
            return redirect()->route('products');
        }

        $product = DispenseryProduct::where('id', $id)->first();

        if($product->brand_product) {
            return back();
        }

        $gallery = DispenseryProductGallery::where('dispensery_product_id', $id)->get();

        $strains = Strain::all();
        $genetics = Genetic::all();

        return view('products.edit')
            ->with('product', $product)
            ->with('gallery', $gallery)
            ->with('strains', $strains)
            ->with('genetics', $genetics);

    }

    /*
    * GET CATEGORIES
    *
    */

    public function gettypesubcat(Request $request) {

        $category_id = $request->category_id;

        $types = CategoryType::where('category_id', $category_id)->get();

        $data = '

            <div class="row categoriesCols">
                ';
                foreach($types as $type) {
                    $data .='
                    <div class="col-md-3">
                        <h6 class="pb-2"><strong>' . $type->name . '</strong></h6>
                        <ul class="list-unstyled">';

                        $subcategories = SubCategory::where('type_id', $type->id)->where('parent_id', 0)->get();

                        foreach($subcategories as $subcat) {
                            $data .= '

                                <li class="mb-2"><label><input rel="' . $subcat->name . '" type="radio" class="childOfParentSC" name="type_' . $type->name . '" value="' . $subcat->id . '" required=""> ' . $subcat->name . '</label></li>

                            ';
                        }

                        $data .='</ul>
                    </div>';
                }
            $data .= "
                <script>

                    $('.childOfParentSC').on('click', function() {
                        var subcat_id = $(this).val();
                        var type_name = $(this).attr('rel');
                        var selected = $(this).attr('rel');
                        var main = $('.selectedcats').text();
                        $('#typesubcategories').addClass('loader');
                        $.ajax({
                            headers: {
                              'X-CSRF-TOKEN': '" . csrf_token() . "'
                            },
                            url:'" . route("getparentchildsc") . "',
                            method:'POST',
                            data:{subcat_id:subcat_id, type_name:type_name},
                            success:function(data) {
                                $('.subchild').remove();
                                $('.categoriesCols').append(data);

                                let str = main;
                                if(str.includes(selected)) {

                                } else {
                                    $('.selectedcats').html(main + selected + ', ');
                                }
                                $('#typesubcategories').removeClass('loader');
                            }
                        });

                    });

                </script>
            </div>
            ";

        $response = [
                        'statuscode'=> 200,
                        'data' => $data
                    ];

        echo json_encode($response);

    }

    /*
    *   GET PARENT CHILD
    *
    */
    public function getparentchildsc(Request $request) {

        $subcategories = SubCategory::where('parent_id', $request->subcat_id)->get();
        $data = '';

        if ($subcategories->count() > 0) {

            $data .='
                <div class="col-md-3 subchild">
                    <h6 class="pb-2"><strong>' . $request->type_name . ' Type</strong></h6>
                    <ul class="list-unstyled">';

                    foreach($subcategories as $subcat) {
                        $data .= '

                            <li class="mb-2"><label><input rel="' . $subcat->name . '" type="radio" class="childOfParentSC" name="type_' . $request->type_name . '" value="' . $subcat->id . '" required=""> ' . $subcat->name . '</label></li>

                        ';
                    }

                    $data .='</ul>
                </div>
                <script>
                    $(".childOfParentSC").on("click", function(){
                        var selected = $(this).attr("rel");
                        var main = $(".selectedcats").text();
                        let str = main;
                        if(str.includes(selected)) {
                            main.replace(selected+", ","");
                        } else {
                            $(".selectedcats").html(main + selected + ", ");
                        }
                    });
                </script>

                ';

            echo $data;

        }

    }

      /*
    *  CREATE PRODUCT
    *
    */

    public function create() {

        if(is_null($this->checkIfPaid())) {
            return $this->redirectToPayment();
        }

        $categories = Category::all();

        $genetics = Genetic::all();

        $strains = Strain::all();

        return view('products.create')
            ->with('categories', $categories)
            ->with('genetics', $genetics)
            ->with('strains', $strains);
    }

    /*
    *   STORE PRODUCT
    *
    */

    public function store(Request $request) {

        if(is_null($this->checkIfPaid())) {
            return $this->redirectToPayment();
        }

        $UUID = (string) Str::uuid();

        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image',
            'sku' => 'required',
            'suggested_price' => 'required',
            'category_id' => 'required',
            'galleryimages' => 'required'
        ]);

        $product = new DispenseryProduct;

        $product->dispensery_id = session('business_id');

        $product->name = $request->name;

        $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $product->description = $request->description;

        if($request->hasFile('image')) {

            $avatar = $request->file('image');
            $filename = time() . '.' . $avatar->GetClientOriginalExtension();

            $avatar_img = Image::make($avatar);
            $avatar_img->resize(274,274)->save(public_path('images/brands/products/' . $filename));

            $product->image = asset("images/brands/products/" . $filename);

        }

        $product->sku = $request->sku;
        $product->price = $request->suggested_price;
        $product->category_id = $request->category_id;

        // $idsArray = array();
        // $subcategoriesArray = array();
        // $search = "type_";
        // $search_length = strlen($search);

        // foreach ($_POST as $key => $value) {
        //     if (substr($key, 0, $search_length) == $search) {
        //         array_push($subcategoriesArray, substr($key, 5));
        //         array_push($idsArray, $value);
        //     }
        // }

        // $subcategoryids = implode(", ", $idsArray);
        $subcategoryids = NULL;

        // $subcategorynames = implode(", ", $subcategoriesArray);
        $subcategorynames = NULL;

        $product->subcategory_ids = $subcategoryids;
        $product->subcategory_names = $subcategorynames;

        $product->strain_id = $request->strain_id;
        $product->genetic_id = $request->genetic_id;
        $product->thc_percentage = $request->thc_percentage;
        $product->cbd_percentage = $request->cbd_percentage;
        $product->brand_product = 0;
        $product->brand_product_id = 0;
        $product->brand_id = 0;
        if ($request->is_featured == 'on') {

            $product->is_featured = 1;

        } else {

            $product->is_featured = 0;

        }

        // $product->status = $request->status;

        if ($product->save()) {

            if ($request->hasFile('galleryimages')) {

                foreach($request->file('galleryimages') as $image) {

                    $name=$image->getClientOriginalName();
                    $name = $UUID . '-' . $name;
                    $image->move(public_path('images/dispensery/products/gallery'), $name);

                    $dpg = new DispenseryProductGallery;

                    $dpg->dispensery_product_id = $product->id;
                    $dpg->image = asset("images/dispensery/products/gallery/" . $name);
                    $dpg->save();

                }

            }

            return redirect()->route('products')->with('info', 'Product Created.');

        } else {

            return redirect()->route('products')->with('error', 'Problem occured while creating product.');

        }

    }

    /*
    *   DELETE PRODUCT
    *
    */

    public function destroy($dispensaryProduct)
    {

        if(is_null($this->checkIfPaid())) {
            return $this->redirectToPayment();
        }

        // $dispensaryProduct = DispenseryProduct::find($dispensaryProduct)->delete();

        if(is_null($this->checkIfRetailerProduct($dispensaryProduct))) {
            return redirect()->back();
        }

        $dispensaryProductId = $dispensaryProduct;

        // Initially Get Delivery Product Gallery Records
        $dispensaryProductGallery = DispenseryProductGallery::where('dispensery_product_id', $dispensaryProduct)->get();

        $dispensaryProductDB = DispenseryProduct::find($dispensaryProduct);

        $oldImage = $dispensaryProductDB->image;

        $dispensaryProduct = $dispensaryProductDB->delete();

        if($dispensaryProduct) {

            // Orders
            Order::where('retailer_id', session('business_id'))->where('product_id', $dispensaryProductId)->delete();

            // Recently Viewed
            RecentlyViewed::where('type', 'dispensary')->where('product_id', $dispensaryProductId)->delete();

            // Deals
            $deals = Deal::where('retailer_id', session('business_id'))->pluck('id')->toArray();

            // Deal Products
            DealProduct::whereIn('deal_id', $deals)->where('product_id', $dispensaryProductId)->delete();

            // Delivery Carts
            DeliveryCart::where('business_id', session('business_id'))->where('product_id', $dispensaryProductId)->delete();

            // Dispensary Product Gallery
            if(!is_null($dispensaryProductGallery)) {
                foreach($dispensaryProductGallery as $image) {
                    $exp = explode('/', $image->image);
                    $expImage = $exp[count($exp) - 1];

                    if(File::exists(public_path('images/dispensery/products/gallery/'.$expImage)))
                    {
                        File::delete(public_path('images/dispensery/products/gallery/'.$expImage));
                    }
                }
            }

            DispenseryProductGallery::where('dispensery_product_id', $dispensaryProductId)->delete();

            // Favourites - Dispensary Product

            Favorite::where('type_id', $dispensaryProductId)->where('fav_type', 'Dispensary Product')->delete();

            // Retailer Reviews
            RetailerReview::where('dispensary_product_id', $dispensaryProductId)->delete();

            if($oldImage) {
                $exp = explode('/', $oldImage);
                $expImage = $exp[count($exp) - 1];
                if(File::exists(public_path('images/brands/products/'.$expImage)))
                {
                    File::delete(public_path('images/brands/products/'.$expImage));
                }
            }

            return back()->with('info', 'Product Deleted.');

        } else {
            return back()->with('error', 'Sorry Something Went Wrong.');
        }

    }

    public function removegalleryimage($id) {

        $gimage = DispenseryProductGallery::find($id);

        if(is_null($this->checkIfRetailerProduct($gimage->dispensery_product_id))) {
            return redirect()->back();
        }

        $oldImage = $gimage->image;

        $deleted = $gimage->delete();

        if($deleted) {

            if($oldImage) {
                $exp = explode('/', $oldImage);
                $expImage = $exp[count($exp) - 1];

                if(File::exists(public_path('images/dispensery/products/gallery/'.$expImage)))
                {
                    File::delete(public_path('images/dispensery/products/gallery/'.$expImage));
                }
            }

            return redirect()->back()->with('success', 'Gallery Image Deleted.');

        } else {

            return redirect()->back()->with('error', 'Sorry something went wrong.');
        }

    }

    public function updateproduct(Request $request) {

        if(is_null($this->checkIfPaid())) {
            return $this->redirectToPayment();
        }

        $UUID = (string) Str::uuid();

        $validated = $request->validate([
            'product_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'sku' => 'required',
            'status' => 'required'
        ]);

        if(is_null($this->checkIfRetailerProduct($request->product_id))) {
            return redirect()->back();
        }

        $product = DispenseryProduct::find($request->product_id);

        $product->name = $request->name;

        $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));

        $product->description = $request->description;

        $oldImage = NULL;

        if($request->hasFile('image')) {

            $avatar = $request->file('image');
            $filename = time() . '.' . $avatar->GetClientOriginalExtension();

            $avatar_img = Image::make($avatar);
            $avatar_img->resize(274,274)->save(public_path('images/brands/products/' . $filename));

            $oldImage = $product->image;
            $product->image = asset("images/brands/products/" . $filename);

        }

        $product->status = $request->status;
        $product->sku = $request->sku;
        $product->price = $request->price;
        // $product->off = $request->off;

        $product->off = 0;

        if ($request->is_featured == 'on') {

            $product->is_featured = 1;

        } else {

            $product->is_featured = 0;

        }

        $product->strain_id = $request->strain_id;
        $product->genetic_id = $request->genetic_id;
        $product->thc_percentage = $request->thc_percentage;
        $product->cbd_percentage = $request->cbd_percentage;

        if ($product->save()) {

            if(!is_null($oldImage)) {
                $exp = explode('/', $oldImage);
                $expImage = $exp[count($exp) - 1];

                if(File::exists(public_path('images/brands/products/'.$expImage)))
                {
                    File::delete(public_path('images/brands/products/'.$expImage));
                }
            }

            if ($request->hasFile('galleryimages')) {
                $dispensaryGalleryImgs = DispenseryProductGallery::where('dispensery_product_id', $request->product_id)->get();
                DispenseryProductGallery::where('dispensery_product_id', $request->product_id)->delete();

                foreach($request->file('galleryimages') as $image) {

                    $name=$image->getClientOriginalName();
                    $name = $UUID . '-' . $name;
                    $image->move(public_path('images/dispensery/products/gallery'), $name);

                    $bpg = new DispenseryProductGallery;

                    $bpg->dispensery_product_id = $product->id;
                    $bpg->image = asset("images/dispensery/products/gallery/" . $name);
                    $bpg->save();

                }

                // DELETE PREVIOUS GALLERY IMAGES
                if(!is_null($dispensaryGalleryImgs)) {
                    foreach($dispensaryGalleryImgs as $image) {
                        $exp = explode('/', $image->image);
                        $expImage = $exp[count($exp) - 1];

                        if(File::exists(public_path('images/dispensery/products/gallery/'.$expImage)))
                        {
                            File::delete(public_path('images/dispensery/products/gallery/'.$expImage));
                        }
                    }
                }

            }

            return redirect()->back()->with('info', 'Product Updated.');

        } else {

            return redirect()->back()->with('error', 'Problem occured while updated product.');

        }

    }


    /*
    *   STORE RETAILER PAYMENT
    *
    */
    public function storeRetailerPayment(Request $request) {

        $validated = request()->validate([
            'name_on_card' => 'required|min:2',
            'cvv' => 'required|numeric|digits:3',
            'card_number' => 'required|numeric|digits:16',
            'expiration_month' => 'required',
            'expiration_year' => 'required'
        ]);

        $createdId = NULL;
        try {
            $authorizePayment = resolve(AuthorizeService::class);
        $response = $authorizePayment->chargeCreditCard($validated);
        $tresponse = $response->getTransactionResponse();

        if ($tresponse != null && $tresponse->getMessages() != null) {

            $created = RetailerMenuOrder::create([
                'retailer_id' => session('business_id'),
                'amount' => '5.00',
                'name_on_card' => $validated['name_on_card'],
                'response_code' => $tresponse->getResponseCode(),
                'transaction_id' => $tresponse->getTransId(),
                'auth_id' => $tresponse->getAuthCode(),
                'message_code' => $tresponse->getMessages()[0]->getCode(),
                'quantity' => 1,
            ]);

            $createdId = $created->id;

            if($created) {

                session()->flash('success', 'Your payment has been successful');

                $products = DispenseryProduct::where('dispensery_id', session('business_id'))->get();

                return view('products.index')
                    ->with('products', $products)
                    ->with('paid', $created);

            } else {

                session()->flash('error', 'Sorry something went wrong');

                $products = DispenseryProduct::where('dispensery_id', session('business_id'))->get();

                return view('products.index')
                ->with('products', $products)
                ->with('paid', NULL);

            }

        } else {

            session()->flash('error', 'Sorry we couldn\'t process the payment');

            $products = DispenseryProduct::where('dispensery_id', session('business_id'))->get();

            return view('products.index')
                ->with('products', $products)
                ->with('paid', NULL);
        }
        } catch(Exception $e) {
            if(!is_null($createdId)) {
                RetailerMenuOrder::where('id', $createdId)->delete();
            }

            session()->flash('error', 'Sorry something went wrong');

            $products = DispenseryProduct::where('dispensery_id', session('business_id'))->get();

            return view('products.index')
                ->with('products', $products)
                ->with('paid', NULL);

        }

    }

    /*
    *   CHECK IF PAID
    *
    */

    private function checkIfPaid(){

        $retailerMenuOrder = RetailerMenuOrder::where('retailer_id', session('business_id'))->first();

        return $retailerMenuOrder;
    }

    /*
    *   REDIRECT TO PAYMENT
    *
    */
    private function redirectToPayment() {
        $products = DispenseryProduct::where('dispensery_id', session('business_id'))->get();
        $paid = RetailerMenuOrder::where('retailer_id', session('business_id'))->first();
        return redirect()->route('products')
            ->with('products', $products)
            ->with('paid', $paid);
    }

    /*
    *   CHECK IF RETAILER PRODUCT
    *
    */
    private function checkIfRetailerProduct($productId) {
        $businessId = session('business_id');

        $product = DispenseryProduct::where([
            ['id', $productId],
            ['dispensery_id', $businessId]
        ])->first();

        return $product;
    }

}
