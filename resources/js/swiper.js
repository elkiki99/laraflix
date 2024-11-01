Livewire.on("livewireFetchedData", () => {
    const swiper = new Swiper(".swiper", {
        spaceBetween: 10,
        slidesPerView: 2,
        direction: "horizontal",
        loop: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: {
                slidesPerView: 3,
                slidesPerGroup: 2,
            },
            768: {
                slidesPerView: 4,
                slidesPerGroup: 3,
            },
            1024: {
                slidesPerView: 5,
                slidesPerGroup: 4,
            },
            1280: {
                slidesPerView: 6,
                slidesPerGroup: 5,
            },
        },
    });
});