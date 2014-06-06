<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TaskyLand Report</title>
  {{ HTML::style('css/bootstrap.min.css') }}
  {{ HTML::style('css/report.css') }} 
  {{ HTML::script('js/Chart.min.js') }}
</head>
<body>     
    <div class="container">
        <div id="reportdata">
            @yield('content')
        </div>
        @section('chart')
            <div id="chart">
                <canvas id="cnvChart" width="800" height="600"></canvas>
            </div>
    
            <script type="text/javascript">
                if (data !== 'undefined'){
                    var ctx = document.getElementById("cnvChart").getContext("2d");
                    var steps = Math.max.apply(Math, data.datasets[0].data);
                    var chart = new Chart(ctx).Bar(data, {scaleOverride: true, 
                                                        scaleStepWidth: 1, 
                                                        scaleSteps: steps});
                }                
            </script>
        @show  
    </div>    
</body>
</html>