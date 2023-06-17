@extends("Reports.PDFTemplates.GeneralLayout")
@section("head-css-style")
    <style>
        table{
            width: 100%;
            height: 100%;
            text-align:center;
            padding:10px 5px;
        }
        th{
            color: white;
            background-color: #000
        }
        tr:nth-of-type(odd)
        {
            background-color:#888
        }
        td{white-space: nowrap}
        td , th{
            padding:10px 5px !important;
        }
    </style>
@endsection

@section("content")
    <table>
        <thead>
        <tr>
            @foreach($DataKeys as $key)
                <th>{{$key}}</th>
            @endforeach
        </tr>
        </thead>

        <tbody>
        @foreach($Data as $row)
            <tr>
                @foreach($row as $value)
                    <td>{{$value}}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>

    </table>
@endsection
