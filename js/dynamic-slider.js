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