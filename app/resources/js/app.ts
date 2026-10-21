import "./bootstrap.js";
import.meta.glob(["../images/**"]);

import "alpinejs";

import { onEnterViewSetup } from "./view_events";
import { advancedInputBehaviorSetup } from "./inputs";
import { enterAnimationSetup } from "./animations";
import { themeSwitcherSetup } from "./theme";

enterAnimationSetup();
advancedInputBehaviorSetup();
onEnterViewSetup();
themeSwitcherSetup();
