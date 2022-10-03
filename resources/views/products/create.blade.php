@extends('layouts.admin')
@section('content')
    <style>
        .text-1{
            text-align: right; font-size: 25px;
            font-weight: bold;
        }
    </style>
    <div class="panel panel-headline">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">Add Product</h3>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="card">
                    <div class="card-body pb-5">

                        @include('partials.success-error')

                        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- <input type="hidden" name="brand_id" value="{{ $brand->id }}"> --}}
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="" class="pb-2">Avatar image</label>
                                    <img src="{{ asset('dummy.png') }}" alt="" class="w-100 img-thumbnail">
                                    <div class="form-group mt-3">
                                        <input type="file" name="image" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group pb-4">
                                        <label for="">Name</label>
                                        <input type="text" name="name" class="form-control" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Description</label>
                                        <textarea id="editor1" name="description" cols="5" rows="5" class="form-control"
                                                  required=""></textarea>
                                    </div>
                                    <div class="form-group pt-3">
                                        <label for="">External ID</label>
                                        <input type="text" name="sku" class="form-control">
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h5><strong>Categorization</strong></h5>
                                        </div>
                                        <div class="col-md-3 border p-3">
                                            <h6 class="mb-3"><strong>Main Categories <span class="text-danger">*</span></strong>
                                            </h6>
                                            <ul class="list-unstyled">
                                                @foreach($categories as $category)
                                                    <li class="mb-2">
                                                        <label for="">
                                                            <input rel="{{ $category->name }}" type="radio"
                                                                   name="category_id" class="mainCategory"
                                                                   value="{{ $category->id }}"
                                                                   required=""> {{ $category->name }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="col-md-9" style="display: none" id="flowerId">
                                            <div class="row">
                                                <div class="col-md-6">
                                                   <p class="text-1">1 gram :</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" name="fp1" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="text-1">3.5 or 1/8oz :</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" name="fp2" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="text-1">7g or 1/4oz :</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" name="fp3" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="text-1">14 g or 1/2oz :</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" name="fp4" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="text-1">28g or  1oz :</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" name="fp5" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group pt-3" id="suggestPrice">
                                        <label for="">Suggested Price*</label>
                                        <input type="number" name="suggested_price" class="form-control" >
                                    </div>
                                    <div class="form-group pt-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5><strong>Featured Products</strong></h5>
                                                <p>Make this a featured product and select the position on your landing
                                                    page</p>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="">
                                                    <input type="checkbox" name="is_featured"> Featured?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group pt-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5><strong>Strain (optional)</strong></h5>
                                            </div>
                                            <div class="col-md-12">
                                                @if($strains->count() >0)
                                                    <select name="strain_id" class="select2 form-control">
                                                        <option value="">No Strain Selected</option>
                                                        @foreach($strains as $strain)
                                                            <option
                                                                value="{{ $strain->id }}">{{ $strain->name }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group pt-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5><strong>Genetics (optional)</strong></h5>
                                            </div>
                                            <div class="row">
                                                @if($genetics->count() >0)
                                                    @foreach($genetics as $genetic)
                                                        <div class="col-md-2 mb-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                       name="genetic_id" id="{{ $genetic->name }}"
                                                                       value="{{ $genetic->id }}">
                                                                <label class="form-check-label"
                                                                       for="{{ $genetic->name }}">
                                                                    {{ $genetic->name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group pt-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5><strong>Cannabinoids (optional)</strong></h5>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">THC %</label>
                                                        <input type="number" name="thc_percentage" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">CBD %</label>
                                                        <input type="number" name="cbd_percentage" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="form-group">
                                            <button class="btn btn-primary appointment-btn"
                                                    style="margin-left: 0px !important;border: 0px;">Add Product
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(".mainCategory").click(function () {
            var category_id = $(this).val();
            var maincat = $(this).attr('rel');
            console.log(category_id);
            if (category_id == "4"){
                $("#flowerId").show();
                $("#suggestPrice").hide();
            }else {
                $("#flowerId").hide();
                $("#suggestPrice").show();
            }
            {{--$("#typesubcategories").addClass('loader');--}}
            {{--$.ajax({--}}
            {{--    headers: {--}}
            {{--        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--    },--}}
            {{--    url: "{{ route('gettypesubcat') }}",--}}
            {{--    method: "POST",--}}
            {{--    data: {category_id: category_id},--}}
            {{--    success: function (data) {--}}
            {{--        var response = JSON.parse(data);--}}
            {{--        // console.log(data);--}}
            {{--        if (response.statuscode == 200) {--}}
            {{--            $("#typesubcategories").html(response.data);--}}
            {{--            $("#typesubcategories").removeClass('loader');--}}
            {{--            $(".selectedcats").html('');--}}
            {{--            $(".selectedcats").html(maincat + ', ');--}}
            {{--        } else {--}}
            {{--            $("#typesubcategories").removeClass('loader');--}}
            {{--            $("#typesubcategories").html("Something went wrong.");--}}
            {{--        }--}}
            {{--    }--}}
            {{--});--}}
        });
    </script>
@endsection

