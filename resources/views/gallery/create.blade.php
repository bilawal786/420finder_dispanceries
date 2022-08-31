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
                <button type="button" class="btn btn-success fileup-btn">
                    Select images
                    <input type="file" id="upload-3" multiple accept="image/*">
                </button>
                <div id="upload-3-queue" class="queue"></div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{asset('jquery.growl.js')}}"></script>
    <script src="{{asset('src/fileup.js')}}"></script>
    <script>

        $.fileup({
            url: 'https://github.com?file_upload=1',
            inputID: 'upload-3',
            queueID: 'upload-3-queue',
            autostart: true,
            onSelect: function(file) {
                $('#types .control-button').show();
            },
            onRemove: function(file, total) {
                if (file === '*' || total === 1) {
                    $('#types .control-button').hide();
                }
            },
            onSuccess: function(response, file_number, file) {
                $.growl.notice({ title: "Upload success!", message: file.name });
            },
            onError: function(event, file, file_number) {
                $.growl.error({ message: "Upload error!" });
            }
        });
        $.fileup({
            url: 'https://github.com?file_upload=1',
            inputID: 'upload-4',
            queueID: 'upload-4-queue',
            dropzoneID: 'upload-4-dropzone'
        })
            .select(function(file) {
                $('#dropzone .control-button').show();
            })
            .remove(function(file, total) {
                if (file === '*' || total === 1) {
                    $('#dropzone .control-button').hide();
                }
            })
            .success(function(response, file_number, file) {
                $.growl.notice({ title: "Upload success!", message: file.name });
            })
            .error(function(event, file, file_number) {
                $.growl.error({ message: "Upload error!" });
            })
            .dragEnter(function(event) {
                $(event.target).addClass('over');
            })
            .dragLeave(function(event) {
                $(event.target).removeClass('over');
            })
            .dragEnd(function(event) {
                $(event.target).removeClass('over');
            });

    </script>
@endpush
@push('styles')
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{asset('jquery.growl.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('src/fileup.css')}}" rel="stylesheet" type="text/css">
    <style>
        body { background-color:#fafafa; font-family:'Roboto';}
        h2 { margin:20px auto;}
        .container { margin:50px auto;}
        .dropzone {
            background-color: #ccc;
            border: 3px dashed #888;
            width: 350px;
            height: 150px;
            border-radius: 25px;
            font-size: 20px;
            color: #777;
            padding-top: 50px;
            text-align: center;
        }
        .dropzone.over {
            opacity: .7;
            border-style: solid;
        }
        #dropzone .dropzone {
            margin-top: 25px;
            padding-top: 60px;
        }
    </style>
@endpush
