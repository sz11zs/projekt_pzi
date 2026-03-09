/**
 * plugins/index.ts
 *
 * Automatically included in `./src/main.ts`
 */

import type { App } from "vue";

// Plugins
import vuetify from "./vuetify";
import router from "../router";

// Pinia
import { createPinia } from "pinia";

export function registerPlugins(app: App) {
  const pinia = createPinia();

  app.use(pinia);
  app.use(vuetify);
  app.use(router);
}
