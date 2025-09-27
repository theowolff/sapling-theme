![Sapling Logo](./sapling.svg)
# Sapling Starter Theme

A lean parent theme providing the base structure, asset pipeline, and common scaffolding for child themes.

---

## File Structure

- **`src/scss`**
  - `_globals.scss` → global resets and base rules
  - `_variables.scss` → design tokens (colors, spacing, breakpoints)
  - `main.scss` → entry point, imports globals + variables
- **`src/js`**
  - `main.js` → core interactions, helper functions, and defaults
- **`extras/`**
  - `helpers.php` → generic PHP helper functions
  - `setup.php` → theme setup (enqueue, supports, menus, etc.)
  - `theme-functions.php` → WordPress-specific functions and filters

## Conventions

- SCSS uses `@use` and `@forward` for modularity.
- JavaScript uses ES2018, bundled with esbuild into `dist/js`.
- Assets always compiled to `dist/`:
  - `dist/css/main.min.css`
  - `dist/js/main.min.js`

Child themes extend this foundation, overriding variables and adding their own assets.
