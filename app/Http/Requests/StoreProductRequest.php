<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|max:255|min:3|string',
            'category_id' => 'required',
            'brand_id' => 'required',
            'metakey' => 'required|min:5',
            'detail' => 'required|min:5|max:10000',
            'metadesc' => 'required|min:5|max:10000',
            'image' => 'required|array',
            'image.*' => 'image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            'price_buy' => 'required|numeric|digits_between:1,10',
            'price' => 'required|numeric|digits_between:1,10',
            'qty' => 'required|numeric|between:1,1000',
            'price_sale' => 'nullable|required_with:date_begin,date_end|numeric|min:1000|lt:price',
            'date_begin' => 'nullable|required_with:price_sale|date',
            'date_end' => 'nullable|required_with:price_sale,date_begin|date|after:date_begin',
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
            'name.string' => 'Tên phải là chuỗi chỉ chứa các ký tự chữ cái và số',
            

            'category_id.required'=> 'Vui lòng chọn một giá trị trong danh sách.',
            'brand_id.required'=> 'Vui lòng chọn một giá trị trong danh sách.',

            'metakey.required' => $messages['required'],
            'metakey.min' => 'Nhập ít nhất 5 ký tự',

            'detail.required' => $messages['required'],
            'detail.min' => 'Nhập ít nhất 5 ký tự',
            'detail.max' => 'Nhập nhiều nhất :max ký tự',

            'metadesc.required' => $messages['required'],
            'metadesc.min' => 'Nhập ít nhất 5 ký tự',
            'metadesc.min' => 'Nhập nhiều nhất :max ký tự',

            'image.required' => 'Hình ảnh không được để trống.',
            
            'image.*.image' => 'Tập tin phải là hình ảnh.',
            'image.*.mimes' => 'Hình ảnh phải có định dạng jpg, png, jpeg, gif hoặc svg.',
            'image.*.max' => 'Kích thước hình ảnh tối đa là 2048KB.',

            'price_buy.required' => 'Vui lòng nhập giá',
            'price_buy.numeric' => 'Vui lòng nhập giá hợp lệ',
            'price_buy.digits_between' => 'Giá sản phẩm phải có độ dài từ :min đến :max chữ số',

            'price.required' => 'Vui lòng nhập giá',
            'price.numeric' => 'Vui lòng nhập giá hợp lệ',
            'price.digits_between' => 'Giá sản phẩm phải có độ dài từ :min đến :max chữ số',


            'qty.required' => 'Vui lòng nhập số lượng sản phẩm',
            'qty.numeric' => 'Vui lòng nhập số lượng sản phẩm hợp lệ',
            'qty.between' => 'Số lượng sản phẩm phải nằm trong khoảng từ 1 đến 1000',

            'price_sale.lt' => 'Phải nhỏ hơn giá gốc',
            'price_sale.required_with' => $messages['required'],
            'date_begin.required_with' => $messages['required'],
            'date_end.required_with' => $messages['required'],
            'date_end.after' => 'Ngày kết thúc phải nhỏ hơn ngày bắt đầu',
        ];
    }
}
