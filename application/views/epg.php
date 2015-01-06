            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Electronic Program Guide
                        <small>Control panel</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="<?=  base_url()?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?php echo base_url()."webtv/playlist/".$this->uri->segment(4) ?>"><i class="fa fa-dashboard"></i> Playlist</a></li>
                        <li class="active">EPG</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">


                    <div class="row">
                        <div class="col-md-3">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h4 class="box-title">Video list</h4>
                                </div>
                                <div class="box-body">
                                    <!-- the events -->
                                    <div id='external-events'>
                                        
                                        <?php
                                        $color = array(
                                            'bg-green',
                                            'bg-red',
                                            'bg-aqua',
                                            'bg-yellow',
                                            'bg-navy');
                                        
                                        foreach ($result as $key=>$value){ ?>
                                        <div class='external-event' style="background-color: <?=$value->color?>" id="<?=$value->id?>"><?=$value->title?></div><br>
                                        <?php } ?>
                                        
                                        <p class="loader"></p>
                                        <!-- <input type='checkbox' id='drop-remove' /> <label for='drop-remove'>remove after drop</label> -->
                                        
                                    </div>
                                    
                                </div><!-- /.box-body -->
                                
                            </div><!-- /. box -->
                            <a class="btn btn-warning" href="<?php echo base_url()."webtv/playlist/".$this->uri->segment(4) ?>"><i class="fa fa-mail-reply"></i> Back</a>
                        </div><!-- /.col -->
                        <div class="col-md-9">
                            <div class="box box-primary">                                
                                <div class="box-body no-padding">
                                    <!-- THE CALENDAR -->
                                    <div id="calendar"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /. box -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->  


                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <script src="<?=base_url()?>assets/js/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <!-- Page specific script -->
        <script type="text/javascript">
            $(function() {
                /* initialize the external events
                 -----------------------------------------------------------------*/
                
                var playlist_id = <?=$this->uri->segment(3);?>;
                $('#drop-remove').prop('checked', true);
                
                function ini_events(ele) {
                    ele.each(function() {

                        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                        // it doesn't need to have a start or end
                        var eventObject = {
                            title: $.trim($(this).text()) // use the element's text as the event title
                        };

                        // store the Event Object in the DOM element so we can get to it later
                        $(this).data('eventObject', eventObject);

                        // make the event draggable using jQuery UI
                        $(this).draggable({
                            zIndex: 1070,
                            revert: true, // will cause the event to go back to its
                            revertDuration: 0  //  original position after the drag
                        });

                    });
                }
                ini_events($('#external-events div.external-event'));

                /* initialize the calendar
                 -----------------------------------------------------------------*/
                //Date for the calendar events (dummy data)
                var date = new Date();
                var d = date.getDate(),m = date.getMonth(),y = date.getFullYear();
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaDay'
                    },
                    buttonText: {//This is to add icons to the visible buttons
                        prev: "<span class='fa fa-caret-left'></span>",
                        next: "<span class='fa fa-caret-right'></span>",
                        today: 'today',
                        month: 'month',
                        week: 'week',
                        day: 'day'
                    },
                    events: "<?=base_url()?>webtv/renderevent?playlist_id=" + playlist_id + '&',
                    minTime: 0,
                    maxTime: 24,
                    slotMinutes: 15,
                    editable: true,
                    droppable: true, // this allows things to be dropped onto the calendar !!!
                    drop : function(date, allDay, ui){
                        
                        // retrieve the dropped element's stored Event Object
                        var originalEventObject = $(this).data('eventObject');

                        // we need to copy it, so that multiple events don't have a reference to the same object
                        var copiedEventObject = $.extend({}, originalEventObject);

                        // assign it the date that was reported
                        copiedEventObject.id = $(this).attr("id");
                        copiedEventObject.start = date;
                        copiedEventObject.allDay = allDay;
                        copiedEventObject.backgroundColor = $(this).css("background-color");
                        copiedEventObject.borderColor = $(this).css("border-color");

                        // render the event on the calendar
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                        
                        __saveEvent(copiedEventObject);
                        
                        $(this).remove();
                        
                        // is the "remove after drop" checkbox checked?
                        /*
                        if ($('#drop-remove').is(':checked')) {
                            // if so, remove the element from the "Draggable Events" list
                            $(this).remove();
                        }
                        */
                    },
                    eventDrop: function(event, delta, revertFunc) {
                        event.action = 'update';
                        __saveEvent(event);
                    },
                    eventResize: function(event, delta, revertFunc) {
                        event.action = 'update';
                        __saveEvent(event);
                    },
                    eventClick : function(event, jsEvent, view) {
                        var r = confirm("Delete " + event.title);
                        if (r===true){
                            event.action = 'delete';
                            __saveEvent(event);
                        }
                    } 
                });
                
                function __saveEvent(event){
                    var subUrl = "<?=base_url()?>webtv/saveevent";
                    $('.loader').html('loading.....');
                    $.ajax({
                        url: subUrl,
                        type: "POST",
                        data: { id : event.id , playlist_id : <?=$playlist_id?>, title : event.title, start_date : event.start, end_date : event.end , action : event.action},
                        success: function(data, textStatus) {
                            if (event.action == 'delete'){
                                location.reload();
                            }else{
                                $('.loader').html('');    
                            }
                        },
                        error: function(data, textStatus) {
                            alert('An error has occured retrieving data!');
                        }
                    });
                }
            });
        </script>