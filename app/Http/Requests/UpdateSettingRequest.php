<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'general_site_name' => ['required', 'string', 'max:120'],
            'general_tagline' => ['nullable', 'string', 'max:200'],
            'general_homepage_title' => ['nullable', 'string', 'max:160'],
            'general_homepage_subtitle' => ['nullable', 'string', 'max:260'],

            'brand_primary_color' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
            'brand_accent_color' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
            'brand_logo' => ['nullable', 'image', 'max:2048'],
            'brand_favicon' => ['nullable', 'image', 'mimes:png,ico', 'max:1024'],

            'contact_email' => ['nullable', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:60'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'contact_whatsapp' => ['nullable', 'string', 'max:60'],

            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }
}
