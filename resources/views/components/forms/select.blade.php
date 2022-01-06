<select
    class="form-control m-input {{ isset($class) ? $class : '' }} {{ isset($required) ? 'required' : '' }}"
    name="{{ $name }}"
    {{ isset($attribute) ? $attribute : '' }}
    >
    @isset($values)
        <option value="">Seçiniz</option>
        @foreach($values as $v)
            <option value="{{ $v->value }}"
                @isset($value)
                    @if($value == $v->value)
                        selected
                    @endif
                    @if(is_array($value))
                        @if(in_array($v->value, $value))
                            selected
                        @endif
                    @endif
                @endisset
            >{{ $v->name }}</option>
        @endforeach
    @else
        @isset($value)
            @isset($value->id)
            <option value="{{ $value->id }}">{{ $value->title ?? '' }}</option>
            @endisset
        @endisset
    @endisset
</select>
<div class="invalid-feedback"></div>
<style>
.select2{
    width:100% !important;
}
</style>
