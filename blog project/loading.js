window.addEventListener("load", () => {
    const loader = document.querySelector(".loader");

    loader.classList.add("loaderHidden");
    loader.addEventListener("transitionend", () => {
        document.body.removeChild("loader");
    });
});