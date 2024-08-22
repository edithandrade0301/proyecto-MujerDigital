
//carousel
document.addEventListener("DOMContentLoaded", function() {
    const track = document.querySelector('.carousel-track');
    const items = document.querySelectorAll('.carousel-item');
    const totalItems = items.length;
    const nextBtn = document.querySelector('.sig');
    const prevBtn = document.querySelector('.ant');
    let currentIndex = 0;

    // siguiente 
    nextBtn.addEventListener('click', () => {
        moveSlide('sig');
    });

    // anterior
    prevBtn.addEventListener('click', () => {
        moveSlide('ant');
    });

    function moveSlide(direction) {
        if (direction === 'sig') {
            currentIndex++;
            if (currentIndex === totalItems) {
                currentIndex = 0;
            }
        } else if (direction === 'ant') {
            currentIndex--;
            if (currentIndex < 0) {
                currentIndex = totalItems - 1;
            }
        }

        updateCarousel();
    }

    function updateCarousel() {
        const itemWidth = items[currentIndex].clientWidth;
        const trackWidth = track.clientWidth;
        const itemOffset = currentIndex * itemWidth;

        if (itemOffset + trackWidth > track.scrollWidth) {
            track.style.transform = `translateX(-${track.scrollWidth - trackWidth}px)`;
        } else {
            track.style.transform = `translateX(-${itemOffset}px)`;
        }
    }
});



