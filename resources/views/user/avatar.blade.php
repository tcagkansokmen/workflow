
<span class="symbol symbol-{{ $options['size'] }} symbol-light-{{ $options['color'] }} {{ $options['class'] }}">
  @if($user->avatar)
    <img alt="{{ $user->name }}" src="{{ Storage::url('uploads/users/') }}{{ $user->avatar }}"/>
  @else 
    <span class="symbol-label font-size-h5 font-weight-bold">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</span>
  @endif
</span>