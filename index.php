<?php
// find all the images in the folder and add them to the slides array
$slides = array();

if ($handle = opendir('.')) {
  while (false !== ($file = readdir($handle))) {
    if (preg_match("/.jpg$/", $file)) {
      $slides[] = $file;
    }
  }
  closedir($handle);
}
?>

<div class="slider">
  <style>
    .slider {
      width: 100%;
      height: 500px;
      overflow: hidden;
      position: relative;
    }

    .slides {
      list-style-type: none;
      width: 100%;
      height: 100%;
      padding: 0;
      margin: 0;
      transition: transform 0.3s ease-in-out;
      display: flex;
    }

    .slides>li {
      flex: 0 0 10%;
      height: 100%;
      overflow: hidden;
      transition: flex 0.3s ease-in-out;
    }

    .slides>li:first-child {
      flex: 0 0 80%;
    }

    .slides>li:hover {
      flex: 0 0 80%;
    }

    .slider img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease-in-out;
    }

    .slider img:hover {
      transform: scale(1.1);
    }
  </style>

  <ul class="slides">
    <?php
    foreach ($slides as $slide) {
      echo "<li><img src='$slide'></li>";
    }
    ?>
  </ul>

  <div>Hello</div>
</div>

<script>
  const slides = document.querySelector('.slides');
  const slide = document.querySelectorAll('.slides li');
  let counter = 1;
  const size = slide[0].clientWidth * 0.1;

  slides.style.transform = 'translateX(' + (-size * counter) + 'px)';

  slides.addEventListener('transitionend', () => {
    if (counter === slide.length - 1) {
      slides.style.transition = "none";
      counter = 1;
      slides.style.transform = 'translateX(' + (-size * counter) + 'px)';
    }
    if (counter === 0) {
      slides.style.transition = "none";
      counter = slide.length - 2;
      slides.style.transform = 'translateX(' + (-size * counter) + 'px)';
    }
  });

  for (let i = 1; i < slide.length; i++) {
    slide[i].addEventListener('mouseover', () => {
      for (let j = 0; j < slide.length; j++) {
        if (j === i) {
          slide[j].style.flex = '0 0 80%';
        } else {
          slide[j].style.flex = '0 0 10%';
        }
      }
    });
  }

  slide[0].addEventListener('mouseleave', () => {
    for (let j = 0; j < slide.length; j++) {
      if (j === 0) {
        slide[j].style.flex = '0 0 80%';
      } else {
        slide[j].style.flex = '0 0 10%';
      }
    }
  });
</script>