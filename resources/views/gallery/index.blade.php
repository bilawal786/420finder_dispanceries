@extends('layouts.admin')

@section('content')

    <!-- OVERVIEW -->
    <div class="panel panel-headline">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">Gallery</h3>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">


                <div class="container">
                    <h2>Image Gallery</h2>
                    <p>The .thumbnail class can be used to display an image gallery.</p>
                    <p>The .caption class adds proper padding and a dark grey color to text inside thumbnails.</p>
                    <p>Click on the images to enlarge them.</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="thumbnail">
                                <a href="/w3images/lights.jpg" target="_blank">
                                    <img src="/w3images/lights.jpg" alt="Lights" style="width:100%">
                                    <div class="caption">
                                        <p>Lorem ipsum donec id elit non mi porta gravida at eget metus.</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail">
                                <a href="/w3images/nature.jpg" target="_blank">
                                    <img src="/w3images/nature.jpg" alt="Nature" style="width:100%">
                                    <div class="caption">
                                        <p>Lorem ipsum donec id elit non mi porta gravida at eget metus.</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail">
                                <a href="/w3images/fjords.jpg" target="_blank">
                                    <img src="/w3images/fjords.jpg" alt="Fjords" style="width:100%">
                                    <div class="caption">
                                        <p>Lorem ipsum donec id elit non mi porta gravida at eget metus.</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
