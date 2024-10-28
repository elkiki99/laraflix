document.addEventListener("DOMContentLoaded", () => {
    const swiper = new Swiper(".swiper", {
        slidesPerView: "6",
        direction: "horizontal",
        loop: true,
        pagination: {
            el: ".swiper-pagination",
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        scrollbar: {
            el: ".swiper-scrollbar",
        },
    });
});
