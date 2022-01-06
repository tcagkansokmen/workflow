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
  <h5 style="margin-bottom:25px;">{{ $detail->name }} | {{ $user->name }} {{ $user->surname }} Değerlendirme</h5>
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
        <input type="hidden" name="rating[{{ $loop->index }}][question]" value="{{ $q->nid }}">
        @if($q->type=='star')
          <div class="form-group row" style="margin-top:15px;">
            <label class="col-xl-5 col-lg-3 col-form-label">* {{ $q->title }}</label>
            <div class="col-lg-7">
              <div class="rate">
                <input class="rating" type="hidden" name="rating[{{ $loop->index }}][answer]" value="{{ isset($q->answer) ? round($q->answer) : 0 }}">
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
                    <input type="radio" name="rating[{{ $loop->index }}][answer]" value="1" 
                    @isset($q->answer)
                      @if($q->answer==1)
                        checked
                      @endif
                    @endisset
                    > Evet/Yes
                    <span></span>
                  </label>
                  <label class="kt-radio kt-radio--bold kt-radio--success">
                    <input type="radio" name="rating[{{ $loop->index }}][answer]" value="2"
                    @isset($q->answer)
                      @if($q->answer==2)
                        checked
                      @endif
                    @endisset
                    > Kısmen/Partly
                    <span></span>
                  </label>
                  <label class="kt-radio kt-radio--bold kt-radio--success">
                    <input type="radio" name="rating[{{ $loop->index }}][answer]" value="0"
                    @isset($q->answer)
                      @if($q->answer==0)
                        checked
                      @endif
                    @endisset
                    > Hayır/No
                    <span></span>
                  </label>
                </div>
            </div>
          </div>
        @endif
    @endforeach
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

$(document).ready(function(){
  $( ".rate" ).each(function( index ) {
    var a = $(this).find('.rating').val();

    for(var i = 0; i<a; i++){
      $(this).find('.fa-star:eq('+i+')').addClass('active');
    }

  });
});
</script>