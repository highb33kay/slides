
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
