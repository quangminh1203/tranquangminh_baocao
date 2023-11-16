                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="price">Giá nhập</label>
                                    <input name="price" id="price" type="number" class="form-control "
                                        value="{{ old('price', $product->price) }}" placeholder="Nhập giá gốc">
                                    @if ($errors->has('price'))
                                        <div class="text-danger">
                                            {{ $errors->first('price') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="qty">Số lượng nhập</label>
                                    <input name="qty" id="qty" type="number" class="form-control "
                                        value="{{ old('qty', $product->qty) }}" placeholder="Nhập giá khuyến mãi">
                                    @if ($errors->has('qty'))
                                        <div class="text-danger">
                                            {{ $errors->first('qty') }}
                                        </div>
                                    @endif
                                </div>

                            </div>

                        </div>
