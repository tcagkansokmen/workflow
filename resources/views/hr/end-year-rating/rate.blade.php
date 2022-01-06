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
  <h3 style="margin-bottom:25px;">Yeni Değerlendirme Girişi</h3>
  <form class="kt-form new-form" method="POST" action="{{ route('save-end-year-rate') }}" >
    <input type="hidden" name="user_id" value="{{ $user_id ?? '' }}">
    <input type="hidden" name="yearly_rating_id" value="{{ $yearly_rating_id ?? '' }}">
    @csrf
      
    @php 
      $kriter = "";
      $sorumluluk = "";
    @endphp
    @foreach($questions as $q)
    @php 
      if($kriter!=$q->criteria){
        $kriter = $q->criteria;
        echo "<h5>".$kriter."</h5>";
      }
      if($sorumluluk!=$q->responsibility){
        $sorumluluk = $q->responsibility;
        echo "<strong>".$sorumluluk."</strong><br>";
      }
    @endphp
      <div class="form-group row" style="margin-top:15px;">
        <label class="col-xl-12 col-lg-12 col-form-label">* {{ $q->question }}</label>
        <div class="col-lg-12">
          <input type="hidden" name="rating[{{ $loop->iteration }}][question_id]" value="{{ $q->id }}">
          <div class="rate">
            <input class="rating" type="hidden" name="rating[{{ $loop->iteration }}][rating]" value="{{ isset($q->rating->answer) ? round($q->rating->answer) : 0 }}">
              <div class="simple-rating star-rating">
                <i class="fa fa-star" data-rating="1"></i>
                <i class="fa fa-star" data-rating="2"></i>
                <i class="fa fa-star" data-rating="3"></i>
                <i class="fa fa-star" data-rating="4"></i>
                <i class="fa fa-star" data-rating="5"></i>
              </div>

            <textarea id="" cols="30" rows="2" name="rating[{{ $loop->iteration }}][description]" class="form-control" placeholder="Notlarınız" style="margin-top:15px;">{{ $q->rating->description ?? null }}</textarea>
          </div>
        </div>
      </div>
      <hr>
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
							window.location.href = item.redirect;
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