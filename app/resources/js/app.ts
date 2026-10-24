import.meta.glob(["../images/**"]);

import { onEnterViewSetup } from "./view_events";
import { advancedInputBehaviorSetup } from "./inputs";
import { enterAnimationSetup } from "./animations";
import { themeSwitcherSetup } from "./theme";

import portfolioUpload from "./components/portfolio-upload";
import portfolioSorter from "./components/portfolio-sorter";

document.addEventListener("alpine:init", () => {
  window.Alpine.data("portfolioUpload", portfolioUpload);
  window.Alpine.data("portfolioSorter", portfolioSorter);
});

enterAnimationSetup();
advancedInputBehaviorSetup();
onEnterViewSetup();
themeSwitcherSetup();
