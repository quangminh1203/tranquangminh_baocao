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
            @if ($errors->has('image'))
                <div class="text-danger">
                    {{ $errors->first('image') }}
                </div>
            @endif
        </div>
    </div>
</div>
