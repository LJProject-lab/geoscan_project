(function () {
  "use strict";

  const select = (el, all = false) => {
    el = el.trim();
    if (all) {
      return [...document.querySelectorAll(el)];
    } else {
      return document.querySelector(el);
    }
  };

  /**
   * Preloader
   */
  let preloader = select("#preloader");
  const showPreloader = () => {
    if (preloader) {
      preloader.style.display = "flex";
      preloader.style.opacity = "1"; // Ensure it's fully visible
    }
  };

  const hidePreloader = () => {
    if (preloader) {
      preloader.style.transition = "opacity 0.5s ease";
      preloader.style.opacity = "0";
      setTimeout(() => {
        preloader.style.display = "none";
      }, 500); // Match the delay with the transition duration
    }
  };

  window.addEventListener("load", hidePreloader);

  window.showPreloader = showPreloader;
  window.hidePreloader = hidePreloader;

  new PureCounter();
})();
