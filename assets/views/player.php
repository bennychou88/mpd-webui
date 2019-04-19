<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
/* Show it is fixed to the top */
body {
  min-height: 75rem;
  padding-top: 4.5rem;
}
    </style>

    <title>Hello, world!</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#">&nbsp;</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="playPrev">Prev</button>
            <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="playPlay">Play</button>
            <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="playNext">Next</button>
            <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="playStop">Stop</button>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="container">
    <div class="row">
        <div class="col">
          <div class="card mx-auto">
            <div class="card-header">
            Queue
            </div>
            <div class="card-body">
                <ul id="playQueue">
                </ul>
            </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col">
          <div class="card mx-auto">
            <div class="card-header">
            Status
            </div>
            <div class="card-body">
            <ul id="playStatus">
                </ul>
            </div>
            </div>
        </div>
    </div>
    </main>
    <!--<div class="container-fluid fixed-bottom">
        <div class="row" style="background-color: red; height: 100px;">
            <div class="col">One</div>
        </div>
    </div>-->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        getQueue();
        getStatus();
        setInterval(function(){
                getQueue();
                getStatus();
        }, 5000);
        $('#playPrev').on('click', function(e){
            $.getJSON('player/prev');
        });
                
        $('#playPlay').on('click', function(e){
            $.getJSON('player/play');
        });

        $('#playNext').on('click', function(e){
            $.getJSON('player/next');
        });
        
        $('#playStop').on('click', function(e){
            $.getJSON('player/stop');
        });
    });
    
    function getQueue(){
        $('#playQueue').empty();
        $.getJSON('player/queue', function(resp){
            //alert(resp);
            for(var i=0;i<resp.length;i++){
                $('#playQueue').append('<li>'+resp[i]+'</li>');
            }
        });
    }
    function getStatus(){
        $('#playStatus').empty();
        $.getJSON('player/status', function(resp){
            $.each(resp, function(key, value){
                $.each(value, function(key, value){
                    $('#playStatus').append('<li>'+key+' : '+value+'</li>');
                });
            });
            
        });
    }
    </script>
  </body>
</html>