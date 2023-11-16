                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="#">Tên thuộc tính</label>
                                    <input name="#" id="#" type="text" class="form-control "
                                        value="{{ old('name') }}" placeholder="  ">
                                    @if ($errors->has('name'))
                                        <div class="text-danger">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="description">Mô tả</label>
                                    <textarea name="description" id="description" cols="10" rows="2" class="form-control "
                                        placeholder="vd: là một thể loại văn xuôi có hư cấu, thông qua nhân vật, hoàn cảnh, sự việc để phản ánh bức tranh xã hội">{{ old('description') }}</textarea>
                                    @if ($errors->has('description'))
                                        <div class="text-danger">
                                            {{ $errors->first('description') }}
                                        </div>
                                    @endif
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="#">Giá trị</label>
                                    <input name="#" id="#" type="text" class="form-control "
                                        value="{{ old('name') }}" placeholder="  ">
                                    @if ($errors->has('name'))
                                        <div class="text-danger">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="description">Thứ tự</label>
                                    <textarea name="description" id="description" cols="10" rows="2" class="form-control "
                                        placeholder="vd: là một thể loại văn xuôi có hư cấu, thông qua nhân vật, hoàn cảnh, sự việc để phản ánh bức tranh xã hội">{{ old('description') }}</textarea>
                                    @if ($errors->has('description'))
                                        <div class="text-danger">
                                            {{ $errors->first('description') }}
                                        </div>
                                    @endif
                                </div>

                            </div>

                        </div>
