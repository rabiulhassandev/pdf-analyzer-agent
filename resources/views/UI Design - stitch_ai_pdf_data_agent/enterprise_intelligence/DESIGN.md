---
name: Enterprise Intelligence
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#45464d'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#76777d'
  outline-variant: '#c6c6cd'
  surface-tint: '#565e74'
  primary: '#000000'
  on-primary: '#ffffff'
  primary-container: '#131b2e'
  on-primary-container: '#7c839b'
  inverse-primary: '#bec6e0'
  secondary: '#0058be'
  on-secondary: '#ffffff'
  secondary-container: '#2170e4'
  on-secondary-container: '#fefcff'
  tertiary: '#000000'
  on-tertiary: '#ffffff'
  tertiary-container: '#0b1c30'
  on-tertiary-container: '#75859d'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dae2fd'
  primary-fixed-dim: '#bec6e0'
  on-primary-fixed: '#131b2e'
  on-primary-fixed-variant: '#3f465c'
  secondary-fixed: '#d8e2ff'
  secondary-fixed-dim: '#adc6ff'
  on-secondary-fixed: '#001a42'
  on-secondary-fixed-variant: '#004395'
  tertiary-fixed: '#d3e4fe'
  tertiary-fixed-dim: '#b7c8e1'
  on-tertiary-fixed: '#0b1c30'
  on-tertiary-fixed-variant: '#38485d'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  display-md:
    fontFamily: Inter
    fontSize: 36px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 30px
    fontWeight: '600'
    lineHeight: '1.3'
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
  title-lg:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: '1.4'
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.6'
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: '1'
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.03em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 24px
  lg: 40px
  xl: 64px
  container-max: 1440px
  gutter: 24px
---

## Brand & Style

This design system is engineered for high-stakes enterprise environments where clarity, speed, and intelligence are paramount. The brand personality is authoritative yet unobtrusive, positioning the AI as a powerful co-pilot rather than a distracting novelty. 

The aesthetic draws from **Minimalism** and **Modern Corporate** movements. It prioritizes functional density—balancing the need for large data sets with expansive white space to prevent cognitive overload. The visual language conveys a sense of "quiet luxury" through precision, intentionality, and a lack of decorative fluff. Every pixel must serve a purpose in the user's workflow.

## Colors

The palette is anchored by **Deep Navy** (#0F172A) for core structural elements and high-level typography, providing a grounded, professional foundation. **Crisp White** (#FFFFFF) serves as the primary canvas, ensuring the interface feels airy and modern.

The **Electric Blue** (#3B82F6) is reserved for primary actions, active states, and AI-driven insights, creating a clear visual hierarchy for the "intelligence layer" of the product. **Subtle Grays** are utilized for borders and secondary text to maintain low visual noise. Accent colors for success, warning, or error states should be desaturated to maintain the premium enterprise feel.

## Typography

The design system utilizes **Inter** for all typographic needs. Inter’s tall x-height and systematic design make it the ideal choice for data-heavy enterprise applications. 

Headlines use a tighter letter-spacing and heavier weights to command attention, while body text is optimized for long-form readability with a generous line height (1.6). For data labels and micro-copy, we use semi-bold weights at smaller sizes to ensure legibility against various background tints. Use "Deep Navy" for primary text and "Slate Gray" for secondary descriptions.

## Layout & Spacing

This design system follows a **Fixed Grid** philosophy for dashboard views to ensure consistency across complex data visualizations, switching to a **Fluid Grid** for content-heavy internal pages. A strictly enforced 8px linear scale governs all padding and margins.

The layout uses "Generous Whitespace" as a functional tool. By increasing margins around critical AI outputs, we signal importance and reduce user fatigue. Standard containers should have a minimum internal padding of `md` (24px). Sidebar navigation is fixed at 280px to provide a stable anchor for the application.

## Elevation & Depth

Hierarchy is established through **Ambient Shadows** and **Tonal Layering**. Instead of heavy shadows, we use extremely diffused, low-opacity shadows (e.g., `0 4px 20px rgba(15, 23, 42, 0.05)`) to lift cards off the background.

We employ a "Layered Surface" strategy:
1. **Level 0 (Background):** Neutral Gray (#F8FAFC) - The base canvas.
2. **Level 1 (Cards/Containers):** Pure White (#FFFFFF) - The primary work area, featuring a 1px subtle border (#E2E8F0).
3. **Level 2 (Modals/Popovers):** Pure White with a slightly more pronounced ambient shadow to indicate focus.

Avoid heavy gradients. Depth should feel natural and atmospheric, not forced.

## Shapes

The design system utilizes a **Rounded** (0.5rem) shape language. This curvature strikes the balance between the clinical sharpness of legacy enterprise software and the overly "bubbly" feel of consumer apps.

Primary containers and cards use `rounded-lg` (1rem) to create a soft, high-end framing effect. Smaller interactive components like checkboxes, inputs, and tags use the base `rounded` (0.5rem). High-action items, such as primary call-to-action buttons, may occasionally use a pill-shape for maximum contrast against the rectangular grid.

## Components

### Buttons
Primary buttons use the Electric Blue background with white text and no border. Secondary buttons use a white background with a subtle gray border and navy text. Use a "Hover" state that slightly darkens the background and increases shadow depth.

### Input Fields
Fields feature a subtle gray border (#E2E8F0). On focus, the border transitions to Electric Blue with a faint blue outer glow (2px). Labels should always be visible above the field in `label-md` style.

### Cards
Cards are the primary organizational unit. They must have a white background, a 1px subtle border, and a soft ambient shadow. Header sections within cards should be separated by a light horizontal rule.

### AI Components
- **Insight Chips:** Small, pill-shaped badges with a light blue tint and navy text used to highlight AI-generated tags.
- **Status Indicators:** Pulsing subtle glows for "Active Processing" states.
- **Actionable Lists:** Clean rows with generous vertical padding (16px), separated by hair-line dividers.

### Data Tables
Tables should use a "Flat" style—no vertical borders, only horizontal dividers. The header row should have a subtle gray background tint to anchor the data.