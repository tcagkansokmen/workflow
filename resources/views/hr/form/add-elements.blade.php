{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<style>
.inside-fields{
  padding:15px;
}
#top{
  display:block;
}
#bottom{
  padding: 20px;
cursor: pointer;
border: 2px dashed #ebedf2;
border-radius: 4px;
}
#bottom .draggable{
  padding:15px;
  background:#f9f9f9;
  border-radius:12px;
  margin-bottom:10px;
  position:relative;
  padding-left:40px;
  padding-right:40px;
}
#top .draggable{
  text-align:center;
  margin-bottom:25px;
}
#top .draggable{
  display:block;
}
#top .draggable img{
  margin-top:5px;
 max-width:100%;
}
#top .draggable{
  cursor:move;
}
.gu-transit img,
.gu-mirror img{
  max-height:85px;
}

.drag-side{
  position: absolute;
  left: 5px;
  font-size: 32px;
  top: 50%;
  margin-top: -22px;
  -webkit-transform: rotate(90deg);
  -moz-transform: rotate(90deg);
  -o-transform: rotate(90deg);
  -ms-transform: rotate(90deg);
  transform: rotate(90deg);
  cursor:move;
}
.drag-delete{
  position: absolute;
  right: 5px;
  font-size: 22px;
  top: 50%;
  margin-top: -16px;
}
label.label-title{
  font-size:16px;
  font-weight:bold;
}
</style>
<div class="container">
<div class="row">
  <div class="col-sm-2 offset-2">
    <div class="card">
      <div class="card-body">
        <div id="top">
          <h5><strong>Form Elementleri</strong></h5>
          <hr>
          <div class="draggable" data-type="input">
            <strong>Kısa Yanıt</strong>
            <img src="/images/modules/kisa-yanit.png" alt="">
          </div>
          <div class="draggable" data-type="textarea">
            <strong>Paragraf</strong>
            <img src="/images/modules/paragraf.png" alt="">
          </div>
          <div class="draggable" data-type="text">
            <strong>Metin</strong>
            <img src="/images/modules/metin.png" alt="">
          </div>
          <div class="draggable" data-type="select">
            <strong>Açılır Menü</strong>
            <img src="/images/modules/acilir-menu.png" alt="">
          </div>
          <div class="draggable" data-type="radio">
            <strong>Çoktan Seçmeli</strong>
            <img src="/images/modules/coktan-secmeli.png" alt="">
          </div>
          <div class="draggable" data-type="checkbox">
            <strong>Onay Kutuları</strong>
            <img src="/images/modules/onay-kutusu.png" alt="">
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="col-sm-6">
    <div class="card">
      <div class="card-header flex-wrap">
        <div class="card-title">
            <h3 class="card-label">{{ $form_single->title }}
            </h3>
        </div>
      </div>
      <div class="card-body">
        <div style="margin-top:25px;">
          <div class="row">
            <div class="col-sm-12">
                <div id="bottom" class="inside-fields" style="min-height:500px;">
                  @foreach($fields as $f)
                  <div class="draggable" data-type="{{ $f->type }}">
                  <div class="drag-side"><span class="drag-button"><i class="flaticon-more-v4"></i></span></div>
                  <div class="drag-delete" data-form="{{ $form_single->id }}" data-id="{{ $f['name'] ?? '' }}"><i class="flaticon2-trash"></i></div>
                    @include('module.'.$f->type, 
                  [
                    'all' => array(
                      'is_required' => $f['is_required'] ?? false,
                      'label' => $f['label'],
                      'name' => $f['name'] ?? uniqid(),
                      'class' => $f['form_class'] ?? '',
                      'values' => $f['values'] ?? ''
                    )
                  ])
                  </div>
                  @endforeach

                  <div class="dropzone-msg dz-message needsclick" style="margin-top:55px; text-align:center;">
                  <img src="/images/drag-drop.jpg" class="img-fluid" style="height:145px;s" alt="">
                      <h3 class="dropzone-msg-title">Form Elementlerini Buraya Sürükleyin</h3>
                      <span class="dropzone-msg-desc">Sürüklediğiniz elementlerin detaylarını düzenleyebilir ve sürükleyerek yerini değiştirebilirsiniz.</span>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <button type="button" class="btn btn-primary form-olustur mt-10">Formu Oluştur</button>
      </div>

    </div>
  </div>

