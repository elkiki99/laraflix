Livewire.on("dataWasLoadedByLivewire", () => {
    const swiper = new Swiper(".swiper", {
        spaceBetween: 10,
        direction: "horizontal",
        loop: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
            1280: {
                slidesPerView: 6,
            },
        },
    });
});