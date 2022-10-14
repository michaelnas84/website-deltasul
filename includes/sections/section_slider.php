<?php include('includes/api_slider.php'); ?>
<link rel="stylesheet" href="css/slider.css">

  <div class="slideshow-container">
    <?php for($xx=0; $xx < $tot_sliders; $xx++) { ?>
        <div class="mySlides fade" style='width: 100%; max-height: 21vw; overflow: hidden;'><img <?php if($slider['url'][$xx] != null) { $cursor = 'cursor: pointer'; ?> onclick="location.href='<?= $slider['url'][$xx]?>'"<?php } ?> style='width: 100vw; background-position: center; <?= $cursor ?>' src='img_base/slider/<?= $slider['url_arq'][$xx] ?>'></div>
    <?php } ?>

    <a class="prev" OnClick="plusSlides(-1)">&#10094;</a>
    <a class="next" OnClick="plusSlides(1)">&#10095;</a>

    </div>

    <div style="text-align:center">
    <?php for($xx=1; $xx <= $tot_sliders; $xx++) { ?>
    <span class="dot" onclick="currentSlide(<?= $xx ?>)"></span> 
    <?php } ?>
    </div>

    <script src="js/slider.js"></script>