</div>
</div>


<div class="modal fade" id="input_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form>
        <input type="hidden" name="type" value="input">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Standard Alan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label for="recipient-name" class="form-control-label">Soru (Açıklama):</label>
                  <input type="text" class="form-control" name="label" id="recipient-name">
              </div>
              <h5 for="" style="display:block;">
                Zorunlu Alan mı?
              </h5>
              <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--primary">
                <label>
                <input type="checkbox" name="is_required" value="true">
                <span></span>
                </label>
              </span>
              <div>
                <hr>
                <h5>Türü</h5>
                @include('common.forms.radio', [
                    'label' => 'Standart Format',
                    'color' => 'brand',
                    'name' => 'input_type',
                    'href' => '',
                    'class' => '',
                    'value' => 'standard',
                    'val' => 'standard'
                ])
                @include('common.forms.radio', [
                    'label' => 'Telefon Formatı',
                    'color' => 'brand',
                    'name' => 'input_type',
                    'href' => '',
                    'class' => '',
                    'value' => 'phone'
                ])
                @include('common.forms.radio', [
                    'label' => 'Tarih Formatı',
                    'color' => 'brand',
                    'name' => 'input_type',
                    'href' => '',
                    'class' => '',
                    'value' => 'date-format'
                ])
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary input-onayla">Yerleştir</button>
            </div>
        </div>
      </form>
    </div>
</div>

<div class="modal fade" id="text_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form>
        <input type="hidden" name="type" value="text">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Metin alanı</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label for="recipient-name" class="form-control-label">Soru (Açıklama):</label>
                  <textarea name="label" id="" cols="30" rows="10" class="summernote"></textarea>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary input-onayla">Yerleştir</button>
            </div>
        </div>
      </form>
    </div>
</div>

<div class="modal fade" id="textarea_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form>
        <input type="hidden" name="type" value="textarea">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Paragraf Alanı</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label for="recipient-name" class="form-control-label">Soru (Açıklama):</label>
                  <input type="text" class="form-control" name="label" id="recipient-name">
              </div>
              <h5 for="" style="display:block;">
                Zorunlu Alan mı?
              </h5>
              <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--primary">
                <label>
                <input type="checkbox" name="is_required" value="true">
                <span></span>
                </label>
              </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary input-onayla">Yerleştir</button>
            </div>
        </div>
      </form>
    </div>
</div>

<div class="modal fade" id="select_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form>
        <input type="hidden" name="type" value="select">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Menüden Seçmeli Alan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label for="recipient-name" class="form-control-label">Soru (Açıklama):</label>
                  <input type="text" class="form-control" name="label" id="recipient-name">
              </div>
              <div class="form-group">
                  <label for="recipient-name" class="form-control-label">Seçenekler:</label>
                  <input class="kt_tagify_1" name='values' placeholder='Her bir seçenek arasına virgül koyun' >
              </div>
              <h5 for="" style="display:block;">
                Zorunlu Alan mı?
              </h5>
              <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--primary">
                <label>
                <input type="checkbox" name="is_required" value="true">
                <span></span>
                </label>
              </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary input-onayla">Yerleştir</button>
            </div>
        </div>
      </form>
    </div>
</div>

<div class="modal fade" id="radio_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form>
        <input type="hidden" name="type" value="radio">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Çoklu Seçimli Alan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label for="recipient-name" class="form-control-label">Soru (Açıklama):</label>
                  <input type="text" class="form-control" name="label" id="recipient-name">
              </div>
              <div class="form-group">
                  <label for="recipient-name" class="form-control-label">Seçenekler:</label>
                  <input class="kt_tagify_2" name='values' placeholder='Her bir seçenek arasına virgül koyun' >
              </div>
              <h5 for="" style="display:block;">
                Zorunlu Alan mı?
              </h5>
              <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--primary">
                <label>
                <input type="checkbox" name="is_required" value="true">
                <span></span>
                </label>
              </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary input-onayla">Yerleştir</button>
            </div>
        </div>
      </form>
    </div>
</div>

