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
      flex: 0 0 20%;
      height: 100%;
      overflow: hidden;
      transition: flex 0.3s ease-in-out;
      border: 1px solid white;
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

    .slider .slides .slide {
      position: relative;
    }

    .slider .slides .slide .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      opacity: 0;
      transition: opacity 0.3s ease-in-out;
    }

    .slider .slides .slide:hover .overlay {
      opacity: 1;
    }

    .slider .slides .slide .content {
      position: absolute;
      bottom: 48px;
      left: 144px;
      color: white;
      z-index: 2;
    }

    .slider .slides .slide .content h2 {
      font-size: 24px;
      margin-bottom: 10px;
    }

    .slider .slides .slide .content p {
      font-size: 16px;
      margin-bottom: 20px;
    }

    .slider .slides .slide .content .buttons {
      display: flex;
      gap: 10px;
    }

    .slider .slides .slide .content .buttons a {
      display: inline-block;
      padding: 10px 20px;
      background-color: #fff;
      color: red;
      text-decoration: none;
      border-radius: 4px;
      font-weight: 800;
      font-size: 16px;
    }
  </style>

  <ul class="slides">
    <?php
    foreach ($slides as $slide) {
      echo '<li class="slide">';
      echo "<img src='$slide'>";
      echo '<div class="overlay"></div>';
      echo '<div class="content">';
      echo '<h2>Dynamic Header</h2>';
      echo '<p>Dynamic Paragraph</p>';
      echo '<div class="buttons">';
      echo '<a href="#">Button 1</a>';
      echo '<a href="#">Button 2</a>';
      echo '</div>';
      echo '</div>';
      echo '</li>';
    }
    ?>
  </ul>

  <div>Hello</div>
</div>

<script>
  // JavaScript code

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
          slide[j].style.flex = '0 0 20%';
        }
      }
    });
  }

  slide[0].addEventListener('mouseleave', () => {
    for (let j = 0; j < slide.length; j++) {
      if (j === 0) {
        slide[j].style.flex = '0 0 80%';
      } else {
        slide[j].style.flex = '0 0 20%';
      }
    }
  });

  // Function to update content
  function updateSlideContent(slideIndex, header, paragraph, button1Label, button1URL, button2Label, button2URL) {
    const slides = document.querySelectorAll('.slider .slides .slide');

    if (slideIndex >= 0 && slideIndex < slides.length) {
      const slide = slides[slideIndex];
      const headerElement = slide.querySelector('.content h2');
      const paragraphElement = slide.querySelector('.content p');
      const buttons = slide.querySelectorAll('.content .buttons a');

      headerElement.textContent = header;
      paragraphElement.textContent = paragraph;

      buttons[0].textContent = button1Label;
      buttons[0].href = button1URL;
      buttons[1].textContent = button2Label;
      buttons[1].href = button2URL;
    }
  }

  // Example usage
  updateSlideContent(
    0,
    "Welcome to Slide 1",
    "This is the first slide. Update the fields below.",
    "Custom Button 1",
    "#custom-url-1",
    "Custom Button 2",
    "#custom-url-2"
  );
</script>