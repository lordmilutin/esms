@extends("admin/main")

@section("page_title")
All Tournaments
@stop

@section("content")
<?php $i=1; ?>
<div class="row">
    <div class="col-md-12">
        <div class="well well-sm">
            <h2 class="notopmargin">All Tournaments</h2>
        </div>
        <table class="table table-striped table-hover " style="background-color:#222222 ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tournament name</th>
                    <th>Total prize</th>
                    <th>Starting</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tournaments as $t)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td><a href="{{URL::Route('adminEditTournament', $t->tournamentID)}}">{{ $t->name}}</a></td>
                    <td>{{  number_format((int)$t->prizepool, 0, ',', ', ')   }} $</td>
                    <td>{{ date("d. M Y", strtotime($t->starting))}}</td>
                    <td>
                        <a href="{{URL::Route('adminTournamentApplies', $t->tournamentID)}}"><button type="button" class="btn btn-info">Manage</button></a>
                        <a href="{{URL::Route('adminEditTournament', $t->tournamentID)}}"><button type="button" class="btn btn-warning" style="margin-left:10px">Edit</button></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table> 

        {{ $tournaments->links() }}

    </div>
</div>


@stop