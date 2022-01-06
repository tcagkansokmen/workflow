@extends('hr.candidate.detail')

@section('inside')
<div class="kt-grid__item kt-grid__item--fluid kt-app__content">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">Aday Bilgileri <small>personele ait hesap bilgilerini görütnüleyebilirsiniz.</small></h3>
          </div>
          <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
            </div>
          </div>
        </div>
          <form class="kt-form general-form" method="POST" action="{{ route('mulakat-degerlendirme-kaydet') }}" >
          @csrf
          <input type="hidden" name="id" value="{{ $interview->id ?? '' }}">
          <input type="hidden" name="candidate_id" value="{{ $candidate_id ?? '' }}">
          <input type="hidden" name="type" value="rating">
           <div class="card-body">
            

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
            <h5 class="kt-font-dark">Yetkinlik Değerlendirme</h5>
            @foreach($perfections as $p)
            <div class="form-group row" style="margin-top:15px;">
              <label class="col-xl-3 col-lg-3 col-form-label">{{ $p->perfection->name }}</label>
              <div class="col-lg-9">
                <input type="hidden" name="rating[{{ $loop->iteration }}][perfection_id]" value="{{ $p->perfection->id }}">
                <div class="rate">
                  <input class="rating" type="hidden" name="rating[{{ $loop->iteration }}][rating]" value="{{ $p->rating ? round($p->rating) : 0 }}">
                    <div class="simple-rating star-rating">
                      <i class="fa fa-star" data-rating="1"></i>
                      <i class="fa fa-star" data-rating="2"></i>
                      <i class="fa fa-star" data-rating="3"></i>
                      <i class="fa fa-star" data-rating="4"></i>
                      <i class="fa fa-star" data-rating="5"></i>
                    </div>

                  <textarea id="" cols="30" rows="2" name="rating[{{ $loop->iteration }}][notes]" class="form-control" placeholder="Notlarınız" style="margin-top:15px;">{{ $p->notes ?? null }}</textarea>
                </div>
              </div>
            </div>
            <hr>
            @endforeach
            
            <div class="kt-divider">
                <span></span>
                <hr>
                <span></span>
            </div>
            <h5 class="kt-font-dark">Sonuç</h5>
            <div class="form-group row ">
              <label class="col-xl-3 col-lg-3 col-form-label">* Görüş</label>
              <div class="col-lg-4">
                <div class="kt-radio-inline">
                  <label class="kt-radio kt-radio--bold kt-radio--success">
                    <input type="radio" name="status" value="olumlu_mulakat"
                    @if(isset($interview['status']))
                      @if($interview['status'] == "olumlu_mulakat")
                        checked
                      @endif
                    @endif> Olumlu
                    <span></span>
                  </label>
                  <label class="kt-radio kt-radio--bold kt-radio--success">
                    <input type="radio" name="status" value="olumsuz_mulakat"
                    @if(isset($interview['status']))
                      @if($interview['status'] == 'olumsuz_mulakat')
                        checked
                      @endif
                    @endif> Olumsuz
                    <span></span>
                  </label>
                  <label class="kt-radio kt-radio--bold kt-radio--success">
                    <input type="radio" name="status" value="randevu_gelmedi"
                    @if(isset($interview['status']))
                      @if($interview['status'] == 'randevu_gelmedi')
                        checked
                      @endif
                    @endif> Randevuya Gelmedi
                    <span></span>
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group row" style="margin-top:15px;">
              <label class="col-xl-3 col-lg-3 col-form-label">* Genel Notlar</label>
              <div class="col-lg-9">
                  <textarea class="form-control summernote" type="text" required name="notes"  placeholder="Açıklama">{{ $interview->notes ?? '' }}</textarea>
              </div>
            </div>

            <div class="kt-divider">
                <span></span>
                <hr>
                <span></span>
            </div>
            @isset($interview->id)

            <div class="repeater">
              <div class="form-group form-group-last row ">
                <label class="col-lg-3 col-form-label">Dosyalar:</label>
                <div data-repeater-list="file" class="col-lg-9">
                  
                  @if(count($candidate_files))
                  @foreach($candidate_files as $p)
                  <div data-repeater-item class="form-group row align-items-center">
                    <div class="col-md-4">
                    <input type="hidden" name="id" value="{{ $p->id }}">
                      <div class="kt-form__group--inline">
                        <div class="kt-form__control">
                          <input type="text" class="form-control" name="title" value="{{ $p->title ?? '' }}" placeholder="Dosya Adı">
                        </div>
                      </div>
                      <div class="d-md-none kt-margin-b-10"></div>
                    </div>
                    <div class="col-md-4">
                      <div class="file_side">
                        <a target="_blank" href="{{ Storage::url('uploads/candidate') }}/{{ $p->file }}" class="custom-file-upload btn btn-bold btn-md btn-label-brand" style="margin-bottom:0;">
                        <i class="la la-search"></i> Görüntüle
                        </a>
                      </div>
                      <div class="d-md-none kt-margin-b-10"></div>
                    </div>
                    <div class="col-md-2">
                      <a href="javascript:;" data-repeater-delete="" class="btn-sm btn-icon btn btn-label-danger btn-bold">
                        <i class="la la-trash-o"></i>
                      </a>
                    </div>
                  </div>
                  @endforeach
                  @endif

                  @if($demand->status != 'kapatıldı')
                  <div data-repeater-item class="form-group row align-items-center">
                    <div class="col-md-4">
                      <div class="kt-form__group--inline">
                        <div class="kt-form__control">
                          <input type="text" class="form-control" name="title" placeholder="Dosya Adı">
                        </div>
                      </div>
                      <div class="d-md-none kt-margin-b-10"></div>
                    </div>
                    <div class="col-md-4">
                      <div class="file_side">
                      <input type="hidden" class="file_input" name="file_input" >
                              <label for="file-upload" class="custom-file-upload btn btn-bold btn-md btn-label-brand" style="margin-bottom:0;">
                              <i class="la la-plus"></i> Dosya Ekle
                          </label>
                          <input id="file-upload" type="file" accept=".pdf"/>
                      </div>
                      <div class="d-md-none kt-margin-b-10"></div>
                    </div>
                    <div class="col-md-2">
                      <a href="javascript:;" data-repeater-delete="" class="btn-sm btn-icon btn btn-label-danger btn-bold">
                        <i class="la la-trash-o"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group form-group-last row">
                <label class="col-lg-3 col-form-label"></label>
                <div class="col-lg-4">
                  <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                    <i class="la la-plus"></i> Yeni Ekle
                  </a>
                </div>
              </div>
              @endif
            </div>
            @endisset

          </div>
          <div class="kt-portlet__foot">
          <button class="btn btn-success" type="submit">Kaydet</button>
          </div>
          </form>
      </div>
    </div>
  </div>
</div>
@endsection