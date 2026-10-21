# Project Instructions

## Tech Stack

- Backend: Laravel / PHP
- Templates: Laravel Blade
- Reactive components: Livewire
- Styling: Tailwind CSS v4
- Build tool: Vite
- Development environment: Docker

## General Guidelines

- Follow the existing project architecture and conventions.
- Prefer simple, maintainable solutions.
- Reuse existing components and utilities.
- Avoid adding dependencies unless necessary.
- Keep changes focused on the requested task.
- Do not refactor unrelated code.
- Do not introduce Vue, React, or Inertia.

## Laravel

- Use Laravel conventions and existing project patterns.
- Prefer Eloquent relationships and query scopes where appropriate.
- Follow existing validation and authorization patterns.
- Avoid unnecessary database queries.
- Do not modify migrations unless required.
- Keep business logic out of Blade templates.
- Can update controllers if needed.
- Can update models if needed.

## Blade

- Prefer Blade components for reusable UI elements.
- Reuse existing components before creating new ones.
- Use @props for component configuration.
- Keep templates readable and avoid complex inline PHP.
- Preserve existing component attributes and slots.
- Use escaped output unless trusted HTML is required.
- Can create new components if needed.

## Livewire

- Use Livewire for server-driven interactive features.
- Follow the project's existing Livewire conventions.
- Keep component state minimal.
- Validate user input on the server.
- Avoid unnecessary requests and re-renders.
- Use Alpine.js for lightweight client-side interactions
  where appropriate and already supported.

## Styling

- Use Tailwind CSS v4 utility classes.
- Reuse existing design tokens and CSS variables.
- Follow existing responsive breakpoints.
- Preserve light and dark mode support.
- Avoid arbitrary values when existing utilities suffice.
- Prefer CSS via the @pushOnce('styles') tag for complex or repeated styling patterns.
- Avoid introducing new CSS files unnecessarily.

## JavaScript

- Prefer vanilla JavaScript for simple interactions.
- Use Alpine.js where it fits existing patterns.
- Avoid unnecessary frontend frameworks.
- Preserve progressive enhancement where possible.

## Workflow

- Inspect relevant files before changing code.
- Check for existing reusable components.
- Make minimal, targeted modifications.
- Run relevant tests or builds when possible.
- Do not modify unrelated files.
- Do not modify environment files or secrets.
- Ask before destructive operations.
