<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:brand|max:255|min:1|string',
            'metakey' => 'required|min:1',
            'metadesc' => 'required|min:1',
            'image' => 'image|mimes:png,jpg,jpeg,webp|max:2048'  // Max file size is 2MB (2048KB)
        ];
    }

    public function messages()
    {
        $messages = [
            'required' => 'Bạn chưa điền vào đây'
        ];
        return [
            'name.required' => $messages['required'],
            'name.min' => 'Nhập ít nhất :min ký tự',
            'name.string' => 'Tên phải là chuỗi chỉ chứa các ký tự chữ cái và số',
            'name.unique' => 'Tên đã được sử dụng, vui lòng sử dụng một tên khác',
            'metakey.required' => $messages['required'],
            'metakey.min' => 'Nhập ít nhất :min ký tự',
            'metadesc.required' => $messages['required'],
            'metadesc.min' => 'Nhập ít nhất :min ký tự',
            'image.image' => 'Vui lòng tải lên một tệp hình ảnh.',
            'image.mimes' => 'Vui lòng tải lên một tệp hình ảnh có phần mở rộng hợp lệ (png,jpg,jpeg).',
            'image.max' => 'Kích thước tệp tải lên không được vượt quá 2048KB (2MB).',
        ];
    }
}
