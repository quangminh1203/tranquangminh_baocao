@extends('layouts.admin')
@section('title', $title ?? 'trang quản lý')
@section('header')

@endsection
@section('content')
    <section class="content-wrapper">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>

                    <div class="card-tools">
                        <a href="{{ route('contact.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-circle-left"></i>
                        </a>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="mailbox-read-info">
                        <h5>{{ $contact->title }}</h5>
                        <h6>From: {{ $contact->user->name }}
                            <span
                                class="mailbox-read-time float-right">{{ $contact->created_at->toDayDateTimeString() }}</span>
                        </h6>
                    </div>
                    <!-- /.mailbox-read-info -->
                    <div class="mailbox-controls with-border text-center">
                        <div class="btn-group">
                            <a href="{{ route('contact.delete', ['contact' => $contact->id]) }}" type="button"
                                class="btn btn-default btn-sm" data-container="body" title="Delete">
                                <i class="far fa-trash-alt"></i>
                            </a>
                            <button type="button" class="btn btn-default btn-sm" data-container="body" title="Reply">
                                <i class="fas fa-reply"></i>
                            </button>

                        </div>
                        <!-- /.btn-group -->
                        <button type="button" class="btn btn-default btn-sm" title="Print">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                    <!-- /.mailbox-controls -->
                    <div class="mailbox-read-message">
                        <p>{!! $contact->content !!}</p>


                    </div>
                    <!-- /.mailbox-read-message -->
                </div>
                <!-- /.card-body -->

                <!-- /.card-footer -->
                <div class="card-footer">
                    <div class="float-right">
                        <button type="button" class="btn btn-default"><i class="fas fa-reply"></i>Reply</button>

                    </div>
                    <a href="{{ route('contact.delete', ['contact' => $contact->id]) }}" type="button"
                        class="btn btn-default"><i class="far fa-trash-alt"></i> Delete</a>
                    <button type="button" class="btn btn-default"><i class="fas fa-print"></i> Print</button>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
    </section>
@endsection
@section('footer')

@endsection
