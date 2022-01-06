
@foreach($detail->comments as $d)
  @if($d->user->id != Auth::user()->id)
  <!--begin::Message In-->
  <div class="d-flex flex-column mb-5 align-items-start">
    <div class="d-flex align-items-center">
      <div class="symbol symbol-circle symbol-40 mr-3 symbol-light-success">
        <span class="symbol-label font-size-h5 font-weight-bold">{{ strtoupper(mb_substr($d->user->name, 0, 1)) }}{{ strtoupper(mb_substr($d->user->surname, 0, 1)) }}</span>
      </div>
      <div>
        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">{{ $d->user->name }} {{ $d->user->surname }}</a>
        <span class="text-muted font-size-sm">{{ Carbon\Carbon::parse($d->created_at)->diffForHumans()}}</span>
      </div>
    </div>
    <div class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">{{ $d->comment }}</div>
  </div>
  <!--end::Message In-->
  @else 
  <!--begin::Message Out-->
  <div class="d-flex flex-column mb-5 align-items-end">
    <div class="d-flex align-items-center">
      <div>
        <span class="text-muted font-size-sm">{{ Carbon\Carbon::parse($d->created_at)->diffForHumans()}}</span>
        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Siz</a>
      </div>
      <div class="symbol symbol-circle symbol-40 ml-3 symbol-light-primary">
        <span class="symbol-label font-size-h5 font-weight-bold">{{ strtoupper(mb_substr($d->user->name, 0, 1)) }}{{ strtoupper(mb_substr($d->user->surname, 0, 1)) }}</span>
      </div>
    </div>
    <div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">{{ $d->comment }}</div>
  </div>
  <!--end::Message Out-->
  @endif
@endforeach
@if(!count($detail->comments))
  <p>Herhangi bir yorum bulunamadı</p>
@endif