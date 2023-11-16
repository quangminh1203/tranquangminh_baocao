                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Tên sản phẩm</label>
                                    <input name="name" id="name" type="text" class="form-control "
                                        value="{{ old('name') }}" placeholder="  ">
                                    @if ($errors->has('name'))
                                        <div class="text-danger">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>


                                <div class="mb-3">
                                    <label for="metakey">Từ khóa</label>
                                    <textarea name="metakey" id="metakey" cols="10" rows="2" class="form-control " placeholder=" ">{{ old('metakey') }}</textarea>
                                    @if ($errors->has('metakey'))
                                        <div class="text-danger">
                                            {{ $errors->first('metakey') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="category_id">Chọn danh mục</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">--chon danh mục--</option>
                                        {!! $html_category_id !!}
                                    </select>
                                    @if ($errors->has('category_id'))
                                        <div class="text-danger">
                                            {{ $errors->first('category_id') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="brand_id">Vị trí</label>
                                    <select name="brand_id" id="brand_id" class="form-control">
                                        <option value="">--chon thương hiệu--</option>
                                        {!! $html_brand_id !!}
                                    </select>
                                    @if ($errors->has('brand_id'))
                                        <div class="text-danger">
                                            {{ $errors->first('brand_id') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="price_buy">Giá bán</label>
                                    <input name="price_buy" id="price_buy" type="number" class="form-control "
                                        value="{{ old('price_buy') }}">
                                    @if ($errors->has('price_buy'))
                                        <div class="text-danger">
                                            {{ $errors->first('price_buy') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="status">Trạng thái</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Xuất bản
                                        </option>
                                        <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Chưa xuất bản
                                        </option>

                                    </select>
                                </div>
                            </div>

                        </div>
