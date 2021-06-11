@extends('layouts.dashboard.app')
@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.semanticui.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.semanticui.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css/hackers.css')}}">
@endsection

@section('content')
    <div class="container  pt-3 pb-7">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center font-weight-900 text-xl"> Hackers </h1>
            </div>
            <div class="col-md-10">

            </div>
            <div class="col-md-10">
            <table class="table table-sm table-condensed mb-0" id="hackerTable">
            <thead class="bg-tsk-o-1">
                <tr>
                    <th>Sr</th>
                    <th>last_name</th>
                    <th>last_name</th>
                    <th>email</th>
                    <th>sex</th>
                    <th>phone_number Id</th>
                    <th>skills</th>
                    <th> github</th>
                 
                    <th style="width: 50px; text-align:center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hackers as $key => $hacker)
                    <tr>
                    
                    <td> 1</td>
                
                        <td>{{$hacker->first_name}}</td>
                        <td>{{$hacker->last_name}}</td>
                        <td>{{$hacker->email}}</td>
                        <td>{{$hacker->sex}}</td>
                        <td>{{$hacker->phone_number}}</td>
                    
                        <td>{{ucfirst($hacker->skills)}}</td>
                        <td>
                        {{$hacker-> github }}
                        </td>
                        <td> 
                       <a href="">  <Button:submit class= "btn btn-primary" class="fa fa-trash"> Send Email</Button:submit> </a>
                       <a href="">  <Button:submit class= "btn btn-primary" class="fa fa-trash"> Send Message</Button:submit> </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
            </div>
        </div>
    </div>

@endsection
@push('js')
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<script>

    $(document).ready( function () {
        $('#hackerTable').DataTable({
            columnDefs: [{
                orderable: true,
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
            },
            {
                orderable: false,
                targets: [9]
            }
            ],
        });
    } );

</script>
    <!-- DataTable Scripts -->
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.semanticui.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.semanticui.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.2/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.2/js/responsive.semanticui.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{asset('js/dashboard/datatable.js')}}"></script>
    <!-- Set Decision Script -->
    <script type="text/javascript">
        const token = '{{csrf_token()}}';
    </script>
    <script src="{{asset('js/dashboard/set-decision.js')}}"></script>

@endpush
