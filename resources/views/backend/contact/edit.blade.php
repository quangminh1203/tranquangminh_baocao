@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

@endsection
@section('content')
    <section class="content-wrapper">
        <div class="col-md-12">
            <div class="card card-primary card-outline contact-data">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ route('contact.update', ['contact' => $contact->id]) }}" id="form-update" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <input class="form-control" placeholder="To:" value="To: {{ $contact->user->name }}" disabled>
                        </div>

                        <div class="form-group">

                            @if ($contact->status == 1)
                                <textarea disabled=true name="reply_content" id="reply_content" cols="10" rows="2"
                                    class="form-control reply_content " placeholder="vd: ">{{ old('reply_content', $contact->reply_content) }}</textarea>
                            @else
                                <textarea name="reply_content" id="reply_content" cols="10" rows="2" class="form-control reply_content "
                                    placeholder="vd: ">{{ old('reply_content', $contact->reply_content) }}</textarea>
                            @endif
                            @if ($errors->has('reply_content'))
                                <div class="text-danger">
                                    {{ $errors->first('reply_content') }}
                                </div>
                            @endif
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="float-right">
                                @if ($contact->status == 2)
                                    <button type="submit" class="btn btn-primary btnSend"><i class="far fa-envelope"></i>
                                        Send</button>
                                @endif

                            </div>
                            <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Discard</button>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    {{-- </form> --}}
            </div>
        </div>
    </section>
@endsection
@section('footer')
    <script>
        CKEDITOR.replace('reply_content')
    </script>

    {{-- <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(".btnSend").click(function(e) {
                
                e.preventDefault();

                // var reply_content = CKEDITOR.instances.reply_content.getData();
                // alert(reply_content);
                $.ajax({
                    method: 'put',
                    url: "{{ route('contact.update', ['contact' => $contact->id]) }}",
                    // data: {
                    //     'reply_content': reply_content,
                    // },

                    success: function(response) {

                    }
                });
            })
            $("#form-update").submit(function(e) {
                
                e.preventDefault();

                var reply_content = CKEDITOR.instances.reply_content.getData();
                // alert(reply_content);
                $.ajax({
                    method: 'put',
                    url: "{{ route('contact.update', ['contact' => $contact->id]) }}",
                    // data: {
                    //     'reply_content': reply_content,
                    // },

                    success: function(response) {

                    }
                });
            })
        });
    </script> --}}
@endsection
