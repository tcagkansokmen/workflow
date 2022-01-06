<div class="form-group m-form__group">
    <label>
        {{ $label }}
        @isset($required)
            @if ($required)
                *
            @endif
        @endisset
    </label>
    <input
        type="{{ $type }}"
        class="form-control form-control-solid m-input  {{ isset($class) ? $class : '' }} {{ isset($required)&&$required ? 'required' : '' }}"
        placeholder="{{ $placeholder }}"
        name="{{ $name }}"

        @isset($attribute)
        {{$attribute}}
        @endisset

        @isset($id)
            id="{{$id}}"
        @endisset
        @if(isset($value))
            @if(is_object($value))
                value="{{ $value[0]['value'] }}"
            @else 
                value="{{$value}}"
            @endif
        @else
            value="{{old($name)}}"
        @endif
       @isset($step)
            step="{{$step}}"
       @endisset
    />
    <div class="invalid-feedback">Shucks, check the formatting of that and try again.</div>
    <span class="m-form__help">{{ $help }}</span>
</div>
