/**
 * Enter animation
 */
export function enterAnimationSetup() {
  interface Modifiers {
    direction?: string;
    delay?: number;
  }

  const enterAnimationSelector = "[data-enter-animation]";

  // Attach the event listeners
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(enterAnimationSelector).forEach((element) => {
      // Extract modifiers from the data attribute
      const htmlElement = element as HTMLElement;

      let modifiers: Modifiers = {};

      if (htmlElement.dataset.enterAnimation) {
        const rawModifiers = htmlElement.dataset.enterAnimation.split(",");

        rawModifiers.forEach((pair) => {
          const [key, value] = pair.split(":");

          modifiers[key.trim()] = value.trim();
        });
      }

      // Setup the animation
      htmlElement.dataset.enterAnimation = "";
      htmlElement.dataset.entered = "false";

      // Setup the animation type
      switch (true) {
        default:
          htmlElement.dataset.enterAnimationType = "fade-in";
          break;
      }

      // Setup the animation direction
      switch (modifiers.direction) {
        case "top":
          htmlElement.dataset.enterAnimationDirection = "top";
          break;
        case "bottom":
          htmlElement.dataset.enterAnimationDirection = "bottom";
          break;
        case "left":
          htmlElement.dataset.enterAnimationDirection = "left";
          break;
        case "right":
          htmlElement.dataset.enterAnimationDirection = "right";
          break;
      }

      // animation delay, speed and intensity
      // 	const speed = settings ? settings[1] : null
      // 	const intensity = settings ? settings[2] : null
      if (modifiers.delay) {
        htmlElement.style.setProperty("--_enter-animation-delay", `${modifiers.delay}ms`);
      }

      // 	if (speed) {
      // 		element.style.setProperty('--_enter-animation-speed', `${speed}ms`)
      // 	}

      // 	if (intensity) {
      // 		element.style.setProperty('--_enter-animation-intensity', `${intensity}rem`)
      // 	}
    });
  });
}
