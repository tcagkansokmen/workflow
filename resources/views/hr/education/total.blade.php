<div>
<style>
.simple-rating i{
  color: rgba(0,0,0,0.10);
  display: inline-block;
  padding: 1px 2px;
  cursor: pointer;
  font-size:22px;
}
.simple-rating i.active{
  color: #5d78ff;
}
</style>
  <h5 style="margin-bottom:25px;">{{ $detail->name }} Değerlendirme</h5>
    @php 
    $category = "";
    @endphp
    @foreach($questions as $q)
      @if($category!=$q->category)
      <hr>
      @php 
        $category = $q->category
      @endphp
      <h5 style="margin-top:25px;">{{ $category }}</h5>
      @endif
      <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-25 symbol-lg-25 symbol-light-danger">
          <span class="font-size-h5 symbol-label font-weight-boldest" style="flex-direction:column;">
            {{ $q->paid_sum ? round($q->paid_sum, 2) : 0.00 }}
          </span>
        </div>
        <div class="ml-4">
          <div class="text-dark-75 font-size-lg mb-0">{{ $q->title }}</div>
        </div>
      </div>
    @endforeach
    @php 
    $category = "";
    @endphp
    @foreach($yesno as $q)
      @if($category!=$q->category)
      <hr>
      @php 
        $category = $q->category
      @endphp
      <h5 style="margin-top:25px;">{{ $category }}</h5>
      @endif

      <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-40 symbol-lg-40 symbol-light-success mr-2">
          <span class=" symbol-label" style="flex-direction:column;"><strong>{{ $q->yes ?? 0 }}</strong>
          <strong style="font-size:10px; font-weight:normal;">Evet</strong></span>
        </div>
        <div class="symbol symbol-40 symbol-lg-40 symbol-light-danger mr-2">
          <span class=" symbol-label" style="flex-direction:column;"><strong>{{ $q->no ?? 0 }}</strong>
          <strong style="font-size:10px; font-weight:normal;">Hayır</strong></span>
        </div>
        <div class="symbol symbol-40 symbol-lg-40 symbol-light-primary mr-2">
          <span class=" symbol-label" style="flex-direction:column;"><strong>{{ $q->partly ?? 0 }}</strong>
          <strong style="font-size:10px; font-weight:normal;">Kısmen</strong></span>
        </div>
        <div class="ml-4">
          <div class="text-dark-75 font-size-lg mb-0">{{ $q->title }}</div>
        </div>
      </div>
    @endforeach