# UI Component Guidelines

The RTIH Experience Platform uses a strict Shared UI layer (`resources/views/components/ui`).

## Rules for Creating Components

1. **Token Consumption**: Components MUST use Tailwind Design Tokens. Do not hardcode specific hex colors or arbitrary pixel values. Use classes like `bg-primary-600`, `rounded-2xl`, `shadow-floating`.
2. **Domain-Specific Directories**: Place components in their respective domain folders:
   - `ui/buttons/` (e.g. primary, secondary, destructive)
   - `ui/forms/` (e.g. input, select, textarea, toggle)
   - `ui/layout/` (e.g. card, modal, slide-over)
   - `ui/feedback/` (e.g. toast, skeleton, empty-state, badge)
3. **No Business Logic**: UI components must be completely ignorant of business logic. They receive primitive data (strings, booleans, arrays) and emit events (via Alpine.js or native HTML). Never pass Eloquent Models directly into generic UI primitives.
4. **Alpine.js Integration**: For interactive components (dropdowns, modals, tabs), encapsulate the Alpine state within the component itself (`x-data`).
5. **Accessibility**: Include standard ARIA attributes (`aria-hidden`, `aria-expanded`, `role`) on interactive elements.
