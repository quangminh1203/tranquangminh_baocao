<div class="d-flex justify-content-center row">
    <div class="col-md-12">
        @if (Auth::guard('users')->check())
            <form action="{{ route('comment.store') }}" method="POST" class="my-3">
                @csrf
                @if ($type == 'product')
                    <input type="hidden" name="table_id" value="{{ $product->id }}">
                @else
                    <input type="hidden" name="table_id" value="{{ $post->id }}">
                @endif
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="mt-2 row d-flex justify-content-between">
                    <h3 class="col-md-6 col-6 m-0">New Comment</h3>
                    <button class="btn btn-outline-primary btn-sm shadow-none col-md-1 col-2 me-3" type="submit"
                        name="NEW">Submit</button>
                </div>
                <div class="d-flex flex-row align-items-start my-2">
                    @if (Auth::guard('users')->check())
                        <img class="rounded-circle me-2"
                            src="{{ asset('images/user/' . Auth::guard('users')->user()->image) }}" width="40">
                    @else
                        <img class="rounded-circle me-2"
                            src="{{ asset('images/user/' . Auth::guard('users')->user()->image) }}" width="40">
                    @endif
                    <textarea class="form-control ml-1 shadow-none textarea" name="body"></textarea>

                </div>
                @if ($errors->has('body'))
                    <div class="text-danger">
                        {{ $errors->first('body') }}
                    </div>
                @endif
            </form>
        @else
            <h3 class="col-md-12 col-12 m-0  text-center my-3 border-bottom"><a class="text-danger" href="{{ route('site.getlogin') }}">Vui lòng đăng
                    nhập để bình luận
                    và trả lời.</a></h3>
        @endif
        <h3>{{ count($list_comment) }} Comments</h3>
        @if (count($list_comment) > 0)
            @foreach ($list_comment as $item)
                <div>

                    <div class="d-flex flex-column comment comment-section ">
                        <div class="bg-white p-2">
                            <div class="d-flex flex-row user-info">
                                <img class="rounded-circle me-2" src="{{ asset('images/user/' . $item->user->image) }}"
                                    width="40">
                                <div class="d-flex flex-column justify-content-start ml-2">
                                    <span class="d-block font-weight-bold name">{{ $item->user->name }}</span>
                                    <span class="date text-black-50">
                                        {{ $item->created_at->format('H:i:s d-m-Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="comment-text">{{ $item->body }}</p>
                            </div>
                        </div>

                        <div class="bg-white">
                            <div class="d-flex flex-row fs-12">
                                <div class="like p-2 cursor"><i class="fa fa-thumbs-o-up"></i><span
                                        class="ml-1">Like</span>
                                </div>
                                <div class="like p-2 cursor"><i class="fa fa-commenting-o"></i><span
                                        class="ml-1 show-replies">Reply</span>
                                </div>

                            </div>
                        </div>

                        <form action="{{ route('comment.reply') }}" method="POST" class="bg-light p-2 reply-box"
                            dataset="first-comment">

                            @csrf
                            @if ($errors->has('body1'))
                                <div class="text-danger">
                                    {{ $errors->first('body1') }}
                                </div>
                            @endif
                            <input type="hidden" name="product_id" value="{{ $item->table_id }}">
                            <input type="hidden" name="parent_id" value="{{ $item->id }}">
                            <input type="hidden" name="reply_id" value="{{ $item->user_id }}">
                            <input type="hidden" name="type" value="{{ $item->type }}">
                            <div class="d-flex flex-row align-items-start"><img class="rounded-circle me-2"
                                    src="https://i.imgur.com/RpzrMR2.jpg" width="40">
                                <textarea class="form-control ml-1 shadow-none textarea" name="body1"></textarea>

                            </div>

                            <div class="mt-2 float-end">
                                <button class="btn btn-primary btn-sm shadow-none" type="submit"
                                    name="reply_2">Comment</button>
                                <button class="btn btn-outline-primary btn-sm ml-1 shadow-none"
                                    type="submit">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <x-main-comment :comment="$item" />
                </div>
            @endforeach


        @endif
        <div class="pt-5 ">
            {{ $list_comment->links('pagination::bootstrap-5') }}
        </div>

        {{-- <div class="d-flex flex-column comment comment-reply-2 ">
            <div class="bg-white p-2">
                <div class="d-flex flex-row user-info">
                    <img class="rounded-circle me-2" src="https://i.imgur.com/RpzrMR2.jpg" width="40">
                    <div class="d-flex flex-column justify-content-start ml-2"><span
                            class="d-block font-weight-bold name">Marry Andrews</span><span
                            class="date text-black-50">Shared publicly - Jan 2020</span></div>
                </div>
                <div class="mt-2">
                    <p class="comment-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                        exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
            </div>
            <div class="bg-white ">
                <div class="d-flex flex-row fs-12">
                    <div class="like p-2 cursor"><i class="fa fa-thumbs-o-up"></i><span class="ml-1">Like</span>
                    </div>
                    <div class="like p-2 cursor"><i class="fa fa-commenting-o"></i><span
                            class="ml-1 show-replies">Reply</span>
                    </div>

                </div>
            </div>
            <div class="bg-light p-2 reply-box">
                <div class="d-flex flex-row align-items-start"><img class="rounded-circle me-2"
                        src="https://i.imgur.com/RpzrMR2.jpg" width="40">
                    <textarea class="form-control ml-1 shadow-none textarea"></textarea>
                </div>
                <div class="mt-2 float-end">
                    <button class="btn btn-primary btn-sm shadow-none" type="button">Comment</button>
                    <button class="btn btn-outline-primary btn-sm ml-1 shadow-none" type="button">Cancel</button>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column comment comment-reply">
            <div class="bg-white p-2">
                <div class="d-flex flex-row user-info">
                    <img class="rounded-circle me-2" src="https://i.imgur.com/RpzrMR2.jpg" width="40">
                    <div class="d-flex flex-column justify-content-start ml-2"><span
                            class="d-block font-weight-bold name">Marry Andrews</span><span
                            class="date text-black-50">Shared publicly - Jan 2020</span></div>
                </div>
                <div class="mt-2">
                    <p class="comment-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                        exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
            </div>
            <div class="bg-white">
                <div class="d-flex flex-row fs-12">
                    <div class="like p-2 cursor"><i class="fa fa-thumbs-o-up"></i><span class="ml-1">Like</span>
                    </div>
                    <div class="like p-2 cursor"><i class="fa fa-commenting-o"></i><span
                            class="ml-1 show-replies">Reply</span>
                    </div>

                </div>
            </div>
            <div class="bg-light p-2 reply-box">
                <div class="d-flex flex-row align-items-start"><img class="rounded-circle me-2"
                        src="https://i.imgur.com/RpzrMR2.jpg" width="40">
                    <textarea class="form-control ml-1 shadow-none textarea"></textarea>
                </div>
                <div class="mt-2 float-end">
                    <button class="btn btn-primary btn-sm shadow-none" type="button">Comment</button>
                    <button class="btn btn-outline-primary btn-sm ml-1 shadow-none" type="button">Cancel</button>
                </div>
            </div>
        </div> --}}

    </div>
</div>
