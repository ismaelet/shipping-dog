const onEnterViewSelector = "[data-on-enter-view]";

export function onEnterViewSetup() {
  document.addEventListener("DOMContentLoaded", function () {
    // Create the callback to be used
    let observerCallback = (entries: IntersectionObserverEntry[]) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const target = entry.target as HTMLElement;

          target.dataset.entered = "true";

          observer.unobserve(target);
        }
      });
    };

    // Create the observer
    const observer = new IntersectionObserver(observerCallback, {
      root: null,
      threshold: 0.5,
      rootMargin: "0px",
    });

    // Add the observer to each element
    document.querySelectorAll(onEnterViewSelector).forEach((element) => {
      observer.observe(element);
    });
  });
}
