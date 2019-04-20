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
        <div class="col">
          <div class="card mx-auto">
            <div class="card-header">
            Add file uri
            </div>
            <div class="card-body">
                <form>
                <div class="form-group">
                  <label for="">URI</label>
                  <textarea class="form-control" id="fileURI" rows="3"></textarea>
                </div>
                <button type="button" id="addToQueue" class="btn btn-primary">Add</button>
              </form>
              <form class="form-inline">
                <div class="form-group mb-2">
                  <label for="removeSong" class="sr-only">Song POS</label>
                  <input type="text" class="form-control-plaintext" id="removeSongId">
                </div>
                <button type="button" id="removeSong" class="btn btn-primary mb-2">Remove</button>
            </form>
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
        
        $('#addToQueue').on('click', function(e){
            var uri = $('#fileURI').val();
            
            $.getJSON('player/queueAdd', {uri: uri}, function(data){
                alert("Id = " + data[0].Id);
                $('#fileURI').val('');
            });
        });
        $('#removeSong').on('click', function(e){
            var id = $('#removeSongId').val();
            
            $.getJSON('player/queueDelOne', {id: id}, function(data){
                $('#removeSongId').val('');
            });
        });
    });
    
    function getQueue(){
        $('#playQueue').hide();
        $('#playQueue').empty();
        $.getJSON('player/queue', function(resp){
            //alert(resp);
            for(var i=0;i<resp.length;i++){
//                if($('#queue_'+(i+1)).length){
//                    alert('Found');
//                }else{
//                    $('#playQueue').append('<li id="queue_'+(i + 1)+'">'+resp[i]+'</li>');
//                }
                $('#playQueue').append('<li id="queue_'+(i + 1)+'">'+resp[i]+'</li>');
            }
            $('#playQueue').show();
        });
    }
    function getStatus(){
        $('#playStatus').hide("slow");
        $('#playStatus').empty();
        $.getJSON('player/status', function(resp){
            $.each(resp, function(key, value){
                $.each(value, function(key, value){
                    $('#playStatus').append('<li>'+key+' : '+value+'</li>');
                });
            });
            $('#playStatus').show();
        });
    }
    </script>
  </body>
</html>