                        <div class="row">
                            <div class="col-md-12">

                                <div class="mb-3">
                                    <label for="metadesc">Mô tả</label>
                                    <textarea name="metadesc" id="metadesc" cols="10" rows="2" class="form-control "
                                        placeholder="vd: ">{{ old('metadesc') }}</textarea>
                                    @if ($errors->has('metadesc'))
                                        <div class="text-danger">
                                            {{ $errors->first('metadesc') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="detail">Chi tiết</label>
                                    <textarea name="detail" id="detail" cols="10" rows="2" class="form-control "
                                        placeholder="vd: ">{{ old('detail') }}</textarea>
                                    @if ($errors->has('detail'))
                                        <div class="text-danger">
                                            {{ $errors->first('detail') }}
                                        </div>
                                    @endif
                                </div>

                            </div>

                        </div>
