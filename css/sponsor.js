let slideIndex = 1;
showSlides(slideIndex);

function changeSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("slide");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {
    slideIndex = 1
  }
  if (n < 1) {
    slideIndex = slides.length
  }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";
}

/*sponsor page */
const openPopUpsButtons = document.querySelectorAll('[data-info-target]');
const closePopUpsButtons = document.querySelectorAll('[data-close-button]');
const pageOverlay = document.getElementById('page-overlay');

openPopUpsButtons.forEach(button => {
  button.addEventListener('click', () => {
    const popUp = document.querySelector(button.dataset.popupInfoTarget);
    openPupUp(popupInfo);

  })
})

pageOverlay.addEventListener('click', () => {

  //pop up screen 1
  const popUps = document.querySelectorAll('.sponsor-popup.page-active');
  popUps.forEach(popUp => {
    closePupUp(popupInfo);
  })
  /**pop uo sceen 2 */
  //pop up screen 2
  const popUps2 = document.querySelectorAll('.sponsor-popup.page-active');
  popUps2.forEach(popUp2 => {
    closePupUp(popupInfo2);
  })
  // pop up screen 3
  const popUps3 = document.querySelectorAll('.sponsor-popup.page-active');
  popUps3.forEach(popUp3 => {
    closePupUp(popupInfo3);
  })
   // pop up screen 4
   const popUps4 = document.querySelectorAll('.sponsor-popup.page-active');
   popUps3.forEach(popUp3 => {
     closePupUp(popupInfo4);
   })
})

closePopUpsButtons.forEach(button => {
  button.addEventListener('click', () => {
    // popup screen 1
    const popUp = button.closest('.sponsor-popup');
    closePupUp(popupInfo);

    // popup screen 2
    const popUp2 = button.closest('.sponsor-popup');
    closePupUp(popupInfo2);

    // popup screen 3
    const popUp3 = button.closest('.sponsor-popup');
    closePupUp(popupInfo3);
    // popup screen 4
    const popUp4 = button.closest('.sponsor-popup');
    closePupUp(popupInfo4);
  })
})


function openPupUp(popupInfo) {
  if (popupInfo == null) return
  popupInfo.classList.add('page-active');
  pageOverlay.classList.add('page-active');

}


function closePupUp(popupInfo) {
  if (popupInfo == null) return
  popupInfo.classList.remove('page-active');
  pageOverlay.classList.remove('page-active');

}

//screen2

const openPopUpsButtons2 = document.querySelectorAll('[data-info2-target]');

openPopUpsButtons2.forEach(button => {
  button.addEventListener('click', () => {
    const popUp2 = document.querySelector(button.dataset.popupInfo2Target);
    openPupUp(popupInfo2);

  })
})


function openPupUp(popupInfo2) {
  if (popupInfo2 == null) return
  popupInfo2.classList.add('page-active');
  pageOverlay.classList.add('page-active');

}


function closePupUp(popupInfo2) {
  if (popupInfo2 == null) return
  popupInfo2.classList.remove('page-active');
  pageOverlay.classList.remove('page-active');

}

// screen 3
const openPopUpsButtons3 = document.querySelectorAll('[data-info3-target]');

openPopUpsButtons3.forEach(button => {
  button.addEventListener('click', () => {
    const popUp3 = document.querySelector(button.dataset.popupInfo3Target);
    openPupUp(popupInfo3);

  })
})

function openPupUp(popupInfo3) {
  if (popupInfo3 == null) return
  popupInfo3.classList.add('page-active');
  pageOverlay.classList.add('page-active');

}

function closePupUp(popupInfo3) {
  if (popupInfo3 == null) return
  popupInfo3.classList.remove('page-active');
  pageOverlay.classList.remove('page-active');

}

// screen 3
const openPopUpsButtons4 = document.querySelectorAll('[data-info4-target]');

openPopUpsButtons4.forEach(button => {
  button.addEventListener('click', () => {
    const popUp4 = document.querySelector(button.dataset.popupInfo4Target);
    openPupUp(popupInfo4);

  })
})

function openPupUp(popupInfo4) {
  if (popupInfo4 == null) return
  popupInfo4.classList.add('page-active');
  pageOverlay.classList.add('page-active');

}

function closePupUp(popupInfo4) {
  if (popupInfo4 == null) return
  popupInfo4.classList.remove('page-active');
  pageOverlay.classList.remove('page-active');

}