function plusSlides(n) {
    showSlides(slideIndex += n)
}

function plusSlidesaut() {
    showSlides(slideIndex += 1)
}

setInterval(plusSlidesaut, 7000)

function currentSlide(n) {
    showSlides(slideIndex = n)
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides")
    var dots = document.getElementsByClassName("dot")
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none"
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "")
    }
    slides[slideIndex - 1].style.display = "flex"
    dots[slideIndex - 1].className += " active"
}

fetch('includes/api_slider.php')
.then(async response => {
  try {
    const retorno = await response.json()
    Object.entries(retorno).forEach(([key, value]) => {
        adiciona_slide(value, key);
      })
  } catch (error) {
    console.error(error)
    return false
  }
})

function adiciona_slide(item, key){
    document.getElementById('slideshow-container').innerHTML += `
    <div id="slider_${key}" OnClick="window.open('${item['URL']}')" class="mySlides fade estilo_extra">
        <img id="img_${key}">
    </div>`
    if(window.screen.width < 992){
        if(item['URL_ARQ_MOBILE']){
            document.getElementById(`img_${key}`).src = `img_base/slider/${item['URL_ARQ_MOBILE']}`
        } else {
            document.getElementById(`img_${key}`).src = `img_base/slider/${item['URL_ARQ']}`
        }
    } else {
        document.getElementById(`img_${key}`).src = `img_base/slider/${item['URL_ARQ']}`
    }
    if(key > 0){ document.getElementById(`slider_${key}`).style.display = 'none' }
    if(item['URL']){ document.getElementById(`slider_${key}`).style.cursor = 'pointer' }
}

var slideIndex = 1;
showSlides(slideIndex)
