<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
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
            'title' => 'required|string|min:5',
            'detail' => 'required|min:5',
            'metakey' => 'required|min:5',
            'metadesc' => 'required|min:5',
            'image' => 'image|mimes:png,jpg,jpeg|max:2048',  // Max file size is 2MB (2048KB)
           
        ];
    }

    public function messages()
    {
        $messages = [
            'required' => 'Bạn chưa điền vào đây'
        ];
        return [
            'title.required' => $messages['required'],
            'title.min' => 'Nhập ít nhất 5 ký tự',
            'title.string' => 'Tên phải là chuỗi chỉ chứa các ký tự chữ cái và số',
            'title.unique' => 'Tên đã được sử dụng, vui lòng sử dụng một tên khác',
            'detail.required' => $messages['required'],
            'detail.min' => 'Nhập ít nhất 5 ký tự',
            'metakey.required' => $messages['required'],
            'metakey.min' => 'Nhập ít nhất 5 ký tự',
            'metadesc.required' => $messages['required'],
            'metadesc.min' => 'Nhập ít nhất 5 ký tự',
            'image.image' => 'Vui lòng tải lên một tệp hình ảnh.',
            'image.mimes' => 'Vui lòng tải lên một tệp hình ảnh có phần mở rộng hợp lệ (png,jpg,jpeg).',
            'image.max' => 'Kích thước tệp tải lên không được vượt quá 2048KB (2MB).',

           
        ];
    }
}
