@php 
$color_array = array();
@endphp
@foreach($d->comments as $c)
<!--begin::Message In-->
@if($c->user->id!=Auth::user()->id)
  @php 
  $color_array[] = $c->user->id;
  $unique = array_unique($color_array);
  $ordering = array_search($c->user->id, $unique);

  @endphp
<div class="d-flex flex-column mb-5 align-items-start">
  <div class="d-flex align-items-center">
      {{ $c->user->userAvatar('40', $c->user->findColor($ordering), 'mr-3') }}
    <div>
      <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">{{ $c->user->name }} {{ $c->user->surname }}</a>
      <span class="text-muted font-size-sm">{{ Carbon\Carbon::parse($c->created_at)->diffForHumans()}} </span>
    </div>
  </div>
  <div class="mt-2 rounded p-5 bg-light-{{ $c->user->findColor($ordering) }} text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">{{ $c->comment }}</div>
  <div class="mt-1 showgallery">
    @foreach($c->files as $f)
      @if(pathinfo(Storage::url('snap/brief/').$f->file, PATHINFO_EXTENSION)=='jpg'||pathinfo(Storage::url('snap/brief/').$f->file, PATHINFO_EXTENSION)=='jpeg' || pathinfo(Storage::url('snap/brief/').$f->file, PATHINFO_EXTENSION)=='png'||pathinfo(Storage::url('snap/brief/').$f->file, PATHINFO_EXTENSION)=='gif')
        <a href="{{ Storage::url('snap/brief/') }}{{ $f->file }}" class="active-gallery">
          <img src="{{ Storage::url('snap/brief/') }}{{ $f->file }}" style="border-radius:5px; background:#f7f7f7; width:120px; height:120px; object-fit:cover; margin:3px;" alt="">
        </a>
      @else 
        <a href="{{ Storage::url('snap/brief/') }}{{ $f->file }}" class="active-gallery">
          <div style="width:120px; height:120px; background-color:#f7f7f7;display:flex;align-items:center;justify-content:center">
            {{ Metronic::getSVG("media/svg/icons/Files/Download.svg", "svg-icon-3x svg-icon-primary d-block") }}
          </div>
        </a>
      @endif

    @endforeach
  </div>
</div>
<!--end::Message In-->
@else 
<!--begin::Message Out-->
<div class="d-flex flex-column mb-5 align-items-end">
  <div class="d-flex align-items-center">
    <div>
      <span class="text-muted font-size-sm">{{ Carbon\Carbon::parse($c->created_at)->diffForHumans()}}</span>
      <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">{{ strtoupper(mb_substr($c->user->name, 0, 1)) }}{{ strtoupper(mb_substr($c->user->surname, 0, 1)) }}</a>
    </div>
      {{ $c->user->userAvatar('40', 'primary', 'ml-3') }}
  </div>
  <div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">{{ $c->comment }}</div>
  <div class="mt-1 showgallery">
    @foreach($c->files as $f)
      @if(pathinfo(Storage::url('snap/brief/').$f->file, PATHINFO_EXTENSION)=='jpg'||pathinfo(Storage::url('snap/brief/').$f->file, PATHINFO_EXTENSION)=='jpeg' || pathinfo(Storage::url('snap/brief/').$f->file, PATHINFO_EXTENSION)=='png'||pathinfo(Storage::url('snap/brief/').$f->file, PATHINFO_EXTENSION)=='gif')
        <a href="{{ Storage::url('snap/brief/') }}{{ $f->file }}" class="active-gallery">
          <img src="{{ Storage::url('snap/brief/') }}{{ $f->file }}" style="border-radius:5px; background:#f7f7f7; width:120px; height:120px; object-fit:cover; margin:3px;" alt="">
        </a>
      @else 
        <a href="{{ Storage::url('snap/brief/') }}{{ $f->file }}" class="active-gallery">
          <div style="width:120px; height:120px; background-color:#f7f7f7;display:flex;align-items:center;justify-content:center">
            {{ Metronic::getSVG("media/svg/icons/Files/Download.svg", "svg-icon-3x svg-icon-primary d-block") }}
          </div>
        </a>
      @endif

    @endforeach
  </div>
</div>
<!--end::Message Out-->
@endif
@endforeach