<div class="modal fade" id="checkbox_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form>
        <input type="hidden" name="type" value="checkbox">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Çoklu Seçimli Alan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label for="recipient-name" class="form-control-label">Soru (Açıklama):</label>
                  <input type="text" class="form-control" name="label" id="recipient-name">
              </div>
              <div class="form-group">
                  <label for="recipient-name" class="form-control-label">Seçenekler:</label>
                  <input class="kt_tagify_3" name='values' placeholder='Her bir seçenek arasına virgül koyun' >
              </div>
              <h5 for="" style="display:block;">
                Zorunlu Alan mı?
              </h5>
              <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--primary">
                <label>
                <input type="checkbox" name="is_required" value="true">
                <span></span>
                </label>
              </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary input-onayla">Yerleştir</button>
            </div>
        </div>
      </form>
    </div>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js"></script>
<script src="/assets/js/pages/crud/forms/widgets/tagify.js" type="text/javascript"></script>
<script>
  var input = document.querySelector('.kt_tagify_1');
  var kt_tagify_1 = new Tagify(input);
  var input = document.querySelector('.kt_tagify_3');
  var kt_tagify_3 = new Tagify(input);
  var input = document.querySelector('.kt_tagify_2');
  var kt_tagify_2 = new Tagify(input);
  
