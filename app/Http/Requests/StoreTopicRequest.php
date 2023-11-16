<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicRequest extends FormRequest
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
            'name' => 'required|unique:topic|string|min:1',
            'metakey' => 'required|min:1',
            'metadesc' => 'required|min:1',
            
        ];
    }

    public function messages()
    {
        $messages = [
            'required' => 'Bạn chưa điền vào đây'
        ];
        return [
            'name.required' => $messages['required'],
            'name.min' => 'Nhập ít nhất 5 ký tự',
            'name.string' => 'Tiêu đề phải là chuỗi chỉ chứa các ký tự chữ cái và số',
            'name.unique' => 'Tiêu đề này đã được sử dụng, vui lòng sử dụng một tiêu đề khác',
            'metakey.required' => $messages['required'],
            'metakey.min' => 'Nhập ít nhất 5 ký tự',
            'metadesc.required' => $messages['required'],
            'metadesc.min' => 'Nhập ít nhất 5 ký tự',
            
        ];
    }
}
