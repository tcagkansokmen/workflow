{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<!--Begin:: Portlet-->
<div class="card" style="margin-top:25px;">
  <div class="card-body">
    <div class="kt-widget kt-widget--user-profile-3">
      <div class="kt-widget__top">
        @isset($c->event->logo)
          <div class="kt-widget__media">
            <img src="{{ Storage::url('uploads/event/') }}{{ $c->event->logo }}" alt="image" style="height:120px; border:1px solid #ddd; object-fit:contain;">
          </div>
        @else
        <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-bolder kt-font-light kt-hidden">
          {{ strtoupper(substr($c->event->title, 0, 1)) }}
        </div>
        @endif
        <div class="kt-widget__content">
          <div class="kt-widget__head">
            <div class="kt-widget__user">
              <a href="#" class="kt-widget__username">
                {{ $c->title }}
              </a>
              @if(strtotime(date("Y-m-d")) > strtotime($c->end_at))
              <span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-danger">{{ $c->event->title }}</span>
              @else 
              <span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-success">{{ $c->event->title }}</span>
              @endif
            </div>
            <div class="kt-widget__action">
              @if(Request::user()->isDirector())
              <a href="{{ route('form-edit', ['form_id' => $c->id]) }}" class="btn btn-label-warning btn-sm btn-upper">Düzenle</a>
              @endif
              @if(Request::user()->isAttendee())
              <a href="{{ route('form-contacts', ['form_id' => $c->id]) }}" class="btn btn-label-brand btn-sm btn-upper">Kişi Listesi</a>
              <a href="{{ route('form-answers', ['form_id' => $c->id]) }}" class="btn btn-brand btn-sm btn-upper">Cevaplar</a>
              <a href="{{ route('form-preview', ['form_id' => $c->id]) }}" target="_blank" class="btn btn-success btn-sm btn-upper">Form Önizleme</a>
              <a href="{{ route('form-qr-code', ['form_id' => $c->id]) }}" target="_blank" class="btn btn-info btn-sm btn-upper"><i class="fa fa-qrcode"></i> QR Code</a>
              @endisset
            </div>
          </div>
          <div class="kt-widget__subhead">
            <a href="#"><i class="flaticon2-calendar-3"></i>{{ date('d.m.Y', strtotime($c->start_at)) }} - {{ date('d.m.Y', strtotime($c->end_at)) }}  </a>
          </div>
          <div class="kt-widget__info">
            <div class="kt-widget__desc">
            {!! $c->description !!}
            </div>
            <div class="kt-widget__progress">
              <div class="kt-widget__text">
                Cevaplanma Oranı
              </div>
              <div class="progress" style="height: 5px;width: 100%;">
                <div class="progress-bar kt-bg-success" role="progressbar" style="width: {{ ($c->answers_count/($c->contacts_count==0 ? 1 : $c->contacts_count ))*100 }}%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <div class="kt-widget__stats">
              @php 

                $round = $c->answers_count/($c->contacts_count==0 ? 1 : $c->contacts_count );
              @endphp
                {{ round($round*100) }}%
              </div>
            </div>
          </div>
          <div style="display:flex; justify-content:space-between">
            <div>
                <a href="{{ Storage::url('uploads/form/') }}{{ $c->kvkk }}" target="_blank" class="btn btn-label-info btn-sm btn-upper">KVKK</a>
                <a href="{{ Storage::url('uploads/form/') }}{{ $c->aydinlatma }}" target="_blank" class="btn btn-label-info btn-sm btn-upper">Aydınlatma Metni</a>
            </div>
            <div>
              @if($c->allow_anonymous)
                <span class="btn btn-label-success btn-sm btn-upper"><i class="flaticon-globe"></i> Kayıt Serbest</span>
              @else 
                <span class="btn btn-label-danger btn-sm btn-upper"><i class="flaticon-lock"></i> Özel Form</span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--End:: Portlet-->
<div class="row">
  <div class="col-xl-4">

    @if(Request::user()->isResponsible())
    <!--Begin:: Portlet-->
    <div class="card card--head-noborder">
      <div class="card__head">
        <div class="card__head-label">
          <h3 class="card__head-title  kt-font-danger">
            Önemli Not
          </h3>
        </div>
        <div class="card__head-toolbar">
          <span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--danger">Yeni</span>
        </div>
      </div>
      <div class="card-body card-body--fit-top">
        <div class="kt-section kt-section--space-sm">
          Form cevaplarına göre yeni listeler oluşturarak bu listeler üzerinden işaretlemeler gerçekleştirebilirsiniz. Örneğin; {{ __('messages.ornek') }} gibi listeler oluşturup bu listedeki kişileri "tamam", "Bekliyor", "iptal" olarak işaretleyerek operasyonlarınızda kullanabilirsiniz.
        </div>
        <div class="kt-section kt-section--last">
          <a href="#" class="btn btn-brand btn-sm btn-bold" data-toggle="modal" data-target="#liste-olustur"><i class=""></i> Bir Liste Oluştur</a>&nbsp;
        </div>
      </div>
    </div>

      <div class="modal fade" id="liste-olustur" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <form action="{{ route('new-list') }}" method="POST">
                    @csrf
                    <input type="hidden" name="form_id" value="{{ $c->id }}">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Liste Oluştur</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      </button>
                  </div>
                  <div class="modal-body">
                    <p>Seçtiğiniz soruya verilen cevaba göre bir liste oluşturabilirsiniz.</p>
                    <div class="row">
                      <div class="col-sm-12">
                        <input type="text" name="name" class="form-control" placeholder="Listenize isim giriniz">
                      </div>

                        <div class="col-lg-12">
                        <label class="col-form-label">Liste Sorumluları</label>
                          <select name="sorumlu[]" id="" class="select2" multiple="multiple">
                            @foreach($sorumlular as $s)
                              <option value="{{ $s->id }}"
                              @isset($secililer)
                                @if(in_array($s->id, $secililer))
                                  selected
                                @endif
                              @endisset
                              >{{ $s->name }}</option>
                            @endforeach
                          </select>
                        </div>

                      <div class="col-sm-12" style="margin-top:20px;">
                        <select name="label" id="" class="custom-select pick-label">
                          <option value="">Soru Seçiniz</option>
                          @foreach($answers as $a)
                            <option value="{{ $a->id }}">{{ $a->label }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-12" style="margin-top:20px;">
                        <select name="answer" id="" class="custom-select pick-answer">
                          <option value="">Cevap Seçiniz</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Liste Oluştur</button>
                  </div>
                  </form>
              </div>
          </div>
      </div>
    <!--End:: Portlet-->

    <!--Begin:: Portlet-->
    <div class="card">
      <div class="card__head">
        <div class="card__head-label">
          <h3 class="card__head-title">
            Listeler
          </h3>
        </div>
      </div>
      <div class="card-body">
            <table class="table table-striped- table-hover table-checkable" >
              <thead>
                <tr>
                  <th>İsim</th>
                  <th>Sayı</th>
                  <th class="align-right">İşlem</th>
                </tr>
              </thead>
              <tbody>
                @foreach($lists as $list)
                <tr>
                  <td>
                  <strong>{{ $list->name }}</strong>
                  </td>
                  <td>
                    <span class="btn btn-label-success btn-sm btn-upper">{{ $list->contacts_count }}</span>
                  </td>
                  <td class="align-right">
                    <a href="{{ route('list-detail', ['list_id' => $list->id]) }}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Listeyi Görüntüle">
                      <i class="la la-search"></i>
                    </a>
                    <a href="{{ route('list-delete', ['list_id' => $list->id]) }}" class="btn btn-sm btn-danger btn-icon btn-icon-md emin-misiniz" title="Listeyi Silin">
                      <i class="la la-trash"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tfoot>
            </table>
      </div>
    </div>
    @endisset
    <!--End:: Portlet-->

    <!--End:: Portlet-->
  </div>
  <div class="col-xl-8">

    <!--Begin:: Portlet-->
    <div class="card card--tabs">
      <div class="card__head">
        <div class="card__head-toolbar">
          <ul class="nav nav-tabs nav-tabs-space-lg nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-brand" role="tablist">
          @if(Request::user()->isResponsible())
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#kt_apps_contacts_view_tab_1" role="tab">
                <i class="flaticon2-note"></i> Son Yanıtlayanlar
              </a>
            </li>
          @endisset
            <li class="nav-item">
              <a class="nav-link 
              @if(!Request::user()->isResponsible())
                active
              @endisset
              " data-toggle="tab" href="#kt_apps_contacts_view_tab_2" role="tab">
              <i class="fa fa-qrcode"></i> QR Code Okuyucu
              </a>
            </li>
            @if(Request::user()->isResponsible())
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#kt_apps_contacts_view_tab_3" role="tab">
              <i class="fa fa-passport"></i> Turnike Sistemi
              </a>
            </li>
            @endisset
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div class="tab-content kt-margin-t-20">
          @if(Request::user()->isResponsible())
          <!--Begin:: Tab Content-->
          <div class="tab-pane active" id="kt_apps_contacts_view_tab_1" role="tabpanel">
            <table class="table table-striped- table-hover table-checkable" id="filtreli_form">
              <thead>
                <tr>
                  <th>#</th>
                  <th>İsim</th>
                  <th>Cevap Tarihi</th>
                  <th class="align-right" style="text-align: right;">İşlem</th>
                </tr>
              </thead>
              <tbody>
                @foreach($c->answers as $dd)
                <tr>
                  <td>
                    {{ $dd->contact_id }}
                  </td>
                  <td>
                  <strong>{{ $dd->name }} {{ $dd->surname }}</strong>
                    @if($dd->self)
                    <span class="kt-badge kt-badge--danger kt-badge--sm" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Liste dışı kayıt">
                      <i class="la la-info"></i>
                    </span>
                    @endif
                  </td>
                  <td>{{ date('d.m.Y', strtotime($dd->answer_date)) }}</td>
                  <td class="align-right" style="text-align: right;">
                    <a href="{{ route('contact-answers', ['contact_id' => $dd->contact_id]) }}" class="btn btn-sm btn-info " title="Cevaplar">
                      <i class="la la-list"></i> Cevaplar
                    </a>
                    <a href="{{ route('davetli-bilet', ['hash' => $dd->hash]) }}" target="_blank" class="btn btn-sm btn-dark " title="QR Code">
                      <i class="la la-qrcode"></i> Giriş Kartı
                    </a>
                  </td>
                </tr>
                @endforeach
              </tfoot>
            </table>
          </div>
          @endif

          <!--End:: Tab Content-->

          <!--Begin:: Tab Content-->
          <div class="tab-pane
          @if(!Request::user()->isResponsible())
            active
          @endif 
          " id="kt_apps_contacts_view_tab_2" role="tabpanel">
            <form class="kt-form qr-form" method="POST" action="{{ route('qr-reader') }}" >
              <input type="hidden" name="turnike" value="Manuel">
              <input type="hidden" name="form_id" value="{{ $c->id }}">
              @csrf
              <div class="form-group row">
                <div class="col-lg-5">
                    <input class="form-control" type="text" name="qr" >
                </div>
                <div class="col-lg-5">
                    <select name="type" id="" class="custom-select">
                      <option value="Giriş">Giriş</option>
                      <option value="Çıkış">Çıkış</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <button class="btn btn-success" type="submit">Kaydet</button>
                </div>
              </div>
            </form>
            <div class="tablo">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>İsim</th>
                    <th>Turnike</th>
                    <th>İşlem</th>
                    <th>Tarih</th>
                  </tr>
                </thead>
                <tbody class="qr-result">
                    @foreach($qr as $q)
                      <tr>
                        <td>{{ $q->contact->name }} {{ $q->contact->surname }}</td>
                        <td>{{ $q->turnike }}</td>
                        <td><span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-{{ $q->type == 'Giriş' ? 'success' : 'danger' }}">{{ $q->type }}</span></td>
                        <td>{{ $q->created_at }}</td>
                      </tr>
                    @endforeach
                </tbody>
              </table>
              <a href="{{ route('giris-cikis', ['form_id' => $c->id]) }}" class="btn btn-success btn-sm">Tümünü Görüntüle</a>
            </div>
          </div>
          <!--End:: Tab Content-->

          @if(Request::user()->isResponsible())
          <!--Begin:: Tab Content-->
          <div class="tab-pane" id="kt_apps_contacts_view_tab_3" role="tabpanel">
            Aşağıdaki bilgileri, turnike entegratörünüzle paylaşarak turnikelerin QR Code vasıtasıyla çalışmasını ve tüm-giriş çıkış kayıtlarının tutularak panelinizde raporlanmasını sağlayabilirsiniz.<br>
            
            Endpoint: {{ route('turnike-reader', ['turnike' => 'Turnike', 'form_id' => $c->id, 'type' => 'Giriş', 'qr' => 'qrcode']) }}
            <hr>
            <table class="table">
              <tr>
                <td>
                  <strong>turnike</strong>
                </td>
                <td>Turnikenin adı. Her turnikeye ayrı isim verebilir veya tümüne 'Turnike' verisi gönderebilirsiniz.</td>
              </tr>
              <tr>
                <td>
                  <strong>form_id</strong>
                </td>
                <td>
                  Bu veriyi değiştirmemelisiniz.
                </td>
              </tr>
              <tr>
                <td>
                  <strong>type</strong>
                </td>
                <td>
                  İki değer alabilir: Giriş, Çıkış. Biri girerken biri çıkarken gönderilir.
                </td>
              </tr>
              <tr>
                <td>
                  <strong>qr</strong>
                </td>
                <td>
                  Kişinin giriş kartıyla okuttuğu QR code aracılığıyla gönderilen hash'i temsil etmektedir.
                </td>
              </tr>
              <tr>
                <td>
                  <strong>Response</strong>
                </td>
                <td>
                  json formatında gönderilmektedir. status ve message isimli iki alan yer almaktadır.<br>
                  status alanı aşağıdaki değerleri alır:<br>
                  1: Başarılı giriş<br>
                  0: Yetkisiz giriş<br>
                  2: Kişi hiç çıkış gerçekleştirmeden ikinci kez giriş yapmayı denemekte. (Sizin tercihinize göre giriş yapabilir veya giriş engellenebilir.)
                </td>
              </tr>
            </table>
          </div>
          <!--End:: Tab Content-->
          @endif
        </div>
      </div>
    </div>

    <!--End:: Portlet-->
  </div>
</div>


{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script>
  $(document).ready(function(){
  $('.qr-form').ajaxForm({ 
    beforeSubmit:  function(){
      $(".qr-form button[type=submit]").attr('disabled', true);
        $(".formprogress").show();
    },
    error: function(){
      $(".qr-form button[type=submit]").removeAttr('disabled');
      swal.fire({
        "title": "",
        "text": "Kaydedilemedi",
        "type": "warning",
        "confirmButtonClass": "btn btn-secondary"
      });

    },
    dataType:  'json', 
    success:   function(item){
      $(".qr-form button[type=submit]").removeAttr('disabled');
        $(".formprogress").hide();
        if(item.status){
          $(".qr-form input[name=qr]").val('').focus();

          $(".qr-result").html('');

          $.each(item.data, function(i, data) {
             $('.qr-result').append('<tr>\
                <td>'+data.contact.name+' '+data.contact.surname+'</td>\
                <td>'+data.turnike+'</td>\
                <td><span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--unified-'+(data.type=='Giriş' ? 'success' : 'danger' )+'">'+data.type+'</span></td>\
                <td>'+data.created_at+'</td>\
              </tr>');
          });


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
}); 
</script>
@endsection