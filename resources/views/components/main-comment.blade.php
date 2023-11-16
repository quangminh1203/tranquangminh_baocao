 @if ($checksub)

     @foreach ($list_comment_1 as $item1)
         <div class="d-flex flex-column comment comment-reply">
             <div class="bg-white p-2">
                 <div class="d-flex flex-row user-info">
                     <img class="rounded-circle me-2" src="{{ asset('images/user/' . $item1->user->image) }}"
                         width="40">
                     <div class="d-flex flex-column justify-content-start ml-2">
                         <span class="d-block font-weight-bold name">{{ $item1->user->name }}</span>
                         <span class="date text-black-50">
                             {{ ($item1->created_at)->format('H:i:s d-m-Y') }}
                         </span>
                     </div>
                 </div>
                 <div class="mt-2">

                     <span class="comment-text user-comment"><a class="text-primary "
                             href="#">{!! '@' . $item1->repliedBy->name !!} </a>{{ $item1->body }}</span>
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

             <form action="{{ route('comment.replys') }}" method="POST" class="bg-light p-2 reply-box"
                 dataset="first-comment">

                 @csrf
                 <input type="hidden" name="product_id" value="{{ $item1->table_id }}">
                 <input type="hidden" name="parent_id" value="{{ $item1->parent_id }}">
                 <input type="hidden" name="reply_id" value="{{ $item1->user_id }}">
                 <input type="hidden" name="type" value="{{ $item1->type }}">
                 @if ($errors->has('body_1'))
                     <div class="text-danger">
                         {{ $errors->first('body_1') }}
                     </div>
                 @endif
                 <div class="d-flex flex-row align-items-start"><img class="rounded-circle me-2"
                         src="https://i.imgur.com/RpzrMR2.jpg" width="40">
                     <textarea class="form-control ml-1 shadow-none textarea" name="body_1"></textarea>
                 </div>

                 <div class="mt-2 float-end">
                     <button class="btn btn-primary btn-sm shadow-none" type="submit" name="replys">Comment</button>
                     <button class="btn btn-outline-primary btn-sm ml-1 shadow-none" type="submit">Cancel</button>
                 </div>
             </form>
         </div>
     @endforeach

 @endif
