import.meta.glob(["../images/**"]);

import { onEnterViewSetup } from "./view_events";
import { advancedInputBehaviorSetup } from "./inputs";
import { enterAnimationSetup } from "./animations";
import { themeSwitcherSetup } from "./theme";

import customProductEditor from "./components/custom-product-editor";
import portfolioUpload from "./components/portfolio-upload";
import portfolioSorter from "./components/portfolio-sorter";

document.addEventListener("alpine:init", () => {
  window.Alpine.data("customProductEditor", customProductEditor);
  window.Alpine.data("portfolioUpload", portfolioUpload);
  window.Alpine.data("portfolioSorter", portfolioSorter);
});

enterAnimationSetup();
advancedInputBehaviorSetup();
onEnterViewSetup();
themeSwitcherSetup();

// Keep the scroll position when navigating through links marked with data-preserve-scroll
let preservedScrollY: number | null = null;

document.addEventListener("click", (event) => {
  const link = (event.target as Element).closest("[data-preserve-scroll]");

  preservedScrollY = link ? window.scrollY : null;
});

document.addEventListener("livewire:navigated", () => {
  if (preservedScrollY === null) return;

  const y = preservedScrollY;

  preservedScrollY = null;
  window.scrollTo({ top: y, behavior: "instant" });
  requestAnimationFrame(() => window.scrollTo({ top: y, behavior: "instant" }));
});
