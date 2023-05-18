<?php
$slides = array(
  "Image1.jpg",
  "Image1.jpg",
  "Image3.jpg"
);
?>

<div class="slider">
  <style>
    .slider {
      width: 100%;
      height: 500px;
      overflow: hidden;
    }

    .slides {
      list-style-type: none;
      width: 100%;
      height: 100%;
      padding: 0;
      margin: 0;
      transition: transform 0.3s ease-in-out;
    }

    .slides>li {
      float: left;
      width: 100%;
      height: 100%;

    }

    .slider .slides li {
      display: block;
    }


    .slider img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .arrow {

      background-color: rgba(0, 0, 0, 0.5);
      color: #fff;
      font-size: 30px;
      text-align: center;
      line-height: 50px;
      cursor: pointer;
    }

    .slider-nav {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      justify-content: center;
      align-items: center;
    }
  </style>

  <ul class="slides">
    <?php
    foreach ($slides as $slide) {
      echo "<li><img src='./$slide'></li>";
    }
    ?>
  </ul>


  <div class="slider-nav">
    <span class="prev-button">left</span>
    <span class="next-button">right</span>
  </div>

  <div>Hello</div>
</div>

<script>
  // Get the necessary elements
  const slider = document.querySelector('.slider');
  const slides = slider.querySelector('.slides');
  const slideItems = slides.querySelectorAll('li');

  // Set up initial variables
  let currentSlide = 0;
  const slideWidth = slideItems[0].offsetWidth;

  // Function to move to the next slide
  function nextSlide() {
    currentSlide = (currentSlide + 1) % slideItems.length;
    slides.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
  }

  // Function to move to the previous slide
  function prevSlide() {
    currentSlide = (currentSlide - 1 + slideItems.length) % slideItems.length;
    slides.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
  }

  // Add event listeners for navigation
  document.querySelector('.next-button').addEventListener('click', nextSlide);
  document.querySelector('.prev-button').addEventListener('click', prevSlide);

  // Autoplay the slider
  setInterval(nextSlide, 3000);
</script>