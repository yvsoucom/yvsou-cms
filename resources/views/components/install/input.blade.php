
@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'required' => false
])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ __($label) }}
    </label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-500"
    >
</div>
 