@extends("Reports.PDFTemplates.GeneralLayout")

@section("head-css-style")
    <style>
        .card
        {
            max-width: 300px;
            min-height: 250px;
            padding: 30px;
            border-radius: 3px;
            box-sizing: border-box;
            box-shadow: 2px 2px 5px 5px #999;
            margin:20px auto 30px;
        }
        .card .info-row
        {
            margin:10px auto !important;
            /*display: flex;*/
            /*justify-content: space-around;*/
        }
        .card .info-row span
        {
            display: block;
            padding:15px 10px;
            min-width: 20%;
        }
        .card .info-row .info-row-key
        {
            font-weight: bold !important;
        }
        .card .info-row .info-row-value
        {
            font-weight : normal !important;
            border-bottom: 1px dotted #333;
        }
    </style>
@endsection

@section("content")
    @foreach($Data as $row)
        <div class="card">
            @foreach($row as $key => $value)
                <div class="info-row">
                    <span class="info-row-key">{{$key}}  : </span>
                    <span class="info-row-value">{{$value}}</span>
                </div>
            @endforeach
        </div>
    @endforeach
@endsection
