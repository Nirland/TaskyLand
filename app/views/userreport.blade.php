@extends('layouts.report')
@section('content')
    @if (count($entities) > 0)
        <h3 class="text-center">Worktime of {{{$entities[0]->firstname}}}&nbsp;{{{$entities[0]->surname}}}</h3>
        <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th style="width: 150px;">Worktime, hours</td>
                <th>Task</td>
                <th>Project</th>
            </tr>    
        </thead> 
        <?php 
            $summary = 0; 
            $chartData = array();
            $chartLabels = array();
        ?>    
        @foreach ($entities as $entity)
            <tr>
                <td>{{$entity->worktime}}</td>
                <td>{{{$entity->task}}}</td>
                <td>{{{$entity->project}}}</td>
            </tr>
            <?php 
                $summary += $entity->worktime; 
                $chartData[] = (int)$entity->worktime;
                $chartLabels[] = $entity->task.' '. $entity->project;
            ?>    
        @endforeach
        <tr>
            <td colspan="3">Summary: {{$summary}}</td>
        </tr>
        </table>
    
        <script type="text/javascript">            
            var data = {
                labels : {{json_encode($chartLabels)}},
                datasets : [
                    {
                        fillColor : "rgba(59,26,169,0.5)",
                        strokeColor : "rgba(6,72,133,1)",
                        data : {{json_encode($chartData)}}
                    }		
                ]
            };
        </script>            
    @else
        <p>Not found</p>
    @endif
@stop