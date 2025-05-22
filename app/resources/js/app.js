import './bootstrap'
import.meta.glob(['../images/**'])

import './app'
import 'alpinejs'

import { onEnterViewSetup } from './view_events.ts'
import { advancedInputBehaviorSetup } from './inputs.ts'
import { enterAnimationSetup } from './animations.ts'
import { colorSchemeSwitcherSetup } from './color_scheme.ts'

enterAnimationSetup()
advancedInputBehaviorSetup()
onEnterViewSetup()
colorSchemeSwitcherSetup()
