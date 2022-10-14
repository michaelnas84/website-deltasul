    <link rel="stylesheet" type="text/css" href="css/oferta_da_semana.css<?= '?'.bin2hex(random_bytes(50))?>">
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="js/kinetic.js"></script>
    <script type="text/javascript" src="js/jquery.final-countdown.js"></script>
    <script type="text/javascript">  
        $('document').ready(function() {
            'use strict';
            
            $('.countdown').final_countdown({
                'start': Math.floor((new Date("<?= date('m/d/Y') ?> 00:00:00")).getTime() / 1000),
                'end': Math.floor((new Date("<?= date('m/d/Y') ?> 23:59:59")).getTime() / 1000),
                'now': Math.floor((new Date).getTime() / 1000) 
            });
        });
    </script>

<div class="timer_popup">
   <div class="timer_popup_box">
      <p style="color: white; padding-bottom: 20px">Tempo para esta oferta encerrar</p>
      <div class="countdown countdown-container container">
         <div class="clock row" style="display: flex;justify-content: center;">
         <div class="clock-item clock-days countdown-time-value col-sm-6 col-md-3" style="opacity: 0;position: absolute;">
               <div class="wrap">
                  <div class="inner">
                     <div id="canvas-days" class="clock-canvas"></div>
                     <div class="text">
                        <p class="val">0</p>
                        <p class="type-days type-time">d</p>
                     </div>
                     <!-- /.text -->
                  </div>
                  <!-- /.inner -->
               </div>
               <!-- /.wrap -->
            </div>
            <!-- /.clock-item -->
            <div class="clock-item clock-hours countdown-time-value col-sm-6 col-md-3">
               <div class="wrap">
                  <div class="inner">
                     <div id="canvas-hours" class="clock-canvas"></div>
                     <div class="text">
                        <p class="val">0</p>
                        <p class="type-hours type-time">h</p>
                     </div>
                     <!-- /.text -->
                  </div>
                  <!-- /.inner -->
               </div>
               <!-- /.wrap -->
            </div>
            <!-- /.clock-item -->
            <div class="clock-item clock-minutes countdown-time-value col-sm-6 col-md-3">
               <div class="wrap">
                  <div class="inner">
                     <div id="canvas-minutes" class="clock-canvas"></div>
                     <div class="text">
                        <p class="val">0</p>
                        <p class="type-minutes type-time">m</p>
                     </div>
                     <!-- /.text -->
                  </div>
                  <!-- /.inner -->
               </div>
               <!-- /.wrap -->
            </div>
            <!-- /.clock-item -->
            <div class="clock-item clock-seconds countdown-time-value col-sm-6 col-md-3">
               <div class="wrap">
                  <div class="inner">
                     <div id="canvas-seconds" class="clock-canvas"></div>
                     <div class="text">
                        <p class="val">0</p>
                        <p class="type-seconds type-time">s</p>
                     </div>
                     <!-- /.text -->
                  </div>
                  <!-- /.inner -->
               </div>
               <!-- /.wrap -->
            </div>
            <!-- /.clock-item -->
         </div>
         <!-- /.clock -->
      </div>
      <!-- /.countdown-wrapper -->
   </div>
</div>