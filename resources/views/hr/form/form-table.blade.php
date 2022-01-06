{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

<style>
tr.koyu{
  background:#f7f7f7;
  color:#111;
  font-weight:bold;
}
</style>
<div class="container">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap">
            <div class="card-title">
                <h3 class="card-label">Cevaplar </h3>
            </div>
            <div class="card-toolbar">
              <a href="{{ route('form-answers', ['form_id' => $form_single->id]) }}" class="btn btn-light-primary btn-bold btn-icon-h kt-margin-l-10">
                Geri Dön
              </a>&nbsp;&nbsp;
              <a href="#" class="btn btn-light-success btn-bold btn-icon-h kt-margin-l-10 export">
                Excel Olarak Al
              </a>
            </div>
        </div>
        <div class="card-body" id="dvData">
        <table class="table table-striped- table-hover">
          <thead>
            <tr class="koyu">
              <td>#</td>
              @foreach($fields as $f)
              <td>{{ $f->label }}</td>
              @endforeach
              <td>Tarih</td>
            </tr>
          </thead>
          <tbody>
            @foreach($answers as $a)
              <tr>  
              <td>{{ $a->id }}</td>
              @foreach($a->answers as $an)
                <td>
                  @foreach($an->answers as $as)
                    {{ $as->answer ?? '' }}
                    @if($loop->last)
                        {{ '' }}
                    @else 
                        {{ ',' }}
                    @endif
                  @endforeach
                @php 
                  if(isset($an->answers[0])){
                    $tarih = $an->answers[0]->created_at;
                  }
                @endphp
                </td>
              @endforeach
              <td>{{ $tarih ?? '' }}</td>
              </tr>
            @endforeach
          </tfoot>
        </table>

        </div>
      </div>
      <!--end:: Widgets/Notifications-->
    </div>
  </div>
</div>
@endsection

{{-- Styles Section --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script>
$(document).ready(function() {

function exportTableToCSV($table, filename) {

  var $rows = $table.find('tr:has(td)'),

    // Temporary delimiter characters unlikely to be typed by keyboard
    // This is to avoid accidentally splitting the actual contents
    tmpColDelim = String.fromCharCode(11), // vertical tab character
    tmpRowDelim = String.fromCharCode(0), // null character

    // actual delimiter characters for CSV format
    colDelim = '","',
    rowDelim = '"\r\n"',

    // Grab text from table into CSV formatted string
    csv = '"' + $rows.map(function(i, row) {
      var $row = $(row),
        $cols = $row.find('td');

      return $cols.map(function(j, col) {
        var $col = $(col),
          text = $col.text();

        return text.replace(/"/g, '""'); // escape double quotes

      }).get().join(tmpColDelim);

    }).get().join(tmpRowDelim)
    .split(tmpRowDelim).join(rowDelim)
    .split(tmpColDelim).join(colDelim) + '"';

  // Deliberate 'false', see comment below
  if (false && window.navigator.msSaveBlob) {

    var blob = new Blob([decodeURIComponent(csv)], {
      type: 'text/csv;charset=utf8'
    });

    // Crashes in IE 10, IE 11 and Microsoft Edge
    // See MS Edge Issue #10396033
    // Hence, the deliberate 'false'
    // This is here just for completeness
    // Remove the 'false' at your own risk
    window.navigator.msSaveBlob(blob, filename);

  } else if (window.Blob && window.URL) {
    // HTML5 Blob        
    var blob = new Blob([csv], {
      type: 'text/csv;charset=utf-8'
    });
    var csvUrl = URL.createObjectURL(blob);

    $(this)
      .attr({
        'download': filename,
        'href': csvUrl
      });
  } else {
    // Data URI
    var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

    $(this)
      .attr({
        'download': filename,
        'href': csvData,
        'target': '_blank'
      });
  }
}

// This must be a hyperlink
$(".export").on('click', function(event) {
  // CSV
  var args = [$('#dvData>table'), 'export.csv'];

  exportTableToCSV.apply(this, args);

  // If CSV, don't do event.preventDefault() or return false
  // We actually need this to be a typical hyperlink
});
});
</script>
@endsection