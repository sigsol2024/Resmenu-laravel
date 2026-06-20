---
name: Lusso Modern African Luxury
colors:
  surface: '#17130e'
  surface-dim: '#17130e'
  surface-bright: '#3d3833'
  surface-container-lowest: '#110e09'
  surface-container-low: '#1f1b16'
  surface-container: '#231f1a'
  surface-container-high: '#2e2924'
  surface-container-highest: '#39342e'
  on-surface: '#eae1d9'
  on-surface-variant: '#d3c4b4'
  inverse-surface: '#eae1d9'
  inverse-on-surface: '#34302a'
  outline: '#9c8f80'
  outline-variant: '#4f4539'
  surface-tint: '#f0be78'
  primary: '#f0be78'
  on-primary: '#452b00'
  primary-container: '#b88b4a'
  on-primary-container: '#3f2700'
  inverse-primary: '#7d571b'
  secondary: '#d4c3b7'
  on-secondary: '#392e26'
  secondary-container: '#50453b'
  on-secondary-container: '#c2b2a6'
  tertiary: '#a3caf6'
  on-tertiary: '#003256'
  tertiary-container: '#7096c0'
  on-tertiary-container: '#002e4f'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#ffddb3'
  primary-fixed-dim: '#f0be78'
  on-primary-fixed: '#291800'
  on-primary-fixed-variant: '#624002'
  secondary-fixed: '#f1dfd3'
  secondary-fixed-dim: '#d4c3b7'
  on-secondary-fixed: '#231a12'
  on-secondary-fixed-variant: '#50453b'
  tertiary-fixed: '#d0e4ff'
  tertiary-fixed-dim: '#a3caf6'
  on-tertiary-fixed: '#001d35'
  on-tertiary-fixed-variant: '#20496f'
  background: '#17130e'
  on-background: '#eae1d9'
  surface-variant: '#39342e'
typography:
  display-lg:
    fontFamily: Bodoni Moda
    fontSize: 72px
    fontWeight: '700'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  display-lg-mobile:
    fontFamily: Bodoni Moda
    fontSize: 48px
    fontWeight: '700'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  headline-xl:
    fontFamily: Bodoni Moda
    fontSize: 48px
    fontWeight: '600'
    lineHeight: '1.2'
  headline-lg:
    fontFamily: Bodoni Moda
    fontSize: 32px
    fontWeight: '600'
    lineHeight: '1.2'
  headline-md:
    fontFamily: Bodoni Moda
    fontSize: 24px
    fontWeight: '500'
    lineHeight: '1.3'
  body-lg:
    fontFamily: Manrope
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Manrope
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  body-sm:
    fontFamily: Manrope
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  label-lg:
    fontFamily: Manrope
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.1em
  label-md:
    fontFamily: Manrope
    fontSize: 12px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.05em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 24px
  lg: 48px
  xl: 80px
  container-max: 1280px
  gutter: 24px
  margin-mobile: 16px
---

## Brand & Style

The design system is engineered to evoke the exclusivity of a private members' club and the warmth of a luxury West African lounge. It prioritizes intimacy and sophistication through a high-contrast editorial approach. The aesthetic merges **Modern Minimalism** with a **Tactile Luxury** feel, utilizing rich, dark surfaces to create a "dimly lit" digital environment that feels premium and focused.

The target audience is high-net-worth individuals and executive travelers who value discretion, heritage, and contemporary refinement. Visuals should lean into high-quality photography with warm, amber lighting, complemented by a layout that feels like a bespoke printed publication.

## Colors

The palette is anchored in a deep, monolithic coffee-bean brown (#211512) to provide a restful, high-end backdrop. **Accent Gold** (#B88B4A) is used sparingly for interactive elements, signifiers of quality, and calls to action, mimicking brass or gold leaf finishes found in luxury architecture.

Text contrast is carefully balanced: **Primary Text** uses an off-white bone color (#F7F2ED) to reduce eye strain on dark backgrounds while maintaining sharp legibility. **Secondary Text** (#C4B4A8) provides a muted, taupe-like transition for metadata and auxiliary information. Surface tiers create depth through subtle shifts in saturation rather than traditional gray scaling.

## Typography

The typography system relies on a high-contrast pairing: **Bodoni Moda** for editorial impact and **Manrope** for functional clarity. 

- **Display & Headlines:** Bodoni Moda is used for all major titles. Its high stroke contrast creates an immediate sense of fashion and luxury. Tighten letter spacing on larger sizes to maintain a compact, "vogue" appearance.
- **Body & UI:** Manrope provides a clean, geometric counterpoint that ensures professional readability across long-form descriptions of amenities and services.
- **System Labels:** Use uppercase Manrope with increased letter spacing for navigation, small labels, and overlines to create a rhythmic, architectural feel.

## Layout & Spacing

This design system utilizes a **Fixed Grid** model for desktop to ensure content maintains its editorial composition, centering the experience within a 1280px container. On mobile, the system transitions to a fluid 4-column layout.

The spacing rhythm is based on a strict 8px module, echoing the 8px corner radius used throughout the UI. Margins are intentionally generous ("white space" being "dark space" here) to convey a sense of calm and unhurried luxury. Larger gaps (XL) should be used between sections to allow photography and typography to breathe.

## Elevation & Depth

Depth is communicated through **Tonal Layering** and **Ambient Shadows**. Instead of harsh black shadows, this system uses ultra-diffused shadows tinted with the primary background color (#211512) to create a soft, natural lift.

1.  **Level 0 (Base):** #211512 - The main canvas.
2.  **Level 1 (Surfaces):** #2B1C18 - Used for secondary navigation or subtle sectioning.
3.  **Level 2 (Cards):** #33211D - Used for interactive elements and content containers.
4.  **Level 3 (Pop-overs):** Same as Level 2 but with a 20px blur, 15% opacity shadow to simulate floating above the surface.

Avoid heavy borders; use 1px subtle strokes in #3D2924 only when necessary to define edges on dark-on-dark components.

## Shapes

The shape language is controlled and precise. A **Soft** roundedness (8px for primary elements) is applied to maintain a modern feel without becoming overly "bubbly" or casual. This specific radius strikes a balance between the sharp lines of modernist architecture and the comfort of high-end furniture.

- **Primary UI (Buttons, Inputs, Cards):** 8px (rounded-lg).
- **Small Elements (Chips, Tags):** 4px (rounded-sm).
- **Large Sections (Image Containers):** 12px (rounded-xl).

## Components

- **Buttons:** Primary buttons use a solid Gold (#B88B4A) background with dark text (#211512). Secondary buttons are "Ghost" style with a 1px Gold border and bone-white text.
- **Input Fields:** Use a "Minimalist Tray" style—background color #2B1C18 with a subtle bottom-border of 1px in Gold when focused.
- **Cards:** Background #33211D. Use 24px padding (md). Images within cards should always have an 8px radius to match the container.
- **Chips/Badges:** Small, 4px rounded shapes with #2B1C18 background and Secondary Text color to keep them unobtrusive.
- **Lists:** Use gold-tinted dividers (1px, 10% opacity) between list items to maintain structure without visual clutter.
- **Specialty Component - The "Lusso Key":** A specific icon style using thin 1px strokes in Gold, used for primary navigation touchpoints and room status indicators.