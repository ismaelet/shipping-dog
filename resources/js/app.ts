import { onEnterViewSetup } from "./view_events.ts";
import { enterAnimationSetup } from "./animations.ts";
import { colorSchemeSwitcherSetup } from "./color_scheme.ts";

enterAnimationSetup();
onEnterViewSetup();
colorSchemeSwitcherSetup();
