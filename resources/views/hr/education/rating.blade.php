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
  <h5 style="margin-bottom:25px;">{{ $detail->name }} Yeni Değerlendirme Girişi</h5>
  <form class="kt-form new-form" method="POST" action="{{ route('egitim-rating-kaydet') }}" >
    <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
    @csrf
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
        <input type="hidden" name="rating[{{ $q->id }}][question]" value="{{ $q->id }}">
        @if($q->type=='star')
          <div class="form-group row" style="margin-top:15px;">
            <label class="col-xl-5 col-lg-3 col-form-label">* {{ $q->title }}</label>
            <div class="col-lg-7">
              <div class="rate">
                <input class="rating" type="hidden" name="rating[{{ $q->id }}][answer]">
                  <div class="simple-rating star-rating">
                    <i class="fa fa-star" data-rating="1"></i>
                    <i class="fa fa-star" data-rating="2"></i>
                    <i class="fa fa-star" data-rating="3"></i>
                    <i class="fa fa-star" data-rating="4"></i>
                    <i class="fa fa-star" data-rating="5"></i>
                  </div>
              </div>
            </div>
          </div>
        @else 
          <div class="form-group row" style="margin-top:15px;">
            <label class="col-xl-5 col-lg-3 col-form-label">* {{ $q->title }}</label>
            <div class="col-lg-7">
                <div class="kt-radio-inline">
                  <label class="kt-radio kt-radio--bold kt-radio--success">
                    <input type="radio" name="rating[{{ $q->id }}][answer]" value="1"> Evet/Yes
                    <span></span>
                  </label>
                  <label class="kt-radio kt-radio--bold kt-radio--success">
                    <input type="radio" name="rating[{{ $q->id }}][answer]" value="2"> Kısmen/Partly
                    <span></span>
                  </label>
                  <label class="kt-radio kt-radio--bold kt-radio--success">
                    <input type="radio" name="rating[{{ $q->id }}][answer]" value="0"> Hayır/No
                    <span></span>
                  </label>
                </div>
            </div>
          </div>
        @endif
    @endforeach
    <button type="submit" class="btn btn-success">Kaydet</button>&nbsp;
  </form>
</div>
<script>
				$('.new-form').ajaxForm({ 
						error: function(){
							swal.fire({
								"title": "",
								"text": "Kaydedilemedi",
								"type": "warning",
								"confirmButtonClass": "btn btn-secondary"
							});

						},
						dataType:  'json', 
						success:   function(item){
              if(item.status){
							location.reload();
              }else{
                swal.fire({
                  "title": "",
                  "text": item.message,
                  "type": "warning",
                  "confirmButtonClass": "btn btn-secondary"
                });
              }
						}
				}); 

$("body").on('click', '.star-rating i', function(){
  var a = $(this).attr('data-rating');
  
  $(this).closest('.star-rating').find('.fa-star').removeClass('active');
  $(this).closest('.rate').find('.rating').val(a);

  for(var i = 0; i<a; i++){
    $(this).closest('.star-rating').find('.fa-star:eq('+i+')').addClass('active');
  }
});

$(document).ready(function(){
  $( ".rate" ).each(function( index ) {
    var a = $(this).find('.rating').val();

    for(var i = 0; i<a; i++){
      $(this).find('.fa-star:eq('+i+')').addClass('active');
    }

  });
});
</script>