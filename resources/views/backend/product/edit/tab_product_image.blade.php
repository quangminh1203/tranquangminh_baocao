                        {{-- <div class="row">
                            <div class="col-md-12 ">
                                <div class="mb-3">
                                    <label for="image">Hình ảnh</label>
                                    <input name="image[]" id="image" type="file" multiple
                                        onchange="previewFile(this); " class="form-control btn-sm image-preview">
                                    @if ($product->image)
                                        <img id="previewImg" class="mt-1" width="30%"
                                            src="{{ asset('images/product/' . $product->image) }}" alt="">
                                    @else
                                        <img id="previewImg" class="mt-1" width="30%"
                                            src="{{ asset('images/No-Image-Placeholder.svg.png') }}" alt="">
                                    @endif
                                    @if ($errors->has('image.*'))
                                        <div class="text-danger">
                                            {{ $errors->first('image.*') }}
                                        </div>
                                    @endif

                                </div>

                            </div>
                        </div> --}}

                        <div class="form_images">
                            <div class="card">
                                <div class="top">
                                    <p>Kéo và thả để thêm ảnh</p>


                                </div>
                                <div class="drag-area">
                                    <span class="visible">
                                        Kéo và thả ảnh vảo đây hoặc
                                        <span class="select" role="button">Thêm</span>
                                    </span>
                                    <span class="on-drop">Thả ra</span>
                                    <input name="image[]" type="file" class="file" multiple />
                                </div>

                                <div class="container">
                                    for
                                    @if ($errors->has('image'))
                                        <div class="text-danger">
                                            {{ $errors->first('image') }}
                                        </div>
                                    @endif
                                </div>
                               
                            </div>
                        </div>