$("body").on('click', '.drag-delete', function(){
  
  var name = $(this).attr('data-id');
  var id = $(this).attr('data-form');
  var thi = $(this);

  if(!name){
    thi.closest('.draggable').remove();
  }

  var r = confirm("Silmek istediğinize emin misiniz? Bu işlemin geri dönüşü yoktur");
  if (r == true) {
    if(name){
      $.ajax({
      type: "POST",
      url: "/form/delete-field",
      dataType: 'json',
      headers: {
        'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
      },
      data: 'name='+name+"&id="+id,
      error:function(){
        alert('Daha önce cevap alınmış alanları silemezsiniz');
      },
      success: function(data){
        if(data.status){
        thi.closest('.draggable').remove();
        }else{
        alert('Daha önce cevap alınmış alanları silemezsiniz');
        }
      }
      });
    }


    if($("#bottom .drag-side").length < 1){
      $(".dropzone-msg").removeClass('kt-hidden');
    }else{
      $(".dropzone-msg").addClass('kt-hidden');
    }

  }

});
dragula([document.getElementById('top'), document.getElementById('bottom'), document.getElementById('second')], {
  copy: function (el, source) {
    return source === document.getElementById('top')
  },
  accepts: function (el, target) {
    return target !== document.getElementById('top')
  },
}).on('shadow', function(el, container, source){
  console.log('shadow çalıştı');

var m = $(el).attr("data-type");	

  var label = $(el).find('.datas').attr('data-label');
  var is_required = $(el).find('.datas').attr('data-required');
  var input_type = $(el).find('.datas').attr('data-input');
  var values = $(el).find('.datas').attr('data-values');
  var type = $(el).find('.datas').attr('data-type');

  console.log("sh:" + label);
  console.log("val:" + values);
  
  if(label == undefined){
    $.ajax({
    type: "GET",
    dataType: "html",
    url: "/polls/preview",
    headers: {
      'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
    },
    crossDomain: true,
    data: 'type='+m+"&all=1&label="+label+"&values="+values+"&is_required="+is_required
    }).done(function(data) {
      console.log('bu çalışmış olmalı');
          $(".dropzone-msg").addClass('kt-hidden');
        $(".gu-transit").html('<div class="drag-side"><span class="drag-button"><i class="flaticon-more-v4"></i></span></div><div class="drag-delete"><i class="flaticon2-trash"></i></div>'+data);
    });
  }else{

    $.ajax({
            type: "POST",
            url: "/polls/create",
            dataType: 'html',
            crossDomain: true,
            headers: {
              'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
            },
            data: { 
              'type': type,
              'label': label,
              'is_required': is_required,
              'input_type': input_type,
              'values': values,
              'name': name
            }
        })
        .done(function( data ) {
      console.log('layouttan');
            $(".draggable.active").html('<div class="drag-side"><span class="drag-button"><i class="flaticon-more-v4"></i></span></div><div class="drag-delete"><i class="flaticon2-trash"></i></div>'+data);
            $(".modal").modal('hide');

            $(".modal form").trigger("reset");
            $('.modal .summernote').summernote('code', '');

        });
  }
                
}).on('dragend', function(el, container, source){
  console.log('end çalıştı');

  var label = $(el).find('.datas').attr('data-label');
  var is_required = $(el).find('.datas').attr('data-required');
  var input_type = $(el).find('.datas').attr('data-class');
  var values = $(el).find('.datas').attr('data-values');

  $(".draggable").removeClass('active');
  $(el).addClass('active');
  var m = $(el).attr("data-type");	


  $("#"+m+"_modal").modal('show');
  $("#"+m+"_modal [name=label]").val(label);
  if(is_required){
    $("#"+m+"_modal [name=is_required]").attr('checked','checked');
  }

  console.log('input type: '+input_type);
  $("#"+m+"_modal [value=" + input_type + "]").attr('checked', 'checked');
  
  var tagler = values.split(',');
  if(m == "select"){
    
    kt_tagify_1.addTags(tagler);
    console.log(tagler);

  }else if(m == "checkbox"){
    kt_tagify_3.addTags(tagler);
  }else if(m == "radio"){
    kt_tagify_2.addTags(tagler);
  }
  
  
});
$(".phone").inputmask("(999) 999 99-99");
</script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function(){
  $("body").on('click', '.form-olustur:not(.active)', function(){
    $(this).addClass('active');
    var i = 0;
    $( "#bottom .draggable" ).each(function( index ) {
      var thi = $(this).find('.datas');

      type = thi.attr('data-type');
      label = thi.attr('data-label');
      is_required = thi.attr('data-required');
      values = thi.attr('data-values');
      name = thi.attr('data-name');
      dataclass = thi.attr('data-class');


      $.ajax({
            type: "POST",
            url: "/polls/add-element-form/{{ $form_single->id }}",
            dataType: 'json',
            data: { 
              'type': type,
              'label': label,
              'is_required': is_required,
              'values': values,
              'name': name,
              'priority': i,
              'class': dataclass
            }
        })
        .done(function( data ) {
          swal.fire({
            "title": "",
            "text": "Başarıyla kaydedildi, form detaylarına yönlendiriliyorsunuz.",
            "type": "success",
            "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
          });
          setTimeout(function(){
            window.location.href = data.redirect;
          }, 1500);
        });

        i++;
    });
      

  });
  $("body").on('click', '.input-onayla', function(){
    var form = $(this).closest('form');

    var label = form.find('[name=label]').val();
    var is_required = form.find('[name=is_required]:checked').val();
    var input_type = form.find('[name=input_type]:checked').val();
    var type = form.find('[name=type]').val();
    var values = form.find('[name=values]').val();


            
    console.log('input-onayla');
    console.log('.iolabel: ' + label);
    console.log('.iovalues: ' + values);
    console.log('.iotype: ' + type);
    console.log('tagstr');

    var name = $(".draggable.active .datas").attr('data-name');
    
    $(".draggable.active").attr('data-label', label);
    $(".draggable.active").attr('data-required', is_required);
    $(".draggable.active").attr('data-input', input_type);
    $(".draggable.active").attr('data-values', values);
     $.ajax({
            type: "POST",
            url: "/polls/create",
            crossDomain: true,
            headers: {
              'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'html',
            data: { 
              'type': type,
              'label': label,
              'is_required': is_required,
              'input_type': input_type,
              'values': values,
              'name': name
            }
        })
        .done(function( data ) {
          console.log('add elementsten');
						$(".draggable.active").html('<div class="drag-side"><span class="drag-button"><i class="flaticon-more-v4"></i></span></div><div class="drag-delete"><i class="flaticon2-trash"></i></div>'+data);
            $(".modal").modal('hide');

						kt_tagify_1.removeAllTags();
						kt_tagify_3.removeAllTags();
            kt_tagify_2.removeAllTags();
            
            $(".modal form").trigger("reset");
            $('.modal .summernote').summernote('code', '');

        });
  });
});
$('[name=kt_user_add_user_avatar]').change(function () {
  var file = this.files[0];
  var reader = new FileReader();
  reader.onloadend = function () {
     $('.kt-avatar__holder').css('background-image', 'url("' + reader.result + '")');
  }
  if (file) {
      reader.readAsDataURL(file);
  } else {
  }
  
});
</script>
@endsection