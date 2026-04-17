<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_time'  => 'required|date|after:now',
            'is_public'   => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The event title is required.',
            'title.max' => 'The title must not exceed 255 characters.',
            'event_time.required' => 'The event date and time is required.',
            'event_time.date' => 'Please provide a valid date and time.',
            'event_time.after' => 'The event time must be in the future.',
        ];
    }
}
