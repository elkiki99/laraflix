Livewire.on("livewireFetchedData", () => {
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
                slidesPerGroup: 1,
            },
            768: {
                slidesPerView: 3,
                slidesPerGroup: 2,
            },
            1024: {
                slidesPerView: 4,
                slidesPerGroup: 3,
            },
            1280: {
                slidesPerView: 6,
                slidesPerGroup: 5,
            },
        },
    });
});