<link rel="stylesheet" href="css/slider.css">

    <div id="slideshow-container" class="slideshow-container">

      <a class="prev" OnClick="plusSlides(-1)">&#10094;</a>
      <a class="next" OnClick="plusSlides(1)">&#10095;</a>

    </div>

    <div class="dots_slider" style="text-align:center; position: absolute; width: 100%; margin-top: -50px;">

    <span class="dot" OnClick="currentSlide()"></span> 

    </div>

    <script src="js/slider.js<?= '?'.bin2hex(random_bytes(50))?>"></script>